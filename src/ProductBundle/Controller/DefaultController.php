<?php

namespace ProductBundle\Controller;

use ProductBundle\Entity\Category;
use ProductBundle\Form\ProductType;
use ProductBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index_products_page")
     */
    public function indexAction()
    {
        $product_list = $this->getDoctrine()->getManager()->getRepository(Product::class)->findBy(array('enabled' => true));
        $categories_list = $this->getDoctrine()->getManager()->getRepository(Category::class)->findBy(array('enabled' => true));
        $prod_number = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLacknum();
        return $this->render('@Product/list.html.twig', array(
            'product_list' => $product_list,
            'category_list' => $categories_list,
            'prod_number' => $prod_number,
        ));
    }

    /**
     * @Route("/add", name="add_product_page")
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->get('form.factory')->create(ProductType::class, $product);
        $prod_number = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLacknum();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $product->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('index_products_page');
        }

        return $this->render('@Product/add.html.twig', array(
            'form' => $form->createView(),
            'prod_number' => $prod_number,
        ));
    }

    /**
     * @Route("/edit/{id}", name="edit_product_page")
     */
    public function editAction(Request $request, $id)
    {
        $product = $this->getDoctrine()->getManager()->getRepository(Product::class)->find($id);
        $form = $this->get('form.factory')->create(ProductType::class, $product);
        $prod_number = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLacknum();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('index_products_page');
        }

        return $this->render('@Product/edit.html.twig', array(
            'form' => $form->createView(),
            'prod_number' => $prod_number,
        ));
    }

    /**
     * @Route("/delete/{id}", name="delete_product_page")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        $product->setEnabled(false);
        $em->flush();
        return $this->redirectToRoute('index_products_page');
    }

    /**
     * @Route("/missing", name="missing_product_page")
     */
    public function indexMissingAction()
    {
        $product_list = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLack();
        $prod_number = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLacknum();
        return $this->render('@Product/list_missing.html.twig', array(
            'product_list' => $product_list,
            'prod_number' => $prod_number,
        ));
    }
}
