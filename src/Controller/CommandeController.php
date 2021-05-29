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
     * @Route("/api/panier/{id}", name="add_panier")
     */
    public function index($id , SessionInterface $session, SerializerInterface $serializer): Response
    {
        $panier = $session->get('panier', []) ; 
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);
        $data = $serializer->serialize($panier, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/api/paniers", name="list_panier")
     */
    public function list (SessionInterface $session, SerializerInterface $serializer, PlatRepository $platRepository)
    {
        $panier = $session->get('panier', []) ; 
        $dataPanier = [];
        foreach ($panier as $id => $quantite) {
            $dataPanier [] = [
                'plat' => $platRepository->find($id),
                'quantite' => $quantite,
            ];
        }
        
        foreach ($dataPanier as $value) {
            $value['plat']->setImage((base64_encode(stream_get_contents($value['plat']->getImage()))));
        }
        $data = $serializer->serialize($dataPanier, 'json');
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
