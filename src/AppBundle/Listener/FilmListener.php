<?php
/**
 * Created by PhpStorm.
 * User: lotfidev
 * Date: 13/06/18
 * Time: 00:08
 */

namespace AppBundle\Listener;


use AppBundle\Entity\Categorie;
use AppBundle\Entity\Film;
use AppBundle\Service\MailSenderService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class FilmListener
{
    private $mailer;

    public function __construct(MailSenderService $mailer)
    {
        $this->mailer = $mailer;
    }

    public function  postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Film) {
            return;
        }

        $this->mailer->send('Création de film', 'Un film avec l\'id '. $entity->getId() .' a été créé');

    }

    public function  preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof Film) {
            return;
        }

        $this->mailer->send('Suppression de film', 'Le film avec l\'id '. $entity->getId() .' a été supprimé');

    }

}