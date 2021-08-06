<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class CountRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! is_array($data[$field]) || count($data[$field]) !== $params[0]) {
            $v->addError($field, "The field '$field' must contain an array with '{$params[0]}' elements'");
            return false;
        }
    }
}