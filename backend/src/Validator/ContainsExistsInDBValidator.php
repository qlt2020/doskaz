<?php

namespace App\Validator;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;


class ContainsExistsInDBValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function validate($value,Constraint $constraint)
    {
        if (!$constraint instanceof ContainsExistsInDB) {
            throw new UnexpectedTypeException($constraint, ContainsExistsInDB::class);
        }

        if (!is_int($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');
        }

        $qb = $this->entityManager->getConnection()->createQueryBuilder()
            ->select('*')
            ->from($constraint->getTable())
            ->where('id = :id')
            ->setParameter('id', $value)
            ->execute()
            ->fetchAll();

        if (count($qb) === 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }

    }
}