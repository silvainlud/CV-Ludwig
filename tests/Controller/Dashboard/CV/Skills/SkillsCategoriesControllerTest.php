<?php

namespace App\Tests\Controller\Dashboard\CV\Skills;

use App\Entity\Main\CV\CompetenceCategorie;
use App\Tests\Controller\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class SkillsCategoriesControllerTest extends WebTestCase
{
    public function testSecurity()
    {
        $this->login('user');
        $this->client->request(Request::METHOD_GET, '/dashboard/cv/skills');
        self::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testView()
    {
        $this->login('admin');
        $this->client->request(Request::METHOD_GET, '/dashboard/cv/skills');
        self::assertResponseIsSuccessful();
    }

    public function testAddCategorieSubmit()
    {
        $this->login('admin');
        $crawler = $this->client->request(Request::METHOD_GET, '/dashboard/cv/skills/category/add');
        self::assertResponseIsSuccessful();
        $form = $crawler->filter('#form_competencecategorie_submit')->form([
            'form_competencecategorie[name]' => 'Ceci est un nom',
            'form_competencecategorie[ordre]' => 102,
        ]);
        $crawler = $this->client->submit($form);
        self::assertResponseRedirects('/dashboard/cv/skills');

        $cate = $this->em->getRepository(CompetenceCategorie::class)->findOneBy([]);
        self::assertNotNull($cate);
        self::assertEquals('Ceci est un nom', $cate->getName());
        self::assertEquals(102, $cate->getOrdre());

        $crawler = $this->client->followRedirect();
        self::assertCount(1, $crawler->filter('#categories_list tbody tr'));
    }

    public function testAddCategorieError()
    {
        $this->login('admin');
        $crawler = $this->client->request(Request::METHOD_GET, '/dashboard/cv/skills/category/add');
        self::assertResponseIsSuccessful();
        $form = $crawler->filter('#form_competencecategorie_submit')->form([
        ]);
        $this->client->submit($form);
        self::assertResponseIsSuccessful();
        self::assertSelectorExists('.form-errors > li');

        $cate = $this->em->getRepository(CompetenceCategorie::class)->findOneBy([]);
        self::assertNull($cate);
    }
}
