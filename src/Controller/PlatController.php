<?php

namespace App\Controller;

use App\Entity\Plat;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\UserRepository;
use App\Repository\RestoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PlatController extends AbstractController
{
    private $tokenStorage;
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    // public function __invoke(Plat $data, RestoRepository $restoRepository)
    // {
    //     $userConect = $this->tokenStorage->getToken()->getUser();
    //     $userId = $userConect->getId();
    //     $resto = $restoRepository->findRestoById($userId);
    //     $data->setResto($resto["0"]);
    //     $data->setUser($userConect);
    //     return $data;
    // }
    /**
     * @Route("/api/plat/add", name="add_plat", methods={"POST"})
     */
    public function add(RestoRepository $restoRepository, MenuRepository $menuRepository , Request $request, EntityManagerInterface $manager) 
    {
        //$values = json_decode($request->getContent());
        $userConect = $this->tokenStorage->getToken()->getUser();
        $userId = $userConect->getId();
        $resto = $restoRepository->findUserById($userId);
        $plat= new Plat();
        $nomPlat = $request->request->all()["nomPlat"];
        $description = $request->request->all()["description"];
        $prix = $request->request->all()["prix"];
        $menu = $request->request->all()["menu"];
        $menuId = $menuRepository->findOneBy(array("id" => $menu));
        //Add image de type blob
        $image = $request->files->get("image");
        $image = fopen($image->getRealPath(),"rb");
        $plat->setResto($resto["0"])
            ->setNomPlat($nomPlat)
            ->setDescription($description)
            ->setPrix($prix)
            ->setImage($image)
            ->setUser($userConect)
            ->setMenu($menuId);
        $manager->persist($plat);
        $manager->flush();
        fclose($image);
        $data = [

            'status' => 201,
            'message' => 'Votre plat a été crée avec succes. '
        ];

        return new JsonResponse($data, 201);
    }
    /**
     * @Route("/api/plat/list", name="list_plat", methods={"GET"})
     */
    public function listerPlatByUserId(PlatRepository $platRepository,MenuRepository $menuRepository ,RestoRepository $restoRepository ,SerializerInterface $serializer): Response
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $userId = $restoRepository->findUserById($userConnecte->getId());
        $data = $platRepository->findBy(["resto" => $userId["0"]]);
        foreach ($data as $value) {
            $value->setImage((base64_encode(stream_get_contents($value->getImage()))));
        }
        $dataTable = $serializer->serialize($data, 'json');

        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);

    }
     /**
     * @Route("/api/plat/list/{id}", name="list_plat_resto_id", methods={"GET"})
     */
    public function listerPlatByRestoId(PlatRepository $platRepository, $id,RestoRepository $restoRepository ,SerializerInterface $serializer): Response
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $userId = $restoRepository->findRestoById($id);
        $data = $platRepository->findBy(["resto" => $userId]);
        $dataTable = [];
        foreach ($data as $value) {
            $value->setImage((base64_encode(stream_get_contents($value->getImage()))));
        }
        $dataTable = $serializer->serialize($data, 'json');
        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);

    }
}
