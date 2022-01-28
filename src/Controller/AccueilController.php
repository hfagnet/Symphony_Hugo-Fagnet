<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(ProductRepository $productRepository)
    {
        return $this->render('accueil/index.html.twig', [
            'mostRecentProducts'  => $productRepository->findMostRecent(5),
            'lessPriceProducts' => $productRepository->findLessPrice(5),
        ]);
    }

}
