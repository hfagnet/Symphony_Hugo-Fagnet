<?php

namespace App\Controller;

use App\Repository\CommandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    /**
     * @Route("/command", name="command")
     */
    public function index(CommandRepository $commandRepository): Response
    {
        $commands = $commandRepository->findAll();
        return $this->render('command/index.html.twig', ['commands' => $commands]);
    }


    /**
     * @Route("/command/{id}", name="command.show")
     */
    public function show(CommandRepository $commandRepository, $id)
    {
        $command = $commandRepository->find($id);
        if (!$command)
        {
            throw $this->createNotFoundException('Commande inexistante');
        }

        return $this->render('command/show.html.twig', [
            'command' => $command,
            'totalProduct' => 0,
            'totalPrice' => 0,
        ]);
    }
}
