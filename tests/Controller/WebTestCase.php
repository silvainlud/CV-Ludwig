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

        $temp = $this->loadFixtureFiles($this->fixtures);
        foreach ($temp as $k => $t) {
            $pk = '';
            $chars = array_reverse(str_split($k));
            foreach ($chars as $c) {
                if (is_numeric($c)) {
                    $pk = $c . $pk;
                } else {
                    break;
                }
            }
            $k = str_replace($pk, '', $k);
            if (!\array_key_exists($k, $this->data)) {
                $this->data[$k] = [];
            }
            $this->data[$k][] = $t;
        }

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
        $managedUser = $em->getRepository(Utilisateur::class)->find($this->data[$user][0]->getId());
        if (null === $managedUser) {
            throw new Exception("Impossible de retrouver l'utilisateur {$user}");
        }

        $this->client->loginUser($managedUser);
        $this->user = $managedUser;
    }
}
