<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model;
use App\Action\AdminAction;
use App\Action\HomeAction;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/admin',
            controller: AdminAction::class,
            read: false,
          
)])]
class AdminEndpoint
{
}