<?php

namespace App\Tests\Functional\Controller;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpTest extends WebTestCase
{
    #[Test]
    public function registerBook(): void
    {
        $client = static::createClient();
        $container = static::getContainer();

        // ---------------------------------------------------------------
        // ÉTAPE 0 : Créer / Récupérer l'utilisateur en BDD
        // ---------------------------------------------------------------
        $em = $container->get('doctrine')->getManager();
        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => 'user5@user5.com']);

        if (!$user) {
            $hasher = $container->get('security.user_password_hasher');
            $user = new User();
            $user->setEmail('user5@user5.com');
            $user->setPassword($hasher->hashPassword($user, 'user5user5'));

            $em->persist($user);
            $em->flush();
        }

        // ---------------------------------------------------------------
        // ÉTAPE 1 : Générer le token via le service JWT
        // ---------------------------------------------------------------
        /** @var JWTTokenManagerInterface $jwtManager */
        $jwtManager = $container->get('lexik_jwt_authentication.jwt_manager');
        $token = $jwtManager->create($user);

        // ---------------------------------------------------------------
        // ÉTAPE 2 : Effectuer la requête de création avec le token
        // ---------------------------------------------------------------
        $data = [
            'title'  => 'un super titre',
            'author' => 'un super auteur'
        ];

        $client->request(
            'POST',
            '/api/books',
            [],
            [],
            [
                'CONTENT_TYPE'       => 'application/ld+json',
                'HTTP_ACCEPT'        => 'application/ld+json',
                'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            ],
            json_encode($data)
        );

        $reponseContent = json_decode($client->getResponse()->getContent(), true);
        dump($reponseContent);

        // ---------------------------------------------------------------
        // ÉTAPE 3 : Assertions
        // ---------------------------------------------------------------
        $this->assertResponseStatusCodeSame(201);
        $this->assertSame('un super titre', $reponseContent['title']);
        $this->assertSame('un super auteur', $reponseContent['author']);
    }
}