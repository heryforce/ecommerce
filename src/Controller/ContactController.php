<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactController extends AbstractController
{
    /**
     * @Route("/{_locale}/contact", name="contact")
     */
    public function index(Request $request, EntityManagerInterface $manager, ContactNotification $notification, TranslatorInterface $t): Response
    {
        $contact = new Contact;
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contact->setCreatedAt(new \DateTime());
            $notification->notify($contact);

            $text = $t->trans('Votre email a bien été envoyé !');

            $this->addFlash('success', $text);
            $manager->persist($contact);
            $manager->flush();
        }
        return $this->render("contact/index.html.twig", [
            'formContact' => $form->createView()
        ]);
    }

    // j'ai crée un controller juste pour la page de contact pour bien diviser les tâches

}
