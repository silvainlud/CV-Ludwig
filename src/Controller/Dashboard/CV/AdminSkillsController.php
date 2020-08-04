<?php

namespace App\Controller\Dashboard\CV;

use App\Entity\Main\CV\Competence;
use App\Entity\Main\CV\CompetenceCategorie;
use App\Entity\Main\CV\Technologie;
use App\Form\CV\CompetenceCategorieType;
use App\Form\CV\CompetenceType;
use App\Form\CV\TechnologieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminSkillsController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @Route("/dashboard/cv/skills", name="db_cv_skills")
     * @Route("/dashboard/cv/skills/categories/{cat}", name="db_cv_skills_categories_view")
     */
    public function ViewCategories(Request $request, CompetenceCategorie $cat = null): Response
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
            $formSkill = $this->createForm(CompetenceType::class, $nSkill, ['choose_categories' => false]);
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

    /**
     * @Route("/dashboard/cv/skills/{skill}/edit", name="db_cv_skills_edit")
     */
    public function EditCompetences(Request $request, Competence $skill): Response
    {
        $form = $this->createForm(CompetenceType::class, $skill, ['cancel_btn' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('db_cv_skills_categories_view', ['cat' => $skill->getCategorie()->getId()]);
        }

        return $this->render('dashboard/cv/skills/edit_skill.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/cv/skills/technlogies", name="db_cv_skills_technologies")
     */
    public function ViewTechnologies(Request $request)
    {
        $_t = new Technologie();
        $form = $this->createForm(TechnologieType::class, $_t, ['cancel_btn' => false]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->em->persist($_t);

                $this->em->flush();

                return $this->redirectToRoute('db_cv_skills_technologies');
            }
            if (null == $form->get('upload')->getData() && null == $_t->getImage()) {
                $form->get('upload')->addError(new FormError($this->translator->trans((new NotNull())->message, [], 'validators')));
            }
        }
        $_ts = $this->em->getRepository(Technologie::class)->findAll();

        return $this->render('dashboard/cv/skills/viewTechnologies.html.twig', [
            'technologies' => $_ts,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/dashboard/cv/skills/technlogies/{tech}/edit", name="db_cv_skills_technologies_edit")
     */
    public function EditTechnology(Request $request, Technologie $tech): Response
    {
        $form = $this->createForm(TechnologieType::class, $tech, ['cancel_btn' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $image = $form->get('upload')->getData();

                if (null != $image) {
                    $tech->setUpload($image);
                }

                $this->em->flush();

                return $this->redirectToRoute('db_cv_skills_technologies');
            }
            if (null == $form->get('upload')->getData() && null == $_t->getImage()) {
                $form->get('upload')->addError(new FormError($this->translator->trans((new NotNull())->message, [], 'validators')));
            }
        }

        return $this->render('dashboard/cv/skills/edit_technlology.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
