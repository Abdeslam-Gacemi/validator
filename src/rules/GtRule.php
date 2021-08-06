<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class GtRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! is_numeric($data[$field]) || $data[$field] <= $params[0]) {
            $v->addError($field, "The field '$field' must be greater than {$params[0]}");
            return false;
        }
    }
}