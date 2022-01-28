<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Command;
use App\Form\CommandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(ProductRepository $productRepository, SessionInterface $session, Request $request, EntityManagerInterface $em): Response
    {
        $cart = $session->get('panier', []);
        $products = [];
        foreach($cart as $id => $quantity)
        {
            $products[] = $productRepository->find($id);
        }

        $command = new Command();
        $commandForm = $this->createForm(CommandType::class, $command);
        $commandForm->handleRequest($request);
        if ($commandForm->isSubmitted() && $commandForm->isValid())
        {
            $command->setCreatedAt(new \DateTime);

            foreach ($products as $productC) {
                $command->addProduct($productC);
            }
            $em->persist($command);
            $em->flush($command);

            $this->addFlash('success', "Commande réussie");

            return $this->redirectToRoute('cart', []);
        }

        return $this->render('cart/index.html.twig', [
            'products' => $products,
            'total' => 0,
            'commandForm' => $commandForm->createView(),
        ]);
    }


    /**
     * @Route("/cart/add/{id}", name="cart.add")
     */
    public function add(ProductRepository $productRepository, $id, SessionInterface $session): Response
    {
        $product = $productRepository->find($id);

        if (!$product)
        {
            return $this->json('nok', 404);
        }

        $cart = $session->get('panier', []);
        $cart[$id] = 1;
        $session->set('panier', $cart);

        $this->addFlash('success', "Produit ajouté : {$product->getName()}");
        
        return $this->json('ok', 200);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart.delete")
     */
    public function delete(ProductRepository $productRepository, $id, SessionInterface $session): Response
    {
        $productRepository = $this->getDoctrine()->getRepository(Product::class);
        $product = $productRepository->find($id);

        if (!$product) {return $this->json('nok', 404);}

        $cart = $session->get('panier', []);
        
        foreach ($cart as $idCart => $quantity)
        {
            if ($idCart == $product->getId())
            {
                unset($cart[$idCart]);
                $session->set('panier', $cart);
                $this->addFlash('success', "Produit supprimé : {$product->getName()}");
                return $this->redirectToRoute('cart');
            }
        }

        $this->addFlash('error', "Produit non présent dans le panier : {$product->getName()}");

        return $this->redirectToRoute('cart');
    }

}
