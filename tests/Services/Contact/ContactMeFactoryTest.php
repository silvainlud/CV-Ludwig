<?php

namespace App\Tests\Services\Contact;

use App\Entity\Main\ContactLog;
use App\Repository\Main\ContactLogRepository;
use App\Services\Contact\ContactMeFactory;
use App\Tests\Entity\Helpers\Contact\ContactMeTest;
use App\Utils\Helpers\Contact\ContactMe;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @internal
 * @coversNothing
 */
class ContactMeFactoryTest extends TestCase
{
    private const defaultIp = '8.8.8.8';
    private ContactMeFactory $service;
    private MockObject $em;
    private MockObject $mailer;

    public function testFirstCanSendNotData(): void
    {
        $s = $this->createEventCan(null);
        self::assertTrue($s->canSendMessage());
    }

    public function testFirstCanSendOutDate(): void
    {
        $d = new ContactLogFaker(self::defaultIp, (new \DateTime())->sub(date_interval_create_from_date_string(ContactMeFactory::timeBetweenTry . ' 2 minutes')));
        $s = $this->createEventCan($d);
        self::assertTrue($s->canSendMessage());
    }

    public function testFirstCanSendInDate(): void
    {
        $d = new ContactLogFaker(self::defaultIp, (new \DateTime())->sub(date_interval_create_from_date_string(ContactMeFactory::timeBetweenTry . ' - 1 minutes')));
        $s = $this->createEventCan($d);
        self::assertFalse($s->canSendMessage());
    }

    public function testSendEmail()
    {
        $d = ContactMeTest::GetEntity();
        $s = $this->createEventSend($d);
        $s->sendMessage($d);
    }

    private function createEventCan(?ContactLog $contactLog, string $ipAddress = self::defaultIp): ContactMeFactory
    {
        $s = $this->createEvent($ipAddress);

        $contactLogRepo = $this->getMockBuilder(ContactLogRepository::class)->disableOriginalConstructor()->getMock();
        $contactLogRepo->expects($this->any())->method('findLastByIp')->willReturn($contactLog);
        $this->em->expects($this->once())->method('getRepository')->with(ContactLog::class)->willReturn($contactLogRepo);

        return $s;
    }

    private function createEvent(string $ipAddress = self::defaultIp): ContactMeFactory
    {
        $request = new Request([], [], [], [], [], ['REMOTE_ADDR' => $ipAddress], null);
        $requestStack = new RequestStack();
        $requestStack->push($request);
        $this->em = $this->getMockBuilder(EntityManagerInterface::class)->getMock();
        $this->mailer = $this->getMockBuilder(MailerInterface::class)->getMock();

        $this->service = new ContactMeFactory($requestStack, $this->em, $this->mailer);

        return $this->service;
    }

    private function createEventSend(ContactMe $contactMe, string $ipAddress = self::defaultIp): ContactMeFactory
    {
        $s = $this->createEvent($ipAddress);
        $this->em->expects($this->once())->method('persist');
        $this->em->expects($this->once())->method('flush');
        $this->mailer->expects($this->once())->method('send')->with($contactMe->makeEmail(ContactMeFactory::emailTo));

        return $s;
    }
}
