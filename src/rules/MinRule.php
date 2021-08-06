<?php

/**
 * @author Abdeslam Gacemi <abdobling@gmail.com>
 */

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class MinRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! is_string($data[$field]) || strlen($data[$field]) < $params[0]) {
            $v->addError($field, "The field '$field' must contain at least {$params[0]} characters");
            return false;
        }
    }
}