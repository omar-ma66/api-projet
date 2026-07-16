<?php
// Ce fichier est a créer dans src/DataPersister
// namespace App\DataPersister;

// use ApiPlatform\Metadata\Operation;
// use ApiPlatform\State\ProcessorInterface;
// use App\Entity\User;
// use Doctrine\ORM\EntityManagerInterface;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// class UserDataPersister implements ProcessorInterface
// {
//     public function __construct(
//         private readonly EntityManagerInterface $entityManager,
//         private readonly UserPasswordHasherInterface $passwordHasher
//     ) {}

//     public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
//     {
//         if ($data instanceof User) {
//             if ($data->getPassword()) {
//                 $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPassword());
//                 $data->setPassword($hashedPassword);
//             }
//             $data->setRoles(['ROLE_USER']);

//             $this->entityManager->persist($data);
//             $this->entityManager->flush();
//         }

//         return $data;
//     }
// }

namespace App\DataPersister;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements ProcessorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * @param User $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if ($data instanceof User) {
            // 1. On vérifie et récupère le mot de passe EN CLAIR (plainPassword)
         
            if ($data->getPlainPassword()) {
                $hashedPassword = $this->passwordHasher->hashPassword(
                    $data, 
                    $data->getPlainPassword() // <-- On hache le mot de passe en clair !
                );
                $data->setPassword($hashedPassword);
                
                // 2. On efface le mot de passe en clair par sécurité
                $data->eraseCredentials();
            }
            
            // 3. Attribution automatique du rôle par défaut
            $data->setRoles(['ROLE_USER']);

            // 4. Enregistrement en base de données
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        }

        return $data;
    }
}