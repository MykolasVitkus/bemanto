<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategoryCreateType;
use App\Entity\Category;

class CategoryEditController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="categories")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $allCategories = $repository->findAll();
        $categories = [];

        foreach($allCategories as $category)
        {
            array_push($categories, $category);
        }

        return $this->render('category_edit/index.html.twig', [
            'pageTitle' => 'Kategorijos',
            'categoriesArray' => $categories
        ]);
    }

    /**
     * @Route("/admin/category_create", name="category_create")
     */
    public function create(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryCreateType::class, [
            'action' => $this->generateUrl('category_create')
        ]);

        $form->handleRequest($request);

        $errorMessage = "Kategorija tokia pavadinimu jau yra sukurta!";
        $errorType = "danger";
        $errorTitle = null;

        if($form->isSubmitted() && $form->isValid())
        {
            $searchCategory = $this->getDoctrine()->getRepository(Category::class)->findOneBy([
                'name' => $form->get('name')->getData()
            ]);

            if(isset($searchCategory))
            {
                $errorTitle = "Klaida!";
            }
            else
            {
                $category->setName($form->get('name')->getData());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($category);
                $entityManager->flush();

                return $this->redirectToRoute('categories');
            }
        }

        return $this->render('category_edit/create.html.twig', [
            'pageTitle' => 'Kategorijos kūrimas',
            'errorTitle' => $errorTitle,
            'errorMessage' => $errorMessage,
            'errorType' => $errorType,
            'create_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category_edit/{id}", name="category_edit")
     */
    public function edit(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findOneBy([
            'id' => $id
        ]);

        $form = $this->createForm(CategoryCreateType::class, $category, [
            'action' => $this->generateUrl('category_edit', [ 'id' => $id ])
        ]);

        $form->handleRequest($request);

        $errorMessage = "Kategorija tokia pavadinimu jau yra sukurta!";
        $errorType = "danger";
        $errorTitle = null;

        if($form->isSubmitted() && $form->isValid())
        {
            $searchCategory = $this->getDoctrine()->getRepository(Category::class)->findOneBy([
                'name' => $form->get('name')->getData()
            ]);

            if(isset($searchCategory))
            {
                $errorTitle = "Klaida!";
            }
            else
            {
                $category->setName($form->get('name')->getData());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('categories');
            }
        }


        return $this->render('category_edit/edit.html.twig', [
            'pageTitle' => 'Kategorijos redagavimas',
            'errorTitle' => $errorTitle,
            'errorMessage' => $errorMessage,
            'errorType' => $errorType,
            'edit_form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category_delete/{id}", name="category_delete")
     */
    public function delete($id)
    {
        return $this->render('category_edit/index.html.twig', [
            'pageTitle' => 'Kategorijos šalinimas',
        ]);
    }
}
