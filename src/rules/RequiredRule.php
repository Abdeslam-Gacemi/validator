<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class RequiredRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        if (! array_key_exists($field, $data)) {
            $v->addError($field, "The field '$field' is required");
            return false;
        }
    }
}