<?php
/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
* this file returns an array containing the rules for the validators [$name => $rule]
*/

use Abdeslam\Validator\Rules\GtRule;
use Abdeslam\Validator\Rules\InRule;
use Abdeslam\Validator\Rules\LtRule;
use Abdeslam\Validator\Rules\MaxRule;
use Abdeslam\Validator\Rules\MinRule;
use Abdeslam\Validator\Rules\JsonRule;
use Abdeslam\Validator\Rules\ArrayRule;
use Abdeslam\Validator\Rules\EmailRule;
use Abdeslam\Validator\Rules\HasKeyRule;
use Abdeslam\Validator\Rules\StringRule;
use Abdeslam\Validator\Rules\HasKeysRule;
use Abdeslam\Validator\Rules\NumericRule;
use Abdeslam\Validator\Rules\NullableRule;
use Abdeslam\Validator\Rules\RequiredRule;
use Abdeslam\Validator\Rules\ConfirmedRule;
use Abdeslam\Validator\Rules\CountRule;
use Abdeslam\Validator\Rules\NotInRule;

return [
    'required'      => RequiredRule::class,
    'nullable'      => NullableRule::class,
    'string'        => StringRule::class,
    'array'         => ArrayRule::class,
    'numeric'       => NumericRule::class,
    'json'          => JsonRule::class,
    'email'         => EmailRule::class,
    'confirmed'     => ConfirmedRule::class,
    'min'           => MinRule::class,
    'max'           => MaxRule::class,
    'in'            => InRule::class,
    'notIn'         => NotInRule::class,
    'hasKey'        => HasKeyRule::class,
    'hasKeys'       => HasKeysRule::class,
    'count'         => CountRule::class,
    'greaterThan'   => GtRule::class,
    'lessThan'      => LtRule::class,
];