<?php

namespace IndexBundle\Controller;

use ProductBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index_page")
     */
    public function indexAction()
    {
        $prod_number = $this->getDoctrine()->getManager()->getRepository(Product::class)->findLacknum();
        return $this->render('@Index/index.html.twig', array(
            'prod_number' => $prod_number,
        ));
    }

    /**
     * @Route("/search-prod", name="search_prod_page")
     */
    public function searchAction(Request $request)
    {
        $str = $request->query->get('q');
        $result = $this->getDoctrine()->getManager()->getRepository(Product::class)->searchFind($str);
        $output = array(
            'data' => array(),
        );
        foreach ($result as $item) {
            $category_image = array();
            $category = array();
            $image = array();

            $category_image[] = array(
                'id' => $item->getCategory()->getImage()->getId(),
                'image' => $item->getCategory()->getImage()->getImage(),
                'updated_at' => $item->getCategory()->getImage()->getUpdatedat(),
            );

            $category[] = array(
                'id' => $item->getCategory()->getId(),
                'name' => $item->getCategory()->getName(),
                'image' => $category_image,
            );

            $image[] = array(
                'id' => $item->getImage()->getId(),
                'image' => $item->getImage()->getImage(),
                'updated_at' => $item->getImage()->getUpdatedat(),
            );

            $output['data'][] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'quantity' => $item->getQuantity(),
                'image' => $image,
                'category' => $category,
            ];
        }
        //dump($output);
        return new JsonResponse($output);
    }

    /**
     * @Route("/search-all", name="search_all_page")
     */
    public function searchAllAction(Request $request)
    {
        $result = $this->getDoctrine()->getManager()->getRepository(Product::class)->findBy(array('enabled' => true));;
        $output = array(
            'data' => array(),
        );
        foreach ($result as $item) {
            $category_image = array();
            $category = array();
            $image = array();

            $category_image[] = array(
                'id' => $item->getCategory()->getImage()->getId(),
                'image' => $item->getCategory()->getImage()->getImage(),
                'updated_at' => $item->getCategory()->getImage()->getUpdatedat(),
            );

            $category[] = array(
                'id' => $item->getCategory()->getId(),
                'name' => $item->getCategory()->getName(),
                'image' => $category_image,
            );

            $image[] = array(
                'id' => $item->getImage()->getId(),
                'image' => $item->getImage()->getImage(),
                'updated_at' => $item->getImage()->getUpdatedat(),
            );

            $output['data'][] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'price' => $item->getPrice(),
                'quantity' => $item->getQuantity(),
                'image' => $image,
                'category' => $category,
            ];
        }
        return new JsonResponse($output);
    }
}
