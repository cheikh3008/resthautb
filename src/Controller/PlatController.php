<?php

namespace App\Controller;

use App\Entity\Plat;
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
        header('Content-Type: application/json; charset=utf-8');
        header("Access-Control-Allow-Methods:  POST");
        $userConect = $this->tokenStorage->getToken()->getUser();
        $userId = $userConect->getId();
        $resto = $restoRepository->findRestoById($userId);
        $plat= new Plat();
        $nomPlat = $request->request->all()["nomPlat"];
        $description = $request->request->all()["description"];
        $prix = $request->request->all()["prix"];
        $menu = $request->request->all()["menu"];
        $m = $menuRepository->findOneBy(array("id" => $menu));
        $image = $request->files->get("image");
        $image = fopen($image->getRealPath(),"rb");
        $plat->setResto($resto["0"])
            ->setNomPlat($nomPlat)
            ->setDescription($description)
            ->setPrix($prix)
            ->setImage($image)
            ->setUser($userConect)
            ->setMenu($m);
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
    public function index(PlatRepository $platRepository, SerializerInterface $serializer): Response
    {
        $data = $platRepository->findAll();
        $dataTable = [];
        foreach ($data as $entity) {
           // $images[$key] = base64_encode(stream_get_contents($entity->getImage()));
            $entity->setImage((base64_encode(stream_get_contents($entity->getImage()))));
        }
        
        $dataTable = $serializer->serialize($data, 'json');
        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);

    }
}
