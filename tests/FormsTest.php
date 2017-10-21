<?php

namespace Zurbaev\Forms\Tests;

use PHPUnit\Framework\TestCase;
use Zurbaev\Forms\Tests\Stubs\ExampleForm;

class FormsTest extends TestCase
{
    /**
     * @var ExampleForm
     */
    protected $form;

    protected function setUp()
    {
        parent::setUp();

        $this->form = new ExampleForm();
        \OldValuesStorage::flush();
    }

    public function testFieldWithoutTypeShouldBeDetectdAsText()
    {
        $field = $this->form->getField('name');

        $this->assertTrue($this->form->isValidField($field));
        $this->assertSame('text', $this->form->fieldType($field));
    }

    public function testFormShouldGenerateFieldIds()
    {
        $expected = 'inputEmail';

        $this->assertSame($expected, $this->form->inputId('email'));
        $this->assertSame($expected, $this->form->inputId('EMAIL'));
    }

    public function testFormShouldReturnFieldClasses()
    {
        $expected = 'password-visible';
        $field = $this->form->getField('password');

        $this->assertSame(' '.$expected, $this->form->fieldClasses($field));
        $this->assertSame($expected, $this->form->fieldClasses($field, false));
    }

    public function testFormShouldReturnFieldAttributeValues()
    {
        $expected = 'password-visible';
        $field = $this->form->getField('password');

        $this->assertSame($expected, $this->form->fieldAttributeValue($field, 'class'));
    }

    public function testFieldClassesShouldBeMissingFromExtraAttributes()
    {
        $field = $this->form->getField('password');

        $this->assertFalse(isset($this->form->onlyExtraAttributes($field['attributes'])['class']));
    }

    public function testFormShouldRespectValueLookupFieldWhenRetrieveingFieldValue()
    {
        $expected = 'https://example.org/image.png';
        $field = $this->form->getField('photo_file');

        $this->assertSame($expected, $this->form->fieldValue('photo_file', $field));
    }

    public function testFormShouldUseMutatorsWhenPossible()
    {
        $field = $this->form->getField('mutated_input');

        $this->assertSame('default value', $this->form->fieldValue('mutated_input', $field));

        \OldValuesStorage::set('mutated_input', 'mutated input');
        $this->assertSame('mutated from old value, old: "mutated input"', $this->form->fieldValue('mutated_input', $field));
    }
}
