<?php

namespace App\EventSubscriber;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{

    /**
     * @var Security
     */
    private $security;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,Security $security )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            // BeforeEntityPersistedEvent::class => ['addArticle'],
            // BeforeEntityUpdatedEvent::class => ['updateArticle'], //surtout utile lors d'un reset de mot passe plutôt qu'un réel update, car l'update va de nouveau encrypter le mot de passe DEJA encrypté ...
        ];
    }

    // public function addArticle(BeforeEntityPersistedEvent $event)
    // {
    //     $entity = $event->getEntityInstance();
    //     if (!($entity instanceof Article)) {
    //         return;
    //     }
    //       $entity->setUser($this->security->getUser());
    //       $this->entityManager->persist($entity);
    //       $this->entityManager->flush();
    // }
}