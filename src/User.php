<?php
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;
use FormManager\Factory as Form;
use FaaPz\PDO\Database;
use Zend\Diactoros\Response\JsonResponse;

class User
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
            'message' => Form::textarea('Сообщение', ['name' => 'message', 'placeholder' => 'Сообщение']),
            'submit' => Form::submit('Отправить', ['name' => 'send', 'value' => "Отправить"])
        ], ['method' => 'POST', 'action' => '/send-entry']);

        return $form;
    }

    // public function getUsers()
    // {
    //     $users = $this->db->select(["*"])->from('users');
    //     $stmt = $users->execute();
    //     $data = $stmt->fetch();
    //     return $data;
    //     //return $this->
    // }

    public function entry($request)
    {
        // $response = $this->response->withHeader('Content-Type', 'application/hal+json');
        // $response->getBody()->write(json_encode(["name" => "Konstantin"]));

        return $this->renderForm();


    }
}