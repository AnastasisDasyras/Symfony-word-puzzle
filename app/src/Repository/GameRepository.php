<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function validateRequest(array $data): bool
    {
        //TODO MORE VALDIATION NEEDED
        if (!isset($data['word'])) {
            return false;
        }
        return true;
    }

    public function validateWord(string $word, string $letterPool): bool
    {
        $word = strtolower(trim($word));
        $letterPool = strtolower(trim($letterPool));

        if (!$this->isValidEnglishWord($word)) {
            return false;
        }

        $wordLetters = count_chars($word, 1);
        $poolLetters = count_chars($letterPool, 1);

        foreach ($wordLetters as $char => $count) {
            if (!isset($poolLetters[$char]) || $count > $poolLetters[$char]) {
                return false;
            }
        }

        return true;
    }

    private function isValidEnglishWord(string $word): bool
    {
        return true;
    }
}
