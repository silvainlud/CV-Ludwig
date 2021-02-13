<?php

namespace App\Services\Contact;

use App\Entity\Main\ContactLog;
use App\Utils\Helpers\Contact\ContactMe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;

class ContactMeFactory implements ContactMeFactoryInterface
{
    public const timeBetweenTry = '1  days';
    public const emailTo = 'contact@silvain.eu';

    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, MailerInterface $mailer)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function canSendMessage(): bool
    {
        /** @var ?ContactLog $log */
        $log = $this->em->getRepository(ContactLog::class)->findLastByIp((string) $this->requestStack->getCurrentRequest()->getClientIp());

        return null === $log || $log->getDateCreated() < (new \DateTime())->sub(date_interval_create_from_date_string(self::timeBetweenTry));
    }

    public function sendMessage(ContactMe $contactMe): void
    {
        $log = new ContactLog((string) $this->requestStack->getCurrentRequest()->getClientIp());
        $this->em->persist($log);
        $this->em->flush();
        $this->mailer->send($contactMe->makeEmail(self::emailTo));
    }
}
