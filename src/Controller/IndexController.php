<?php

namespace App\Controller;

use App\Form\ContactMeType;
use App\Services\Contact\ContactMeFactory;
use App\Utils\Helpers\Contact\ContactMe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function Index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/legal-notice", name="legal_notice")
     */
    public function LegalNotice(): Response
    {
        return $this->render('index/legal_notice.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function ContactMe(Request $request, ContactMeFactory $contactMeFactory, TranslatorInterface $translator): Response
    {
        $contactMe = new ContactMe();
        $form = $this->createForm(ContactMeType::class, $contactMe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($contactMeFactory->canSendMessage()) {
                $contactMeFactory->sendMessage($contactMe);

                return $this->render('index/contact_success.html.twig');
            }
            $form->addError(new FormError($translator->trans('contact.error.need-to-wait')));
        }

        return $this->render('index/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
