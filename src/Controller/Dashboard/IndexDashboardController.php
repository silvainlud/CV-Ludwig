<?php

namespace App\Controller\Dashboard;

use App\Entity\Mail\Domain;
use App\Entity\Mail\Mailbox;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexDashboardController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var EntityManager
     */
    private $emMail;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->em = $doctrine->getManager();
        $this->emMail = $doctrine->getManager('mail');
    }

    /**
     * @Route("/dashboard/", name="db_index")
     */
    public function Index(Request $request): Response
    {
        /** @var Domain[] $domains */
        $domains = $this->emMail->getRepository(Domain::class)->findBy(['active' => true], ['domain' => 'ASC']);
        /** @var Mailbox[] $mailboxes */
        $mailboxes = $this->emMail->getRepository(Mailbox::class)->findBy(['active' => true], ['username' => 'ASC']);

        return $this->render('dashboard/Mail/index.html.twig', [
            'domains' => $domains,
            'mailboxes' => $mailboxes,
        ]);
    }
}
