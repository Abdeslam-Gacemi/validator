<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

class NullableRule extends Rule
{
    public function __invoke(Validator $v, string $field, array $data, array $params)
    {
        return true;
    }
}