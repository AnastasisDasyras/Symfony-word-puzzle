<?php

namespace App\Controller;

use App\Entity\Puzzle;
use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    #[Route('/game/{id}', name: 'app_game')]
    public function game(int $id, EntityManagerInterface $entityManager): Response
    {
        // If user is not logged in, redirect to login
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Find the puzzle by ID
        $puzzle = $entityManager->getRepository(Puzzle::class)->find($id);

        //create a new game
        try {
            $game = new Game();
            $game->setUserId($this->getUser()->getId());
            $game->setPuzzleId($id);
            $game->setScore(0);
            $entityManager->persist($game);
            $entityManager->flush();
        } catch (\Exception $e) {
            $this->addFlash('error', 'An error occurred while creating the game. Please try again.');
            return $this->redirectToRoute('app_game', ['id' => $id]);
        }

        if (!$puzzle) {
            throw $this->createNotFoundException('Puzzle not found');
        }

        return $this->render('game/index.html.twig', [
            'user' => $this->getUser(),
            'puzzle' => $puzzle,
            'game_id' => $game->getId(),
        ]);
    }


    #[Route('/game/{id}/submit-word', name: 'app_game_submit_word', methods: ['POST'])]
    public function gameSubmitWord(int $id, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if (!$this->getUser()) {
            return new JsonResponse(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $data = json_decode($request->getContent(), true);

        $gameRepository = $entityManager->getRepository(Game::class);
        $isValidRequest = $gameRepository->validateRequest($data);
        if (!$isValidRequest) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid request'], 400);
        }

        $isValidWord = $gameRepository->validateWord($data['word'], $data['availableLetters']);
        if (!$isValidWord) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid word'], 400);
        }

        return new JsonResponse([
            'success' => true,
            'message' => 'Word is valid!',
            'points'  => strlen($data['word']),
            'word'    => $data['word']
        ]);
    }

    #[Route('/game/{id}/finish', name: 'app_game_finish', methods: ['POST'])]
    public function gameFinish(int $id, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$this->getUser()) {
            return new JsonResponse(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $data = json_decode($request->getContent(), true);

        $gameRepository = $entityManager->getRepository(Game::class);
        $game = $gameRepository->find($data['gameId']);
        if (!$game) {
            return new JsonResponse(['success' => false, 'message' => 'Game not found'], 404);
        }

        $game->setScore($data['finalScore']);
        $entityManager->flush();

        return new JsonResponse(['success' => true, 'message' => 'Game finished'], 200);
    }
}
