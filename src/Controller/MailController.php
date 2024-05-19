<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class MailController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/mail')]
    public function index(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('contact@matteo-groult.com')
            ->to('mattgroult10@gmail.com')
            ->subject('Test d\'envoi d\'email')
            ->text('Ceci est un email de test.');

        $mailer->send($email);

        return new Response('Email envoyé avec succès.');
    }
}
