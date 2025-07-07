<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Puzzle;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // If user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Get all puzzles
        $puzzles = $entityManager->getRepository(Puzzle::class)->findAll();

        return $this->render('home/index.html.twig', [
            'user' => $this->getUser(),
            'puzzles' => $puzzles,
        ]);
    }
}
