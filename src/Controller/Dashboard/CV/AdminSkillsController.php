<?php

namespace App\Controller\Dashboard\CV;

use App\Entity\Main\CV\Competence;
use App\Entity\Main\CV\CompetenceCategorie;
use App\Form\CV\CompetenceCategorieType;
use App\Form\CV\CompetenceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminSkillsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/dashboard/cv/skills", name="db_cv_skills")
     * @Route("/dashboard/cv/skills/categories/{cat}", name="db_cv_skills_categories_view")
     */
    public function View(Request $request, CompetenceCategorie $cat = null): Response
    {
        $newCat = new CompetenceCategorie();
        $formCat = $this->createForm(CompetenceCategorieType::class, $newCat);
        $formSkill = $this->createFormBuilder()->getForm();
        $skills = [];

        $formCat->handleRequest($request);
        if ($formCat->isSubmitted() && $formCat->isValid()) {
            $this->em->persist($newCat);
            $this->em->flush();

            return $this->redirectToRoute('db_cv_skills');
        }

        if (null != $cat) {
            $skills = $this->em->getRepository(Competence::class)->findBy(['categorie' => $cat->getId()]);
            $nSkill = new Competence();
            $nSkill->setCategorie($cat);
            $formSkill = $this->createForm(CompetenceType::class, $nSkill);
            $formSkill->handleRequest($request);
            if ($formSkill->isSubmitted() && $formSkill->isValid()) {
                $this->em->persist($nSkill);
                $this->em->flush();

                return $this->redirectToRoute('db_cv_skills_categories_view', ['cat' => $cat->getId()]);
            }
        }

        $categories = $this->em->getRepository(CompetenceCategorie::class)->findBy([], ['ordre' => 'asc']);

        return $this->render('dashboard/cv/skills/index.html.twig', [
            'categories' => $categories,
            'formCat' => $formCat->createView(),
            'skills' => $skills,
            'formSkill' => $formSkill->createView(),
            'selectCategory' => $cat,
        ]);
    }

    /**
     * @Route("/dashboard/cv/skills/categories/{cat}/edit", name="db_cv_skills_categories_edit")
     */
    public function EditCategories(Request $request, CompetenceCategorie $cat): Response
    {
        $form = $this->createForm(CompetenceCategorieType::class, $cat, ['cancel_btn' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('db_cv_skills');
        }

        return $this->render('dashboard/cv/skills/edit_categories.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
