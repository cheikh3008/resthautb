<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\PlatCommande;
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
        $user = $this->tokenStorage->getToken()->getUser();
        $cmd = new Commande();
        $cmdId = $this->getLastId() + 1;
        $numCmd = \str_pad("C",5, "0").$cmdId;
        $cmd->setNumCommande($numCmd)
            ->setUser($user);
        $manager->persist($cmd);
        $plats = array_count_values($values->plat);
        $res = [];
        foreach ($plats as $key =>  $value) {
            $res [] = [
                "plat" => $platRepository->find($key),
                "quantite" => $value
            ];
            $PlatCommande = new PlatCommande();
            $PlatCommande->setCommande($cmd);
            foreach ($res as $key => $value) {

                $PlatCommande->setQuantite($value['quantite']);
                $PlatCommande->setPlat($value['plat']);
            }
            $manager->persist($PlatCommande);
        }
        $manager->flush();
        $data = [

            'status' => 201,
            'message' => 'Votre commande a été enrégistré. '
        ];

        return new JsonResponse($data, 201);
    }

    /**
     * @Route("/api/commande/list", name="list_commande")
     */
    public function listCommande(CommandeRepository $commandeRepository ,RestoRepository $restoRepository, MenuRepository $menuRepository ,PlatRepository $platRepository, SerializerInterface $serializer)
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $resto = $restoRepository->findOneBy(["user" => $userConnecte]);
        //dd($resto);
        $data = $commandeRepository->findAll();
        $dataCommandes = [];
        foreach ($data as $key => $commandes) {
            foreach ($commandes->getPlatCommandes() as $value) {
                if($value->getPlat()->getUser()->getId() === $resto->getUser()->getId()) {
                    $dataCommandes [] = $commandes;
                }
            }

        }
        $resultats = [];
        foreach (array_unique($dataCommandes, SORT_REGULAR) as  $value) {
            $resultats [] = $value;
        }
        return $this->json($resultats, 200);
        
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
    /**
     * @Route("/api/list/commande/client", name="list_commande_client")
     */
    public function list_reserv_client(SerializerInterface $serializer, CommandeRepository $commandeRepository)
    {
        $userConnecte = $this->tokenStorage->getToken()->getUser();
        $data = $commandeRepository->findBy(["user" => $userConnecte]);
         
        $dataTable = $serializer->serialize($data, 'json');

        return new Response($dataTable, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}

        // $plats = $platRepository->findBy(["id" => $values->plat]);
        // $numCmd = \str_pad("C",5, "0").$cmdId;