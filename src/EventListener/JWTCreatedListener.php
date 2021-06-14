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
            
            $data = $this->restoRepository->findBy(["user" => $user]);
            $data["0"]->setImage((base64_encode(stream_get_contents($data["0"]->getImage()))));
            
            $payload = array_merge(
                $event->getData(),
                    [
                        'nomResto' => $data["0"]->getNomResto(),
                        'image' => $data["0"]->getImage()
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
                        'adresse' => $data->getAdresse(),
                    ]
            );
            $event->setData($payload);
        }
    }   
}