<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Type\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws TransportExceptionInterface
     */
    #[Route('/contact')]
    public function index(Request $request,EntityManagerInterface $em,MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contactMessage = $form->getData();

            $em->persist($contactMessage);
            $em->flush();

            $email = (new Email())
                ->from('contact@example.com')
                ->to('mattgroult10@gmail.com')
                ->subject('Nouveau message de contact')
                ->text("Vous avez reçu un message de {$contactMessage->getName()} : {$contactMessage->getMessage()}");

            $mailer->send($email);

            $this->addFlash('success', 'La recette a bien été créée');
            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
