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
    #[Route(path: '/', name: 'index')]
    public function Index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    #[Route(path: '/legal-notice', name: 'legal_notice')]
    public function LegalNotice(): Response
    {
        return $this->render('index/legal_notice.html.twig');
    }

    #[Route(path: '/contact', name: 'contact')]
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

    #[Route(path: '/mail/config-v1.1.xml', name: '_autodiscover')]
    public function Autodiscover(Request $request): Response
    {
        if (!$request->query->has("emailaddress"))
            throw $this->createNotFoundException();

        $email = $request->query->get("emailaddress");
        $domain = explode("@", $email)[1];
        $mailServeur = "mail.silvain.eu";

        $xml = <<<xml
<clientConfig version="1.1">
    <emailProvider id="$domain">
      <domain>$domain</domain>
      <displayName>$domain</displayName>
      <displayShortName>$domain</displayShortName>
      <incomingServer type="imap">
         <hostname>$mailServeur</hostname>
         <port>993</port>
         <socketType>STARTTLS</socketType>
         <username>%EMAILADDRESS%</username>
         <authentication>password-cleartext</authentication>
      </incomingServer>
      <incomingServer type="pop3">
         <hostname>$mailServeur</hostname>
         <port>995</port>
         <socketType>STARTTLS</socketType>
         <username>%EMAILADDRESS%</username>
         <authentication>password-cleartext</authentication>
      </incomingServer>
      <outgoingServer type="smtp">
         <hostname>$mailServeur</hostname>
         <port>587</port>
         <socketType>STARTTLS</socketType>
         <username>%EMAILADDRESS%</username>
         <authentication>password-cleartext</authentication>
      </outgoingServer>
      <documentation url="https://webmail.silvain.eu">
          <descr lang="fr">Connexion Webmail</descr>
          <descr lang="en">Webmail connexion</descr>
      </documentation>
    </emailProvider>
</clientConfig>
xml;


        return new Response($xml, 200, [
            "Content-Type" => "application/xml"
        ]);

    }
}
