<?php

namespace App\Action;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
final class HomeAction
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Bienvenue sur l’API BookMarket',
        ]);
    }
}