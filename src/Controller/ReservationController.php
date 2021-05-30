<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Repository\RoleRepository;
use App\Repository\RestoRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReservationController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        
    }
    /**
     * @Route("/api/add/reservation/client", name="reservation")
     */
    public function addReservationByClient(Request $request, EntityManagerInterface $manager ,RoleRepository $roleRepository, RestoRepository $restoRepository): Response
    {
        $values = json_decode($request->getContent());
        $reservation = new Reservation();
        $role = $roleRepository->findOneBy(array('libelle' => 'ROLE_CLIENT'));
        $resto = $restoRepository->findOneBy(array('id' => $values->resto));
        if ($resto == null) {
            $data = [
                'status' => 500,
                'message' => 'ce resto n\'existe pas . '];
    
            return new JsonResponse($data, 500);
        }
        $reservation->setNomComplet($values->nomComplet)
                    ->setTelephone($values->telephone)
                    ->setNbPersonne($values->nbPersonne)
                    ->setCreatedAt(\DateTime::createFromFormat('Y-m-d', $values->createdAt))
                    ->setHeure(\DateTime::createFromFormat('H:m', $values->heure))
                    ->setResto($resto);
        $manager->persist($reservation);
        $manager->flush();
        $data = [
            'status' => 201,
            'message' => 'Votre réservation a été bien enrégistré. '];

        return new JsonResponse($data, 201);
    }
    /**
     * @Route("/api/add/reservation/gerant", name="add_reservation_client")
     */
    public function addReservationByGerant(Request $request, RestoRepository $restoRepository ,EntityManagerInterface $manager)
    {
        $values = json_decode($request->getContent());
        $reservation = new Reservation();
        if ( $this->getUser() == null) {
            $data = [
                'status' => 500,
                'message' => 'ce resto n\'existe pas . '];
    
            return new JsonResponse($data, 500);
        }
        $userConnecte = $this->getUser();
        $resto = $restoRepository->findBy(["user" => $userConnecte]);
        $reservation->setNomComplet($values->nomComplet)
                    ->setTelephone($values->telephone)
                    ->setNbPersonne($values->nbPersonne)
                    ->setCreatedAt(\DateTime::createFromFormat('Y-m-d', $values->createdAt))
                    ->setHeure(\DateTime::createFromFormat('H:m', $values->heure))
                    ->setResto($resto["0"]);
        $manager->persist($reservation);
        $manager->flush();
        $data = [
            'status' => 201,
            'message' => 'Votre réservation a été bien enrégistré. '];

        return new JsonResponse($data, 201);
    }

    /**
     * @Route("/api/list/reservation", name="add_reservation_gerant")
     */
    public function list(ReservationRepository $reservationRepository, SerializerInterface $serializer, RestoRepository $restoRepository)
    {
        $userConnecte = $this->getUser();
        $resto = $restoRepository->findBy(["user" => $userConnecte]);
        $data = $reservationRepository->findBy(["resto" => $resto]);
        foreach ($data as  $value) {
           date_format( $value->getCreatedAt(), 'Y-m-d');
           date_format( $value->getheure(), 'H:m');
        }
        $dataTable = $serializer->serialize($data, 'json');

        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}
