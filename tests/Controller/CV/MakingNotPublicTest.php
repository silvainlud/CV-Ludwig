<?php

namespace App\Tests\Controller\CV;

use App\Entity\Main\CV\Realisation;
use App\Tests\Controller\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class MakingNotPublicTest extends WebTestCase
{
    private int $nbPublic = 0;
    private int $nbPrivate = 0;

    protected function setUp(): void
    {
        $this->fixtures[] = __DIR__ . '/../../Fixtures/Main/CV/Making/MakingFixtures.yaml';
        parent::setUp();
        [$this->nbPublic, $this->nbPrivate] = $this->countPublic();
    }

    public function testList()
    {
        $crawler = $this->client->request(Request::METHOD_GET, '/realisations');
        self::assertResponseIsSuccessful();
        self::assertEquals($this->nbPublic, $crawler->filter('.card.card-media')->count());

        $this->login('admin');
        $crawler = $this->client->request(Request::METHOD_GET, '/realisations');
        self::assertResponseIsSuccessful();
        self::assertEquals($this->nbPublic + $this->nbPrivate, $crawler->filter('.card.card-media')->count());
        self::assertEquals($this->nbPrivate, $crawler->filter('.card.card-media .app-badge')->count());
    }

    public function listMakingIndex(): iterable
    {
        for ($i = 0; $i < 10; ++$i) {
            yield [$i];
        }
    }

    /** @dataProvider listMakingIndex */
    public function testViewMaking(int $index)
    {
        $m = $this->data['making'][$index];

        $this->client->request(Request::METHOD_GET, '/realisation/' . $m->getSlug());
        self::assertResponseStatusCodeSame($m->isPublic() ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);

        $this->login('admin');
        $this->client->request(Request::METHOD_GET, '/realisation/' . $m->getSlug());
        self::assertResponseIsSuccessful();
    }

    private function countPublic()
    {
        $i = 0;
        /** @var Realisation $m */
        foreach ($this->em->getRepository(Realisation::class)->findAll() as $m) {
            if ($m->isPublic()) {
                ++$i;
            }
        }

        return [$i, \count($this->data['making']) - $i];
    }
}
