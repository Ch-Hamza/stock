<?php

namespace ProductBundle\Controller;

use ProductBundle\Form\CategoryType;
use ProductBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="index_categories_page")
     */
    public function indexAction()
    {
        $categories_list = $this->getDoctrine()->getManager()->getRepository(Category::class)->findBy(array('enabled' => true));
        dump($categories_list);
        return $this->render('@Product/Category/list.html.twig', array(
            'categories_list' => $categories_list,
        ));
    }

    /**
     * @Route("/categories/add", name="add_category_page")
     */
    public function addAction(Request $request)
    {
        $category = new Category();
        $form = $this->get('form.factory')->create(CategoryType::class, $category);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('index_categories_page');
        }

        return $this->render('@Product/Category/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/categories/edit/{id}", name="edit_category_page")
     */
    public function editAction(Request $request, $id)
    {
        $category = $this->getDoctrine()->getManager()->getRepository(Category::class)->find($id);
        $form = $this->get('form.factory')->create(CategoryType::class, $category);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('index_categories_page');
        }

        return $this->render('@Product/Category/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/categories/delete/{id}", name="delete_product_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);
        $category->setEnabled(false);
        $em->flush();
        return $this->redirectToRoute('index_categories_page');
    }
}
