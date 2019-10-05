<?php
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;
use FormManager\Factory as Form;

class App
{
    private $foo;

    public function __construct(string $foo, ResponseInterface $response)
    {
        $this->foo = $foo;
        $this->response = $response;

    }

    public function __invoke(): ResponseInterface
    {
        $form = $this->renderForm();
        $response = $this->response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write("<html><head></head><body>{$form}</body></html>");

        return $response;
    }

    public function renderForm()
    {
        $form = Form::form([
            'name' => Form::text('Имя', ['name' => 'name', 'placeholder' => 'Имя']),
            'email' => Form::email('Email', ['name' => 'email', 'type' => 'email', 'placeholder' => 'E-mail']),
            'password' => Form::password('Password', ['name' => 'password', 'type' => 'password', 'placeholder' => 'Пароль']),
            'submit' => Form::submit('Зарегистрироваться', ['name' => 'register', 'value' => "Зарегистрироваться"])
        ], ['method' => 'POST', 'action' => '/register']);

        return $form;
    }
}