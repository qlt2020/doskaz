<?php


namespace App\Infrastructure;

use Goodwix\DoctrineJsonOdm\Annotation\ODM;
use Ramsey\Collection\AbstractCollection;
use Ramsey\Collection\CollectionInterface;
use Ramsey\Collection\Exception\CollectionMismatchException;

/**
 * @ODM()
 */
class FileReferenceCollection extends AbstractCollection
{
    public function getType(): string
    {
        return FileReference::class;
    }

    public function diff(CollectionInterface $other): CollectionInterface
    {
        if (!$other instanceof static) {
            throw new CollectionMismatchException('Collection must be of type ' . static::class);
        }

        $comparator = function (FileReference $a, FileReference $b) {
            return $a->equalsTo($b) ? 0 : -1;
        };

        $diffAtoB = array_udiff($this->data, $other->data, $comparator);
        $diffBtoA = array_udiff($other->data, $this->data, $comparator);

        return new static(array_merge($diffAtoB, $diffBtoA));
    }

    public function contains($element, bool $strict = true): bool
    {
        return !$this->filter(function (FileReference $fileReference) use ($element) {
            return $fileReference->relativePath === $element->relativePath;
        })->isEmpty();
    }

    public function remove($element): bool
    {
        foreach ($this->data as $key => $item) {
            if ($element->equalsTo($item)) {
                unset($this->data[$key]);
                return true;
            }
        }
        return false;
    }
}
