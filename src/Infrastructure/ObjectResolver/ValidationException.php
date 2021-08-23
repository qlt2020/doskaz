<?php
declare(strict_types=1);

namespace App\Infrastructure\ObjectResolver;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class ValidationException extends \Exception
{
    private ConstraintViolationListInterface $constraintViolationList;

    public function __construct(ConstraintViolationListInterface $constraintViolationList, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->constraintViolationList = $constraintViolationList;
        parent::__construct($message, $code, $previous);
    }

    public function constraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }
}
