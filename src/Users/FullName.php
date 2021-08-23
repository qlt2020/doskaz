<?php


namespace App\Users;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;

/**
 * @ODM()
 */
class FullName
{
    /**
     * @var string|null
     */
    public $first;

    /**
     * @var string|null
     */
    public $last;

    /**
     * @var string|null
     */
    public $middle;

    /**
     * @var string|null
     */
    public $firstAndLast;

    /**
     * FullName constructor.
     * @param string|null $first
     * @param string|null $last
     * @param string|null $middle
     */
    public function __construct(?string $first = null, ?string $last = null, ?string $middle = null)
    {
        $this->first = $first;
        $this->last = $last;
        $this->middle = $middle;
        $this->firstAndLast = $this->firstAndLast();
    }

    public static function parseFromString(string $fullName): self
    {
        $parts = explode(' ', $fullName);
        return new self(...$parts);
    }

    public function empty(): bool
    {
        return empty($this->first) && empty($this->last) && empty($this->middle);
    }

    public function firstAndLast(): string
    {
        return implode(' ', array_filter([$this->first, $this->last], function ($item) {
            return !empty($item);
        }));
    }
}
