<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

use Abdeslam\Validator\Validator;

require __DIR__ . '/../vendor/autoload.php';

$validator = new Validator();

$validator->addRule('someRule', function () {
    return true;
});
$data = [
    'name' => 'abdeslam',
    'age' => '27',
    'email' => 'abdeslam@mail.com',
    'password' => '123456',
    'confirm_password' => '123456',
    'address' => [
        'country' => 'Algeria',
        'city' => 'Algiers'
    ],
    'tags' => '["tag1", "tag2"]'
];

$validator->validate(
    $data, 
    [
        'name' => 'required|min:7',
        'age' => 'numeric|greaterThan:18',
        'address' => 'required|hasKeys:city,country',
        'email' => ['required', 'email', 'min:2', 'max' => [20]],
        'password' => 'required|min:6|confirmed',
        'tags' => 'required|array|count:2',
    ]
);
var_dump($validator->getErrors(), $validator->getValidatedData(), $validator->getLastError());