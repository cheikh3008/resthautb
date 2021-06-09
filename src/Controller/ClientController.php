<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientController extends AbstractController
{
    /**
     * @Route("/api/clients", name="client")
     */
    public function index(Request $request, UserRepository $userRepository ,EntityManagerInterface $manager,  UserPasswordEncoderInterface $passwordEncode, RoleRepository $roleRepository): Response
    {
        $values = json_decode($request->getContent());
        $role = $roleRepository->findOneBy(array('libelle' => 'ROLE_CLIENT'));
        $user = new User();
        $usernameExiste = $userRepository->findOneBy(array("username" =>$values->username));
        if($usernameExiste == true){
            $data = [

                'status' => 500,
                'message' => 'Cette username a été déja utilisé. '
            ];
    
            return new JsonResponse($data, 500);
        }
        $user->setPassword($passwordEncode->encodePassword($user, $values->password))
                ->setRole($role)
                ->setUsername($values->username)
                ->setAdresse(($values->adresse))
                ->setNomComplet($values->nomComplet)
                ->setTelephone($values->telephone);
        $manager->persist($user);
        $manager->flush();
        $data = [

            'status' => 201,
            'message' => 'Votre compte a été crée avec succes. '
        ];

        return new JsonResponse($data, 201);
    }
}
