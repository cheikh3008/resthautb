<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\RestoRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommandeController extends AbstractController
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        
    }
    /**
     * @Route("/api/commande", name="add_commande")
     */
    public function addCommande(Request $request, PlatRepository $platRepository, EntityManagerInterface $manager): Response
    {
        $values = json_decode($request->getContent());
        $cmdId = $this->getLastId() + 1;
        $commande = new Commande();
        $user = $this->tokenStorage->getToken()->getUser();
        $plats = $platRepository->findBy(["id" => $values->plat]);
        $numCmd = \str_pad("C",5, "0").$cmdId;
        $commande->setNumCommande($numCmd)
                ->setUser($user);
        foreach ($plats as  $value) {
            $commande->addPlat($value);
        }
        $manager->persist($commande);

        $manager->flush();
        $data = [
            'status' => 201,
            'message' => 'Votre réservation a été bien enrégistré. '];

        return new JsonResponse($data, 201);
    }

    /**
     * @Route("/api/commande/list", name="list_commande")
     */
    public function listCommande(RestoRepository $restoRepository, MenuRepository $menuRepository ,PlatRepository $platRepository, SerializerInterface $serializer)
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        // $resto = $restoRepository->findBy(["user" => $userConnecte]);
        // $menus = $menuRepository->findBy(["resto" => $resto["0"]]);
        $data = $platRepository->findBy(["user" => $userConnecte]);
        $new_array = [];
        foreach($data as $key => $value) {
            $new_array[] = $value->getCommande();
        }
        $dataTable = $serializer->serialize($new_array['0'], 'json');

        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);
        //$new_array = [];
        // foreach($menus as $key => $value) {
        //     $new_array[] = $value->getPlat();
        // }
        // if($menus){
            
        //     $dataTable = $serializer->serialize($new_array["0"], 'json');

        //     return new Response($dataTable, 200, [
        //         'Content-Type' => 'application/json'
        //     ]);
        // } else {
        //     $data = [

        //         'status' => 204,
        //         'message' => 'Pas de reservation. '
        //     ];
        //     return new JsonResponse($data, 204);
        // }
    }


    public function getLastId()
    {
        $repository = $this->getDoctrine()->getRepository(Commande::class);
        // look for a single Product by name
        $res = $repository->findBy(array(), array('id' => 'DESC')) ;
        if($res == null){
            return 0;
        }else{
            return $res[0]->getId();
        }
        
    }

    /**
     * @Route("/api/commande/etat/{id}", name="etat_commande")
     */
    public function status($id, CommandeRepository $commande, EntityManagerInterface $manager)
    {
       
        $commande = $commande->find($id);
        $status = '';
        if ($commande->getIsValid() === false)
        {
            $status = 'validé';
            $commande->setIsValid(true);
            $manager->persist($commande);
            $manager->flush();
            $data=[
                'status'=>200,
                'message'=> 'La commande n°'.$commande->getNumCommande().' a été '. $status
            ];
            return $this->json($data, 200);
        }
        else
        {
            $status = 'en cours ...';
            $commande->setIsValid(false);
            $manager->persist($commande);
            $manager->flush();
            $data=[
                'status'=>200,
                'message'=> 'La commande n° '.$commande->getNumCommande().' est '. $status
            ];
            return $this->json($data, 200);
        }
        
    }
}
