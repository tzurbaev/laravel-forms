<?php

namespace Zurbaev\Forms\Tests\Stubs;

use Zurbaev\Forms\AbstractForm;

class ExampleForm extends AbstractForm
{
    public function method()
    {
        return 'POST';
    }

    public function action()
    {
        return 'https://example.org';
    }

    public function fields()
    {
        return [
            'name' => ['label' => 'Name'],
            'email' => [
                'label' => 'Email',
                'attributes' => ['type' => 'email'],
            ],
            'password' => [
                'label' => 'Password',
                'attributes' => [
                    'type' => 'password',
                    'class' => 'password-visible',
                ],
            ],
            'photo_file' => [
                'type' => 'file-upload',
                'value_lookup' => 'photo',
                'label' => 'Photo',
            ],
        ];
    }

    public function values()
    {
        return [
            'name' => 'John Doe',
            'email' => 'john@example.org',
            'password' => 'secret',
            'photo' => 'https://example.org/image.png',
        ];
    }
}
