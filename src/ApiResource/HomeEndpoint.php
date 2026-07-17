<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model;
use App\Action\HomeAction;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/home',
            controller: HomeAction::class,
            read: false,
            openapi: new Model\Operation(
                summary: 'Page d’accueil',
                description: 'Retourne un message de bienvenue indiquant que l’API BookMarket est disponible.',
                tags: ['Home'],
                responses: [
                    200 => new Model\Response(
                        description: 'Réponse de bienvenue',
                        content: new \ArrayObject([
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'message' => [
                                            'type' => 'string',
                                            'example' => 'Bienvenue sur l’API BookMarket',
                                        ],
                                    ],
                                    'required' => ['message'],
                                ],
                                'example' => [
                                    'message' => 'Bienvenue sur l’API BookMarket',
                                ],
                            ],
                        ])
                    ),
                ]
            )
        )
    ]
)]
class HomeEndpoint
{
}