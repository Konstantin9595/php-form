<?php
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;
use FormManager\Factory as Form;
use FaaPz\PDO\Database;

class App
{
    private $foo;
    private $db;

    public function __construct(string $foo, ResponseInterface $response, Database $database)
    {
        $this->foo = $foo;
        $this->response = $response;
        $this->db = $database;
    }

    public function __invoke()
    {
        $form = $this->renderForm();
        $response = $this->response->withHeader('Content-Type', 'text/html');
        // $out = implode(" ", $this->getUsers());
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

    public function getUsers()
    {
        $users = $this->db->select(["*"])->from('users');
        $stmt = $users->execute();
        $data = $stmt->fetch();
        return $data;
        //return $this->
    }
}