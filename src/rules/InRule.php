<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class InRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! in_array($data[$field], $params)) {
            $in = implode(', ', $params);
            $v->addError($field, "The content of the field '$field' must be one of '$in'");
            return false;
        }
    }
}