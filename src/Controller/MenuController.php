<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\RestoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MenuController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    /**
     * @Route("/api/menu/add", name="add_menu", methods={"POST"})
     */
    public function add(Request $request, RestoRepository $restoRepository,EntityManagerInterface $manager): Response
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $resto = $restoRepository->findBy(["user" => $userConnecte->getId()]);
        // dd($resto);
        $menu = new Menu();
        $categorie = $request->request->all()["categorie"];
        $image = $request->files->get("image");
        $image = fopen($image->getRealPath(),"rb");
        $menu->setImage($image)
            ->setResto($resto["0"])
            ->setUser($userConnecte)
            ->setCategorie($categorie);
        $manager->persist($menu);
        $manager->flush();
        fclose($image);
        $data = [

            'status' => 201,
            'message' => 'Votre menu a été crée avec succes. '
        ];

        return new JsonResponse($data, 201);
    }

    /**
     * @Route("/api/menu/list", name="list_menu_user", methods={"GET"})
     * cette fonction permet de lister l'ensemble de menu du resto de user cconnected
     */
    public function listerMenuByUserId(MenuRepository $menuRepository, RestoRepository $restoRepository, SerializerInterface $serializer): Response
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $userId = $restoRepository->findUserById($userConnecte->getId());
        $data = $menuRepository->findBy(["resto" => $userId["0"]]);
        $dataTable = [];
        $dataTable = $serializer->serialize($data, 'json');
        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);

    }
    
    /**
     * @Route("/api/menu/list/{id}", name="list_menu_resto", methods={"GET"})
     * cette fonction permet de lister l'ensemble de menu du resto via son id resto
     */
    public function listerMenuByRestoId($id, MenuRepository $menuRepository, RestoRepository $restoRepository, SerializerInterface $serializer): Response
    {
        
        $resto = $restoRepository->find($id);
        $data = $menuRepository->findBy(["resto" => $resto]);
        $dataTable = [];
        $dataTable = $serializer->serialize($data, 'json');
        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);

    }
}
