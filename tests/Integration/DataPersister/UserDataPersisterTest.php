<?php

// namespace App\Tests;

// use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

// class UserDataPersisterTest extends KernelTestCase
// {
//     public function testSomething(): void
//     {
//         $kernel = self::bootKernel();

//         $this->assertSame('test', $kernel->getEnvironment());
//         // $routerService = static::getContainer()->get('router');
//         // $myCustomService = static::getContainer()->get(CustomService::class);
//     }
// }



namespace App\Tests\Integration\DataPersister;

use ApiPlatform\Metadata\Post;
use App\DataPersister\UserDataPersister;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[CoversClass(UserDataPersister::class)]
class UserDataPersisterTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;
    private ?UserDataPersister $persister;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        
        $this->persister = new UserDataPersister($this->entityManager, $passwordHasher);
    }

    // Test if when we create a new User we succeed, if the password is not equal to the one we gave (the password should be hashed so not the same) and if ROLE_USER is attributed correctly
   #[Test]
    public function testPersistNewUser(): void
    {
        // Arrange
        $email = 'test_'.uniqid().'exemple.com' ; // Email Unique 
        $user = new User();
        $user->setEmail($email);
        $user->setPlainPassword('password123');

        $operation = new Post();

        // Act
        $result = $this->persister->process(
            $user,
            $operation,
            [],
            []
        );

        // Assert
        $savedUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        // $this->assertNotNull($result);
        // $this->assertNotEquals('password123', $result->getPassword());
        // $this->assertNotNull($result->getPlainPassword());
        // $this->assertEquals(['ROLE_USER'], $result->getRoles());

        $this->assertNotNull($savedUser, "L'utilisateur aurait dû être enregistré en BDD.");
        // On vérifie que le mot de passe est bien haché et PAS égal au mot de passe en clair
        $this->assertNotNull($savedUser->getPassword());
        $this->assertNotEquals('password123', $savedUser->getPassword());
        // On vérifie que plainPassword a bien été effacé par eraseCredentials()
        $this->assertNull($savedUser->getPlainPassword());  
        // On vérifie les rôles
        $this->assertContains('ROLE_USER', $savedUser->getRoles());
    }
    
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // On nettoie la base de données après chaque test
        if ($this->entityManager) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }
}