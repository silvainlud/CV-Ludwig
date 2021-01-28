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
    private EntityManagerInterface $em;

    private TranslatorInterface $translator;

    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * @Route("/dashboard/cv/skills", name="db_cv_skills")
     */
    public function Categories(Request $request): Response
    {
        $categories = $this->em->getRepository(CompetenceCategorie::class)->findBy([], ['ordre' => 'asc']);

        return $this->render('dashboard/cv/skills/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/dashboard/cv/skills/categories/{cat}", name="db_cv_skills_categories_view")
     */
    public function ViewCategory(Request $request, CompetenceCategorie $cat): Response
    {
        $skills = $this->em->getRepository(Competence::class)->findBy(['categorie' => $cat->getId()]);

        return $this->render('dashboard/cv/skills/viewCategory.html.twig', [
            'skills' => $skills,
            'selectCategory' => $cat,
        ]);
    }

    /**
     * @Route("/dashboard/cv/skills/category/{cat}/edit", name="db_cv_skills_categories_edit")
     * @Route("/dashboard/cv/skills/category/add", name="db_cv_skills_categories_add")
     */
    public function EditCategories(Request $request, CompetenceCategorie $cat = null): Response
    {
        if (null == $cat) {
            $cat = new CompetenceCategorie();
        }
        $form = $this->createForm(CompetenceCategorieType::class, $cat, ['cancel_btn' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null == $cat->getId()) {
                $this->em->persist($cat);
            }
            $this->em->flush();

            return $this->redirectToRoute('db_cv_skills');
        }

        return $this->render('dashboard/cv/skills/edit_categories.html.twig', [
            'form' => $form->createView(),
            'add' => null == $cat->getId(),
        ]);
    }

    /**
     * @Route("/dashboard/cv/skills/edit/{skill}", name="db_cv_skills_edit")
     * @Route("/dashboard/cv/skills/add/{category}", name="db_cv_skills_add_categ")
     * @Route("/dashboard/cv/skills/add", name="db_cv_skills_add")
     */
    public function ModifyCompetences(Request $request, Competence $skill = null, CompetenceCategorie $category = null): Response
    {
        if (null == $skill) {
            $skill = new Competence();
            if (null !== $category) {
                $skill->setCategorie($category);
            }
        }

        $form = $this->createForm(CompetenceType::class, $skill, ['cancel_btn' => true, 'choose_categories' => null == $category]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null == $skill->getId()) {
                $this->em->persist($skill);
            }
            $this->em->flush();

            return $this->redirectToRoute('db_cv_skills_categories_view', ['cat' => $skill->getCategorie()->getId()]);
        }

        return $this->render('dashboard/cv/skills/form_skill.html.twig', [
            'form' => $form->createView(),
            'add' => null == $skill->getId(),
        ]);
    }

    /**
     * @Route("/dashboard/cv/skills/technlogies", name="db_cv_skills_technologies")
     *
     * @return Response
     */
    public function ViewTechnologies()
    {
        $_ts = $this->em->getRepository(Technologie::class)->findAll();

        return $this->render('dashboard/cv/skills/viewTechnologies.html.twig', [
            'technologies' => $_ts,
        ]);
    }

    /**
     * @Route("/dashboard/cv/skills/technlogy/{tech}/edit", name="db_cv_skills_technologies_edit")
     * @Route("/dashboard/cv/skills/technlogy/add", name="db_cv_skills_technologies_add")
     */
    public function EditTechnology(Request $request, Technologie $tech = null): Response
    {
        if (null == $tech) {
            $tech = new Technologie();
        }
        $form = $this->createForm(TechnologieType::class, $tech, ['cancel_btn' => true]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $image = $form->get('upload')->getData();

                if (null != $image) {
                    $tech->setUpload($image);
                }

                if (null == $tech->getId()) {
                    $this->em->persist($tech);
                }
                $this->em->flush();

                return $this->redirectToRoute('db_cv_skills_technologies');
            }
            if (null == $form->get('upload')->getData() && null == $tech->getImage()) {
                $form->get('upload')->addError(new FormError($this->translator->trans((new NotNull())->message, [], 'validators')));
            }
        }

        return $this->render('dashboard/cv/skills/form_technlology.html.twig', [
            'form' => $form->createView(),
            'add' => null == $tech->getId(),
        ]);
    }
}
