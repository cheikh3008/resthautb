<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Reservation;
use App\Repository\RoleRepository;
use App\Repository\RestoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReservationController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        
    }
    /**
     * @Route("/api/reservation", name="reservation")
     */
    public function index(Request $request, EntityManagerInterface $manager,
    UserPasswordEncoderInterface $passwordEncode,RoleRepository $roleRepository, RestoRepository $restoRepository): Response
    {
        $values = json_decode($request->getContent());
        $user = new User();
        $reservation = new Reservation();
        $role = $roleRepository->findOneBy(array('libelle' => 'ROLE_CLIENT'));
        $resto = $restoRepository->findOneBy(array('id' => $values->resto));
        if ($resto == null) {
            $data = [
                'status' => 500,
                'message' => 'ce resto n\'existe pas . '];
    
            return new JsonResponse($data, 500);
        }
        $user->setPassword($passwordEncode->encodePassword($user, $values->password))
            ->setRole($role)
            ->setUsername($values->username)
            ->setNomComplet($values->nomComplet)
            ->setTelephone($values->telephone);
        $manager->persist($user);
        $reservation->setUser($user)
                    ->setNbPersonne($values->nbPersonne)
                    ->setResto($resto);
        $manager->persist($reservation);
        $manager->flush();
        $data = [
            'status' => 201,
            'message' => 'Votre réservation a été bien enrégistrer. '];

        return new JsonResponse($data, 201);
    }
}
