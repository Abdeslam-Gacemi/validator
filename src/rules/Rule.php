<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator\Rules;

use Abdeslam\Validator\Validator;

abstract class Rule
{
    /**
     * validates an entry in an array
     *
     * @param Validator $v Validator object
     * @param string $field the entry key
     * @param array $data the array of data
     * @param array $params supplementary params
     * @return bool
     */
    abstract public function __invoke(Validator $v, string $field, array $data, array $params);

}