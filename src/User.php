<?php
declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;
use FormManager\Factory as Form;
use FormManager\ValidationError as FormValidator;
use FaaPz\PDO\Database;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\JsonResponse;
use FormManager\ValidatorFactory;

class User
{
    private $foo;
    private $db;

    public function __construct(ResponseInterface $response, Database $database)
    {
        $this->foo = $foo;
        $this->response = $response;
        $this->db = $database;
    }

    public function __invoke()
    {
        $form = $this->renderForm();
        $response = $this->response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write("<html><head></head><body>{$form}</body></html>");

        return $response;
    }

    public function renderForm()
    {
        $form = Form::form([
            'name' => Form::text('Имя', ['name' => 'name', 'placeholder' => 'Имя', 'required']),
            'email' => Form::email('Email', ['name' => 'email', 'type' => 'email', 'placeholder' => 'E-mail', 'required']),
            'message' => Form::textarea('Сообщение', ['name' => 'message', 'placeholder' => 'Сообщение', 'required']),
            'submit' => Form::submit('Отправить', ['name' => 'send', 'value' => "Отправить"])
        ], ['method' => 'POST', 'action' => '/send-entry']);

        return $form;
    }

    public function entry($request)
    {
        return $this->renderForm();
    }

    public function sendEntry($request)
    {
        
        $params = $request->getParsedBody();
        $name = htmlspecialchars(strip_tags($params['name']));
        $email = htmlspecialchars(strip_tags($params['email']));
        $message = htmlspecialchars(strip_tags($params['message']));


        $insertStatement = $this->db->insert([
            "name" => $name,
            "email" => $email,
            "message" => $message,
            "date" => date("Y-m-d h:i:s", time() )
        ])->into("user_entry");

        try {
            $insertStatement->execute();

            header("Location:/thanks?". "name={$name}", false, 301);
            exit;
        }catch(Exeption $e) {
            throw new Error($e);
        }

    }

}