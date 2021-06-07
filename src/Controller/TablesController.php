<?php

namespace App\Controller;

use App\Entity\Tables;
use App\Repository\RestoRepository;
use App\Repository\TablesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TablesController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        
    }
    /**
     * @Route("/api/list/tables", name="list_tables_user_resto_connected")
     */
    public function list_tables_user_resto_connected(TablesRepository $tablesRepository ,SerializerInterface $serializer, RestoRepository $restoRepository)
    {
        
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $resto = $restoRepository->findBy(["user" => $userConnecte]);
        $tables = $tablesRepository->findBy(["resto" => $resto["0"]]);
        $dataTable = $serializer->serialize($tables, 'json');
        // dd($tables);

        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
    /**
     * @Route("/api/list/tables/{id}", name="list_tables_user_resto_id")
     */
    public function list_tables_user_resto_id($id, TablesRepository $tablesRepository ,SerializerInterface $serializer, RestoRepository $restoRepository)
    {
        
        // $userConnecte = $this->tokenStorage->getToken()->getUser();
        // $resto = $restoRepository->findBy(["user" => $userConnecte]);
        $tables = $tablesRepository->findTablesByResto($id);
        $dataTable = $serializer->serialize($tables, 'json');
        // dd($tables);

        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * @Route("/api/add/tables", name="add_tables")
     */
    public function add( Request $request, EntityManagerInterface $manager, RestoRepository $restoRepository)
    {
        $values = json_decode($request->getContent());
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $resto = $restoRepository->findBy(["user" => $userConnecte]);
        $tables = new Tables;
        $tables->setNbPersonne($values->nbPersonne)
                ->setNumero($values->numero)
                ->setUser($userConnecte)
                ->setResto($resto["0"]);
        $manager->persist($tables);
        $manager->flush();
        $data = [
            'status' => 201,
            'message' => 'Votre table a été bien enrégistré. '];

        return new JsonResponse($data, 201);

    }
}
