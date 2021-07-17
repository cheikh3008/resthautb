<?php

namespace App\EventListener;

use App\Repository\UserRepository;
use App\Repository\RestoRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    private $restoRepository;
    private $userRepository;
    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack, RestoRepository $restoRepository, UserRepository $userRepository)
    {
        $this->requestStack = $requestStack;
        $this->restoRepository = $restoRepository;
        $this->userRepository = $userRepository;
    }
    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        /** @var $user \AppBundle\Entity\User */
        $user = $event->getUser();
        if($user->getRole()->getLibelle() == "ROLE_GERANT"){
            
            $data = $this->restoRepository->findOneBy(["user" => $user]);
            $data->setImage((base64_encode(stream_get_contents($data->getImage()))));
            
            $payload = array_merge(
                $event->getData(),
                    [
                        'idGerant' => $data->getId(),
                        'nomResto' => $data->getNomResto(),
                        'image' => $data->getImage(),
                        'nomComplet' => $user->getNomComplet()
                    ]
            );
            $event->setData($payload);
        }
        if($user->getRole()->getLibelle() == "ROLE_CLIENT"){
            $data = $this->userRepository->find($user->getid());
            $payload = array_merge(
                $event->getData(),
                    [
                        'nomComplet' => $data->getNomComplet(),
                        'telephone' => $data->getTelephone(),
                        'adresse' => $data->getAdresseDomicile(),
                    ]
            );
            $event->setData($payload);
        }
    }   
}