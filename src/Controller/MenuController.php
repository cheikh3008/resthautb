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

class MenuController extends AbstractController
{
    // /**
    //  * @Route("/api/menu/add", name="add_menu", methods={"POST"})
    //  */
    // public function add(Request $request, EntityManagerInterface $manager): Response
    // {
    //     $menu = new Menu();
    //     $categorie = $request->request->all()["categorie"];
    //     $image = $request->files->get("image");
    //     $image = fopen($image->getRealPath(),"rb");
    //     $menu->setImage($image)
    //         ->setCategorie($categorie);
    //     $manager->persist($menu);
    //     $manager->flush();
    //     fclose($image);
    //     $data = [

    //         'status' => 201,
    //         'message' => 'Votre menu a été crée avec succes. '
    //     ];

    //     return new JsonResponse($data, 201);
    // }
    // /**
    //  * @Route("/api/menu/list", name="list_menu", methods={"GET"})
    //  */
    // public function listerPlatByUserId(MenuRepository $menuRepository  ,SerializerInterface $serializer): Response
    // {
    //     $data = $menuRepository->findAll();
    //     $dataTable = [];
    //     $dataTable = $serializer->serialize($data, 'json');
    //     return new Response($dataTable, 200, [
    //         'Content-Type' => 'application/json'
    //     ]);

    // }
    
}
