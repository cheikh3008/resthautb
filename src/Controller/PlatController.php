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
        $values = json_decode($request->getContent());
        $userConect = $this->tokenStorage->getToken()->getUser();
        $userId = $userConect->getId();
        $resto = $restoRepository->findUserById($userId);
        $menu = $menuRepository->findOneBy(array("id" => $values->menu));
        //dd($values);
        $plat= new Plat();
        
        $plat->setResto($resto["0"])
            ->setNomPlat($values->nomPlat)
            ->setDescription($values->description)
            ->setPrix($values->prix)
            ->setUser($userConect)
            ->setMenu($menu);
        $manager->persist($plat);
        $manager->flush();
        $data = [

            'status' => 201,
            'message' => 'Votre plat a été crée avec succes. '
        ];

        return new JsonResponse($data, 201);
    }
    /**
     * @Route("/api/plat/list", name="list_plat", methods={"GET"})
     */
    public function listerPlatByUserId(PlatRepository $platRepository ,RestoRepository $restoRepository ,SerializerInterface $serializer): Response
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $userId = $restoRepository->findUserById($userConnecte->getId());
        //dd($userId["0"]);
        $data = $platRepository->findByEnvois($userId["0"]->getId());
        dd($platRepository->findByEnvois($userId["0"]->getId()));
        $dataTable = [];
        dd($data);
        foreach ($data as $key => $entity) {
            
            // $images[$key] = base64_encode(stream_get_contents($entity->getImage()));
            $entity[$key] = ((base64_encode(stream_get_contents($entity["image"]))));
            //$entity->setImage()((base64_encode(stream_get_contents($entity->getImage()))));
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
        $data = $platRepository->findBy(array("resto"=> $userId));
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
