<?php

/**
* @author Abdeslam Gacemi <abdobling@gmail.com>
*/

namespace Abdeslam\Validator;

use Abdeslam\Validator\Rules\RuleInterface;
use Closure;
use Exception;

/**
 * todo: add custom messages for rules
 */
class Validator
{
    /** @var callable[] */
    protected $rules = [];

    /** @var array */
    protected $baseRules = [];

    /** @var array */
    protected $validated = [];

    /** @var array */
    protected $errors = [];

    /** @var array */
    protected $data = [];

    /** @var array */
    protected $dataRules = [];

    /** @var bool */
    protected $throwsException = false;

    /** @var string */
    protected $lastError;

    public function __construct(bool $throwsException = false) {
        $this->throwsException = $throwsException;
        $baseRules = require __DIR__ . '/rules.php';
        $this->rules = $this->baseRules = $baseRules;
    }
    
    /**
     * adds a rule to the validator
     *
     * @param string $name
     * @param callable|string $rule
     * @return static
     */
    public function addRule(string $name, $rule): static
    {
        if (array_key_exists($name, $this->baseRules)) {
            throw new Exception("Cannot override base rule '$name'");
        }
        $ruleInterface = RuleInterface::class;
        if (! is_subclass_of($rule, $ruleInterface) && ! $rule instanceof Closure) {
            throw new Exception("Rule class must be a FQCN or an object that implements $ruleInterface or a valid closure");
        }
        $this->rules[$name] = $rule;
        return $this;
    }

    /**
     * adds rules to the validator
     *
     * @param array $rules [string $name => callable $rule]
     * @return static
     */
    public function addRules(array $rules): static
    {
        foreach ($rules as $name => $rule) {
            $this->addRule($name, $rule);
        }
        return $this;
    }

    /**
     * validates an array of data against an array of rules
     *
     * @param array $data [string $filedName => mixed $value]
     * @param array $rules [string $fieldName => array $rules]
     * @return static
     */
    public function validate(array $data, array $rules): static
    {
        $this->reset();
        $this->data = $data;
        $this->dataRules = $rules;

        foreach ($rules as $fieldName => $rulesList) {
            $this->dataRules[$fieldName] = $rulesList = $this->extractFieldRules($rulesList);
            foreach ($rulesList as $ruleName => $params) {
                [$ruleName, $params] = $this->extractRuleData($ruleName, $params);

                $rule = $this->getRule($ruleName);

                if (
                    (
                        ! array_key_exists($fieldName, $data) &&
                        ! $this->fieldHasRule($fieldName, 'required')
                    ) || (
                        ! array_key_exists($fieldName, $data) &&
                        $ruleName !== 'required'
                    )
                ) {
                    continue;
                }

                if (
                    array_key_exists($fieldName, $data) &&
                    $data[$fieldName] === null &&
                    $this->fieldHasRule($fieldName, 'nullable')
                ) {
                    continue;
                }

                $ruleResult = call_user_func_array(
                    $rule,
                    [$this, $fieldName, $data, $params]
                );

                if ($ruleResult === false && $this->throwsException()) {
                    throw new Exception($this->getLastError());
                } elseif ($ruleResult !== false) {
                    $this->validated[$fieldName] = $data[$fieldName];
                }
            }
            if ($this->hasErrorsFor($fieldName)) {
                unset($this->validated[$fieldName]);
            }
        }
        return $this;
    }

    /**
     * adds an error to the array of errors
     *
     * @param string $fieldName the name of field that generated the error
     * @param string $error the error
     * @return static
     */
    public function addError(string $fieldName, string $error): static
    {
        if (! array_key_exists($fieldName, $this->errors)) {
            $this->errors[$fieldName] = [$error];
        } else {
            $this->errors[$fieldName][] = $error;
        }
        $this->lastError = $error;
        return $this;
    }

    /**
     * wether the last validation generated any errors
     *
     * @return boolean
     */
    public function hasErrors(): bool
    {
        return ! empty($this->errors);
    }

    /**
     * whether a specific field validation generated an error
     *
     * @param string $fieldName
     * @return boolean
     */
    public function hasErrorsFor(string $fieldName): bool
    {
        return array_key_exists($fieldName, $this->errors);
    }

    /**
     * gets the array of errors of the last validation
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * gets errors for a specific field
     *
     * @param string $fieldName
     * @return array
     */
    public function getErrorsFor(string $fieldName): array
    {
        if ($this->hasErrorsFor($fieldName)) {
            return $this->errors[$fieldName];
        }
        return [];
    }

    /**
     * gets the last validation error
     *
     * @return string|null
     */
    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    /**
     * gets the throwException state
     *
     * @return boolean
     */
    public function throwsException(): bool
    {
        return $this->throwsException;
    }

    /**
     * gets the array of the validated data from the last validation
     *
     * @return void
     */
    public function getValidatedData()
    {
        return $this->validated;
    }

    /**
     * resets the validator
     *
     * @return static
     */
    public function reset(): static
    {
        $this->validated = [];
        $this->errors = [];
        $this->data = [];
        $this->dataRules = [];
        return $this;
    }

    /**
     * whether the field to validate has a specific rule
     *
     * @param string $name rule name
     * @return boolean
     */
    public function fieldHasRule(string $field, string $ruleName): bool
    {
        $fieldRules = $this->dataRules[$field] ?? [];
        return array_key_exists($ruleName, $fieldRules) || in_array($ruleName, $fieldRules);
    }

    
    /**
     * whether the validator has a specific rule
     *
     * @param string $name rule name
     * @return boolean
     */
    public function hasRule(string $ruleName): bool
    {
        return isset($this->rules[$ruleName]);
    }

    /**
     * gets a rule by its name
     *
     * @param string $name
     * @return callable
     */
    public function getRule(string $name): callable
    {
        if (! $this->hasRule($name)) {
            throw new Exception("Undefined rule '$name'");
        }
        if (is_string($this->rules[$name])) {
            $this->rules[$name] = new ($this->rules[$name])($this);
        }
        return $this->rules[$name];
    }

    /**
     * extract rules list for a field
     *
     * @param string|array $rulesList
     * @return array
     */
    protected function extractFieldRules($rulesList): array
    {
        $rules = [];
        if (is_string($rulesList)) {
            $rules = $this->extractRulesFromString($rulesList);
        } elseif (is_array($rulesList)) {
            foreach ($rulesList as $ruleName => $params) {
                [$ruleName, $params] = $this->extractRuleData($ruleName, $params);
                $rules[$ruleName] = $params;
            }
        }
        return $rules;
    }

    /**
     * extract rules from a string
     *
     * @param string $ruleList
     * @return array
     */
    protected function extractRulesFromString(string $ruleList): array
    {
        $ruleList = explode('|', $ruleList);
        $rules = [];
        foreach ($ruleList as $rule) {
            [$ruleName, $params] = $this->extractRuleData(null, $rule);
            $rules[$ruleName] = $params;
        }
        return $rules;
    }

    /**
     * extracts the name of the rule and the parameters
     *
     * @param string|int $ruleName
     * @param string|array $params
     * @return array
     */
    protected function extractRuleData($ruleName, $params): array
    {
        if (is_string($params)) {
            $segments = explode(':', $params);
            $ruleName = $segments[0];
            $params = isset($segments[1]) ? explode(',', $segments[1]) : [];
            return [$ruleName, $params];
        }
        $params = is_array($params) ? $params : [$params];
        return [$ruleName, $params];
    }
}