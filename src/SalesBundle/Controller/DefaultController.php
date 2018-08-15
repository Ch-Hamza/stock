<?php

namespace SalesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/save-sale", name="save_sale_page")
     */
    public function SaveSaleAction()
    {
        //if post save sale then redirect to index
        //else redirect to index
    }
}
