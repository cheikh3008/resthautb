<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\RestoRepository;
use App\Repository\TablesRepository;
use App\Repository\CommandeRepository;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CountController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @Route("/api/count", name="count")
     */
    public function count(
        SerializerInterface $serializer,
        MenuRepository $menuRepository,
        CommandeRepository $commandeRepository,
        ReservationRepository $reservationRepository,
        TablesRepository $tablesRepository, 
        RestoRepository $restoRepository,
        CommandeController $commandeController
        ): Response
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $resto = $restoRepository->findOneBy(["user" => $userConnecte]);
        $menu = $menuRepository->findby(["user" => $userConnecte]);
        $table = $tablesRepository->findby(["user" => $userConnecte]);
        // liste des commandes par resto 
        $cmd = $commandeRepository->findAll();
        $dataCommandes = [];
        foreach ($cmd as $key => $commandes) {
            foreach ($commandes->getPlatCommandes() as $value) {
                if($value->getPlat()->getUser()->getId() === $resto->getUser()->getId()) {
                    $dataCommandes [] = $commandes;
                }
            }

        }
        $commande = [];
        foreach (array_unique($dataCommandes, SORT_REGULAR) as  $value) {
            $commande [] = $value;
        }
        // liste des reservations par resto 
        $data = $reservationRepository->findAll();
        $dataReservations = [];
        foreach ($data as $key => $reservation) {
            foreach ($reservation->getTables() as $key => $value) {
                if($value->getResto()->getId() === $resto->getId()) {
                    $dataReservations [] = $reservation;
                }
            }

        }
        $reservation = [];
        foreach (array_unique($dataReservations, SORT_REGULAR) as  $value) {
            $reservation [] = $value;
        }
        $data = [
            "commande" => count($commande),
            "reservation" => count($reservation),
            "menu" => count($menu),
            "table" => count($table),
        ];
        $res = $serializer->serialize($data, 'json');
        return new Response($res, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
