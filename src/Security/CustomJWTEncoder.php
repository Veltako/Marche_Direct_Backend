<?php

namespace App\Security;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class CustomJWTEncoder
{
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $user = $event->getUser();

        if (!$user instanceof User) {
            return;
        }
        // Récupérez les données actuelles du token
        $data = $event->getData();

        // Ajoutez les informations supplémentaires au token
        $data['id'] = $user->getId();
        $data['roles'] = $user->getRoles();

        $event->setData($data);
    }
}
