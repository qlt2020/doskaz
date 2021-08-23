<?php


namespace App\Infrastructure;

class TranslatableListener extends \Gedmo\Translatable\TranslatableListener
{
    protected $locale = 'ru';
}
