<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;

final class OpenApiFactory implements OpenApiFactoryInterface
{
    private OpenApiFactoryInterface $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        // On définit manuellement notre route /api/admin
        $pathItem = new Model\PathItem(
            ref: 'Admin',
            summary: 'Espace Administration',
            description: 'Accéder à l\'API d\'administration',
            get: new Model\Operation(
                operationId: 'getApiAdmin',
                tags: ['Administration'],
                responses: [
                    '200' => [
                        'description' => 'Message de bienvenue de l\'admin',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => ['type' => 'string']
                                    ]
                                ]
                            ]
                        ]
                    ],
                    '401' => ['description' => 'JWT non fourni ou invalide'],
                    '403' => ['description' => 'Droits insuffisants (ROLE_ADMIN requis)']
                ],
                summary: 'Récupère le message d\'accueil admin.',
                security: [['JWT' => []]] // Associe cette route à la sécurité JWT configurée
            )
        );

        // On ajoute la route au catalogue OpenAPI d'API Platform
        $openApi->getPaths()->addPath('/api/admin', $pathItem);

        return $openApi;
    }
}