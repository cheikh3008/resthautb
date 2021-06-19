<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PasswordController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @Route("/api/password/update", name="update_password")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $values = json_decode($request->getContent());
        $oldPassword = $values->oldPassword;
        $newPassword = $values->newPassword;
        if (!$user) {
            $data = [

                'status' => 401,
                'message' => 'Le token est invalide. '
            ];

            return new JsonResponse($data, 401);
        }
        if (!password_verify($oldPassword, $user->getPassword())) {
            $data = [

                'status' => 500,
                'message' => 'Le mot de passe actuel saisi est incorrect. '
            ];

            return new JsonResponse($data, 500);
        }
        $hash = $passwordEncoder->encodePassword($user, $newPassword);
        $user->setPassword($hash);
        $manager->persist($user);
        $manager->flush();
        $data = [

            'status' => 201,
            'message' => 'Votre mot passe a été modifié avec succes. '
        ];

        return new JsonResponse($data, 201);
    }
}
