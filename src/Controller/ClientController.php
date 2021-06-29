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
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ClientController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        
    }
    /**
     * @Route("/api/clients", name="add_client")
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
                ->setAdresseDomicile(($values->adresseDomicile))
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

    /**
     * @Route("/api/client/profil", name="list_client")
     */
    public function listClient(SerializerInterface $serializer)
    {
        $user = $this->tokenStorage->getToken()->getUser(); 
        $dataTable = $serializer->serialize($user, 'json');

        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    
}
