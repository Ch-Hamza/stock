<?php

namespace SalesBundle\Controller;

use ProductBundle\Entity\Product;
use SalesBundle\Entity\Sale;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
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
                $em->flush();
            }
            return $this->redirectToRoute('index_page');
        }
        return $this->redirectToRoute('index_page');
    }
}
