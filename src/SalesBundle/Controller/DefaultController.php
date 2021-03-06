<?php

namespace SalesBundle\Controller;

use ProductBundle\Entity\Product;
use SalesBundle\Entity\BilanDate;
use SalesBundle\Entity\Sale;
use SalesBundle\Form\BilanDateType;
use SalesBundle\Form\SaleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index_sales_page")
     */
    public function indexAction(Request $request)
    {
        $prod_number = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLacknum();
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository(Sale::class)->createQueryBuilder('s')
            ->leftJoin('s.product', 'p')
            ->orderBy('s.saleDate');
        if($request->query->getAlnum('filter')) {
            $queryBuilder->where('p.name LIKE :name')
                            ->setParameter('name', '%'.$request->query->getAlnum('filter').'%');
        }
        $query = $queryBuilder->getQuery();
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );
        return $this->render('@Sales/list.html.twig', array(
            'sales_list' => $result,
            'prod_number' => $prod_number,
        ));
    }

    /**
     * @Route("/edit/{id}", name="edit_sale_page")
     */
    public function editAction(Request $request, $id)
    {
        $sale = $this->getDoctrine()->getManager()->getRepository(Sale::class)->find($id);
        $form = $this->get('form.factory')->create(SaleType::class, $sale);
        $old_quantity = $sale->getQuantity();
        $prod_number = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLacknum();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $new_quantity = $sale->getQuantity();
            $product = $sale->getProduct();
            if($new_quantity > $old_quantity) {
                $product->setQuantity($product->getQuantity() - ($new_quantity - $old_quantity));
            }
            elseif ($new_quantity < $old_quantity) {
                $product->setQuantity($product->getQuantity() + ($old_quantity - $new_quantity));
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('index_sales_page');
        }

        return $this->render('@Sales/edit.html.twig', array(
            'form' => $form->createView(),
            'prod_number' => $prod_number,
        ));
    }

    /**
     * @Route("/delete/{id}", name="delete_sale_page")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $sale = $em->getRepository(Sale::class)->find($id);
        $em->remove($sale);
        $em->flush();
        return $this->redirectToRoute('index_sales_page');
    }

    /**
     * @Route("/bilan", name="bilan_sale_page")
     */
    public function bilanAction(Request $request)
    {
        $bilan = new BilanDate();
        $form = $this->get('form.factory')->create(BilanDateType::class, $bilan);
        $prod_number = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLacknum();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $em->getRepository(Sale::class)->findDate($bilan);
            return $this->render('@Sales/bilan_list.html.twig', array(
                'data' => $data,
                'bilan' => $bilan,
                'prod_number' => $prod_number,
            ));
        }

        return $this->render('@Sales/bilan.html.twig', array(
            'form' => $form->createView(),
            'prod_number' => $prod_number,
        ));
    }

    /**
     * @Route("/save-sale", name="save_sale_page")
     */
    public function SaveSaleAction(Request $request)
    {
        if($request->isMethod('POST')) {
            $data = $request->request->get('items');
            $array_data = json_decode($data, true);
            $em = $this->getDoctrine()->getManager();
            foreach ($array_data as $item)
            {
                $sale = new Sale();
                $product = $em->getRepository(Product::class)->find($item['item']['id']);
                $sale->setProduct($product);
                $sale->setQuantity($item['sale_quantity']);
                $sale->setSaleDate(new \DateTime('now'));
                $em->persist($sale);
                $product->setQuantity($product->getQuantity() - $item['sale_quantity']);
                $em->flush();
            }
            return $this->redirectToRoute('index_page');
        }
        return $this->redirectToRoute('index_page');
    }
}
