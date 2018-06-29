<?php
/**
 * Created by PhpStorm.
 * User: lotfidev
 * Date: 12/06/18
 * Time: 23:21
 */

namespace AppBundle\Service;


class MailSenderService
{

    private $mailer;
    private $from;
    private $to;

    public function __construct(\Swift_Mailer $mailer, $from, $to)
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->to = $to;
    }

    public function send($object, $coontent)
    {

        $message = (new \Swift_Message($object))
            ->setFrom($this->from)
            ->setTo($this->to)
            ->setBody($coontent)
        ;

        $this->mailer->send($message);
    }

}