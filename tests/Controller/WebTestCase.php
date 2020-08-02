<?php

namespace App\Tests\Controller;

use App\Entity\Main\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * @internal
 * @coversNothing
 */
class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    use FixturesTrait;
    protected KernelBrowser $client;
    protected EntityManagerInterface $em;
    protected array $data = [];
    protected array $fixtures = [__DIR__ . '/../Fixtures/ControllerFixtures.yaml'];
    protected Utilisateur $user;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        /** @var EntityManagerInterface $em */
        $em = self::$container->get(EntityManagerInterface::class);

        $this->data = $this->loadFixtureFiles($this->fixtures);

        $this->em = $em;
        $this->em->getConnection()->getConfiguration()->setSQLLogger(null);
        parent::setUp();
    }

    public function login(string $user)
    {
        if (null === $user && \array_key_exists($user, $this->data)) {
            return;
        }
        // On récupère l'instance dans l'entityManager pour éviter la deAuthenticate dans le ContextListener
        /** @var EntityManagerInterface $em */
        $em = self::$container->get(EntityManagerInterface::class);
        /** @var Utilisateur $managedUser */
        $managedUser = $em->getRepository(Utilisateur::class)->find($this->data[$user]->getId());
        if (null === $managedUser) {
            throw new Exception("Impossible de retrouver l'utilisateur {$user}");
        }

        $this->client->loginUser($managedUser);
        $this->user = $managedUser;
    }
}
