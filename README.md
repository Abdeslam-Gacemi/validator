# A PHP data validator

## Usage

```php
use Abdeslam\Validator\Validator;

$validator = new Validator();

$validator->addRule('startsWith', function (Validator $v, string $field, array $data, array $params) {
    if (
        ! is_string($data[$field]) ||
        strpos($data[$field], $params[0]) !== 0
    ) {
        $v->addError($field, "String '$field' must start with '{$params[0]}'");
        return false;
    }
});

$validator->validate(
    [
        'email'             => 'me@mail.com',
        'password'          => '123456',
        'confirm_password'  => '123456',
        'message'           => 'Hello world',
    ],
    [
        'email'     => 'required|email',
        'password'  => 'required|min:6|confirmed',
        'message'   => 'required|string|startsWith:Hello',
    ]
);

var_dump($validator->hasErrors()); // false
var_dump($validator->hasErrorsFor('email')); // false
var_dump($validator->getErrors()); // []
var_dump($validator->getLastError()); // null
var_dump($validator->getValidatedData()); 
/*
    [
        'email'             => 'me@mail.com',
        'password'          => '123456',
        'message'           => 'Hello world',
    ]
*/
```