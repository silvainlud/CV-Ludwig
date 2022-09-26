<?php

namespace App\Services\Contact;

use DateTime;
use App\Entity\Main\ContactLog;
use App\Utils\Helpers\Contact\ContactMe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;

class ContactMeFactory implements ContactMeFactoryInterface
{
    public const timeBetweenTry = '1  weeks';
    public const emailTo = 'contact@silvain.eu';

    private RequestStack $requestStack;

    private EntityManagerInterface $em;

    private MailerInterface $mailer;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        private string $addressFrom
    ) {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function canSendMessage(): bool
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if (null === $currentRequest) {
            return false;
        }
        /** @var ?ContactLog $log */
        $log = $this->em->getRepository(ContactLog::class)->findLastByIp((string) $currentRequest->getClientIp());

        $intervale = date_interval_create_from_date_string(self::timeBetweenTry);

        return null === $log || (false !== $intervale && $log->getDateCreated() < (new DateTime())->sub($intervale));
    }

    public function sendMessage(ContactMe $contactMe): void
    {
        /** @var Request $currentRequest */
        $currentRequest = $this->requestStack->getCurrentRequest();
        $log = new ContactLog((string) $currentRequest->getClientIp());
        $this->em->persist($log);
        $this->em->flush();
        $this->mailer->send($contactMe->makeEmail(self::emailTo, $this->addressFrom));
    }
}
