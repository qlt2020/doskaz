<?php


namespace App\Objects\Adding;

use Webmozart\Assert\Assert;

class Attribute
{
    private const NOT_PROVIDED = 'not_provided';
    private const YES = 'yes';
    private const NO = 'no';
    private const UNKNOWN = 'unknown';

    public const ATTRIBUTES = [
        self::NOT_PROVIDED,
        self::YES,
        self::NO,
        self::UNKNOWN
    ];

    private string $value;

    public function __construct(string $value)
    {
        Assert::oneOf($value, self::ATTRIBUTES);
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public static function notProvided(): self
    {
        return new self(self::NOT_PROVIDED);
    }

    public static function yes(): self
    {
        return new self(self::YES);
    }

    public static function no(): self
    {
        return new self(self::NO);
    }

    public static function unknown(): self
    {
        return new self(self::UNKNOWN);
    }

    public function isEqualsTo(Attribute $attribute): bool
    {
        return $attribute->value === $this->value;
    }
}
