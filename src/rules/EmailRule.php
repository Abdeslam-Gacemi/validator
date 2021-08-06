<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class EmailRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        $content = $data[$field] ?? null;
        $validation = filter_var($content, FILTER_VALIDATE_EMAIL);
        if ($validation === false) {
            $v->addError($field, "The field '$field' must be a valid email address");
            return false;
        }
    }
}