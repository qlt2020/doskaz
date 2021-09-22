<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Doctrine\ORM\Mapping\Annotation;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @Annotation
 */
class ContainsExistsInDB extends Constraint
{
    protected $table;

  public function __construct($options = null)
  {
      parent::__construct($options);

      if ($options['table']) {
          $this->table = $options['table'];
      } else {
          throw new MissingOptionsException("...");
      }
  }

    public $message = 'The cityId "{{ string }}" contains an illegal character: it can only exists on database.';

    public function getTable()
    {
        return $this->table;
    }
}