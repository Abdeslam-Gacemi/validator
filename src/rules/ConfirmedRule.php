<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class ConfirmedRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! isset($data['confirm_' . $field]) || $data['confirm_' . $field] !== $data[$field]) {
            $v->addError($field, "The field '$field' must be confirmed");
            return false;
        }
    }
}