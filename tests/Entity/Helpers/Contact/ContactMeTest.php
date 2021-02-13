<?php

namespace App\Tests\Entity\Helpers\Contact;

use App\Tests\Entity\EntityKernelTestCase;
use App\Utils\Helpers\Contact\ContactMe;

/**
 * @internal
 * @coversNothing
 */
class ContactMeTest extends EntityKernelTestCase
{
    public function testGetter()
    {
        self::assertEquals('Nom Prénom', self::GetEntity()->getName());
        self::assertEquals('contact@silvain.eu', self::GetEntity()->getEmail());
        self::assertEquals('Ceci est un message', self::GetEntity()->getMessage());
        $this->assertsHasErrors(self::GetEntity(), 0);
    }

    public static function GetEntity(): ContactMe
    {
        return (new ContactMe())
            ->setName('Nom Prénom')
            ->setEmail('contact@silvain.eu')
            ->setMessage('Ceci est un message');
    }

    public function testName()
    {
        $this->assertsHasErrors(self::GetEntity()->setName(''), 1);
        $this->assertsHasErrors(self::GetEntity()->setName('azert12345azert12345azert1234512'), 0);
        $this->assertsHasErrors(self::GetEntity()->setName('azert12345azert12345azert1234512+'), 1);
    }

    public function testEmail()
    {
        $this->assertsHasErrors(self::GetEntity()->setEmail(''), 1);
        $this->assertsHasErrors(self::GetEntity()->setEmail('ludwig@silvain.eu'), 0);
        $this->assertsHasErrors(self::GetEntity()->setEmail('azert12345azert12345azert1234512'), 1);
        $this->assertsHasErrors(self::GetEntity()->setEmail('azert12345azert12345azert1234512+'), 2);
    }

    public function testMessage()
    {
        $s = '';
        for ($i = 0; $i < 1000; ++$i) {
            $s .= 's';
        }

        $this->assertsHasErrors(self::GetEntity()->setMessage(''), 2);
        $this->assertsHasErrors(self::GetEntity()->setMessage('a'), 1);
        $this->assertsHasErrors(self::GetEntity()->setMessage('azert12345az12+'), 0);
        $this->assertsHasErrors(self::GetEntity()->setMessage($s), 0);
        $this->assertsHasErrors(self::GetEntity()->setMessage($s . '+'), 1);
    }

    public function testMakeEmail()
    {
        $d = self::GetEntity();
        $m = $d->makeEmail('send@silvain.eu');
        self::assertCount(1, $m->getFrom());
        self::assertCount(1, $m->getTo());
        self::assertEquals('contact@silvain.eu', $m->getFrom()[0]->getAddress());
        self::assertEquals('Nom Prénom', $m->getFrom()[0]->getName());
        self::assertEquals('send@silvain.eu', $m->getTo()[0]->getAddress());
        self::assertEquals('Un email de contact (silvain.eu) de Nom Prénom', $m->getSubject());
        self::assertEquals('Ceci est un message', $m->getTextBody());
    }
}
