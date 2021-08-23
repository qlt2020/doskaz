<?php


namespace App\Feedback;

use Doctrine\ORM\EntityManagerInterface;

class FeedbackRepository
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Feedback::class);
    }

    public function add(Feedback $feedback)
    {
        $this->entityManager->persist($feedback);
    }
}
