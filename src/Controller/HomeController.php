<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/gfgdgfg', name: 'gffdgdgd')]
final class HomeController extends AbstractController
{
    // #[Route('/gdfgdg', name: 'api_home')]
    // public function hello()
    // {
      
    //     return $this->json([
    //         'message' => "Bienvenue sur l'API de BookMarket ! 🎉",
    //     ]);

    //     // return $this->render('home/home.html.twig');
    // }

      #[Route('/gfdgd', name: 'gfdgfd')]
    public function office()
    {
      
        return $this->json([
            'message' => "Bienvenue sur l'API de BookMarket ! 🎉",
        ]);

        // return $this->json(["message"=>"super ca marche"],status:200);
    }
}
