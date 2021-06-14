<?php

namespace App\EventSubscriber;

use App\Repository\MenuRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MenuEventSubscriber implements EventSubscriberInterface
{
    private $menuRepository;
    public function __construct(MenuRepository $menuRepository)
    {
        $this->menuRepository = $menuRepository;
    }
    public function onControllerEvent(ControllerEvent $event)
    {
        //dd($event->getRequest()->get('_controller'));
        // if ( $event->getRequest()->get('_controller')) {
        //     dd($event->getRequest()->get('_route'));
        // }
        if ($event->getRequest()->getMethod() === "GET" &&
            $event->getRequest()->get('_route') !== "etat_commande"
        ) {
            $data = $this->menuRepository->findAll();
            foreach ($data as $value) {
                $value->setImage((base64_encode(stream_get_contents($value->getImage()))));
            }
            
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
