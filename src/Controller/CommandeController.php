<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    /**
     * @Route("/api/commande", name="add_commande")
     */
    public function index(): Response
    {
        return new Response('test ok', 200, [
            'Content-Type' => 'application/json'
        ]);
    }

}
