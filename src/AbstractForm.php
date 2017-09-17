<?php

namespace Zurbaev\Forms;

use Illuminate\Support\Str;

abstract class AbstractForm
{
    /**
     * @var array
     */
    protected $validTypes = [
        'hidden', 'text', 'select', 'checkbox', 'file-upload', 'summernote',
    ];

    /**
     * @var array
     */
    protected $excludeFromExtra = [
        'method', 'action', 'class', 'enctype',
    ];

    /**
     * Get the form's HTTP method.
     *
     * @return string
     */
    abstract public function method();

    /**
     * Get the form's action URL.
     *
     * @return string
     */
    abstract public function action();

    /**
     * Get the form's fields.
     *
     * @return array
     */
    abstract public function fields();

    /**
     * Additional form options.
     *
     * @return array
     */
    public function options()
    {
        return [];
    }

    /**
     * Determines if current form contains file fields.
     *
     * @return bool
     */
    public function withUploads()
    {
        return false;
    }

    /**
     * Form values (editing mode).
     *
     * @return array
     */
    public function values()
    {
        return [];
    }

    /**
     * Unique form ID.
     *
     * @return string
     */
    public function id()
    {
        return 'abstract-form-'.rand();
    }

    /**
     * Get the submit button label.
     *
     * @return string
     */
    public function submitLabel()
    {
        return 'Submit';
    }

    /**
     * Get the form option value.
     *
     * @param string $path
     * @param null   $default
     *
     * @return mixed
     */
    public function get(string $path, $default = null)
    {
        return array_get($this->options(), $path, $default);
    }

    /**
     * Form's extra attributes.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function extraAttributes(array $attributes = [])
    {
        return collect($this->onlyExtraAttributes($attributes))
            ->map(function (string $value, string $attribute) {
                return $attribute.'="'.$value.'"';
            })
            ->implode(' ');
    }

    /**
     * Get only extra form attributes.
     *
     * @param array $attributes
     *
     * @return array
     */
    public function onlyExtraAttributes(array $attributes = [])
    {
        $attributes = $attributes ?: $this->get('attributes', []);

        foreach ($this->excludeFromExtra as $attribute) {
            if (isset($attributes[$attribute])) {
                unset($attributes[$attribute]);
            }
        }

        return $attributes;
    }

    /**
     * Get the form field by name.
     *
     * @param string $name
     *
     * @return array|null
     */
    public function getField(string $name)
    {
        return array_get($this->fields(), $name);
    }

    /**
     * Determines if given field is a valid field.
     *
     * @param array $field
     *
     * @return bool
     */
    public function isValidField(array $field)
    {
        return in_array($this->fieldType($field), $this->validTypes);
    }

    /**
     * Generates ID for given input element.
     *
     * @param string $name
     *
     * @return string
     */
    public function inputId(string $name)
    {
        return 'input'.Str::ucfirst(Str::camel(Str::lower($name)));
    }

    /**
     * Determines if given field should be rendered with its markup only.
     *
     * @param array $field
     *
     * @return bool
     */
    public function fieldShouldUseOwnMarkup(array $field)
    {
        return in_array($this->fieldType($field), ['hidden', 'checkbox']);
    }

    /**
     * Get the form field type.
     *
     * @param array $field
     *
     * @return string
     */
    public function fieldType(array $field)
    {
        return Str::lower($field['type'] ?? 'text');
    }

    /**
     * Get the field attribute value.
     *
     * @param array  $field
     * @param string $attribute
     * @param null   $default
     *
     * @return mixed
     */
    public function fieldAttributeValue(array $field, string $attribute, $default = null)
    {
        return array_get($field, 'attributes.'.$attribute, $default);
    }

    /**
     * Get the field value.
     *
     * @param string $name
     * @param array  $field
     * @param null   $default
     *
     * @return mixed|string
     */
    public function fieldValue(string $name, array $field, $default = null)
    {
        if ($this->fieldAttributeValue($field, 'type') === 'password') {
            return '';
        }

        $lookupName = $field['value_lookup'] ?? $name;
        $value = array_get($this->values(), $lookupName, $default);

        return old($name, $value);
    }

    /**
     * Get the field CSS classes.
     *
     * @param array $field
     * @param bool  $prependWithSpace = true
     *
     * @return string
     */
    public function fieldClasses(array $field, bool $prependWithSpace = true)
    {
        $classes = array_get($field, 'attributes.class', '');

        if (!$classes) {
            return '';
        }

        return ($prependWithSpace ? ' ' : '').$classes;
    }

    /**
     * Get additional field attributes.
     *
     * @param array $field
     *
     * @return string
     */
    public function fieldAttributes(array $field)
    {
        if (empty($field['attributes'])) {
            return '';
        }

        return $this->extraAttributes($field['attributes']);
    }
}