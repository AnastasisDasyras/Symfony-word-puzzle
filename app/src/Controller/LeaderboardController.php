<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
    #[Route('/leaderboard', name: 'app_leaderboard')]
    public function leaderboard(GameRepository $gameRepository): Response
    {
        // If user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Get top 10 players by total score
        $leaderboard = $gameRepository->getTopPlayersByTotalScore();

        return $this->render('leaderboard/index.html.twig', [
            'leaderboard' => $leaderboard,
            'user' => $this->getUser()
        ]);
    }
}
