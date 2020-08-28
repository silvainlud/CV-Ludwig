<?php

namespace App\Tests\Controller\Dashboard\CV\Skills;

use App\Entity\Main\CV\Competence;
use App\Entity\Main\CV\CompetenceCategorie;
use App\Entity\Main\CV\CompetenceNiveau;
use App\Entity\Main\CV\Technologie;
use App\Tests\Controller\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class SkillControllerTest extends WebTestCase
{
    protected CompetenceCategorie $_categ;
    protected CompetenceNiveau $_level;
    protected Technologie $_tech;

    protected function setUp(): void
    {
        $this->fixtures[] = __DIR__ . '/../../../../Fixtures/Main/CV/SkillCategories/SkillCategoriesFixtures.yaml';
        $this->fixtures[] = __DIR__ . '/../../../../Fixtures/Main/CV/SkillLevel/SkillLevelFixtures.yaml';
        $this->fixtures[] = __DIR__ . '/../../../../Fixtures/Main/CV/Technology/TechnologyFixtures.yaml';
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->_categ = $this->data['skill_categ'][rand(0, 9)];
        $this->_level = $this->data['skill_level'][rand(0, 9)];
        $this->_tech = $this->data['skill_tech'][rand(0, 9)];
    }

    public function testNotfound()
    {
        $this->login('admin');
        $this->client->request(Request::METHOD_GET, '/dashboard/cv/skills/categories/0');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testFound()
    {
        $this->login('admin');
        $this->client->request(Request::METHOD_GET, '/dashboard/cv/skills/categories/' . $this->_categ->getId());
        self::assertResponseIsSuccessful();
    }

    public function testAddSkills()
    {
        $this->login('admin');
        $crawler = $this->client->request(Request::METHOD_GET, '/dashboard/cv/skills/' . $this->_categ->getId() . '/add');
        $form = $crawler->filter('#competence_submit')->form([
            'competence[niveau]' => $this->_level->getId(),
            'competence[technologie]' => $this->_tech->getId(),
            'competence[scolaire]' => true,
            'competence[autoditacte]' => false,
        ]);
        $this->client->submit($form);
        self::assertResponseRedirects();

        /** @var Competence $_skill */
        $_skill = $this->em->getRepository(Competence::class)->findOneBy([]);
        self::assertNotNull($_skill);
        self::assertEquals($this->_level->getId(), $_skill->getNiveau()->getId());
        self::assertEquals($this->_tech->getId(), $_skill->getTechnologie()->getId());
        self::assertEquals($this->_categ->getId(), $_skill->getCategorie()->getId());
        self::assertTrue($_skill->getScolaire());
        self::assertFalse($_skill->getAutoditacte());
    }

    public function testEditSkill()
    {
        $c = (new Competence())->setCategorie($this->_categ)->setAutoditacte(false)->setScolaire(true)->setNiveau($this->_level)->setTechnologie($this->_tech);
        $this->em->persist($c);
        $this->em->flush();
        $this->login('admin');
        $crawler = $this->client->request(Request::METHOD_GET, '/dashboard/cv/skills/' . $c->getId() . '/edit');
        self::assertResponseIsSuccessful();
        /** @var CompetenceCategorie $_categ */
        $_categ = $this->data['skill_categ'][rand(0, 9)];
        /** @var CompetenceNiveau $_level */
        $_level = $this->data['skill_level'][rand(0, 9)];
        /** @var Technologie $_tech */
        $_tech = $this->data['skill_tech'][rand(0, 9)];
        $form = $crawler->filter('#competence_submit')->form([
            'competence[niveau]' => $_level->getId(),
            'competence[categorie]' => $_categ->getId(),
            'competence[technologie]' => $_tech->getId(),
            'competence[scolaire]' => false,
            'competence[autoditacte]' => true,
        ]);
        $this->client->submit($form);
        self::assertResponseRedirects();

        $c = $this->em->getRepository(Competence::class)->find($c->getId());
        self::assertNotNull($c);
        self::assertEquals($_level->getId(), $c->getNiveau()->getId());
        self::assertEquals($_tech->getId(), $c->getTechnologie()->getId());
        self::assertEquals($_categ->getId(), $c->getCategorie()->getId());
        self::assertFalse($c->getScolaire());
        self::assertTrue($c->getAutoditacte());
    }
}
