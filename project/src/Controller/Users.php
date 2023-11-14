<?php

namespace App\Controller;

class Users {
    public function run () {
        $pdo = \App\Service\DB::get();
        $stmt = $pdo->prepare("
            SELECT
                *
            FROM
                `users`
        ");
        $stmt->execute();

        $view = new \App\View\Users();
        $view->render([
            'title' => 'Пользователи',
            'data' => $stmt->fetchAll()
        ]);
    }

    public function runAdd () {
        $validator = $this->getValidator();

        if ($_POST && $validator->check($_POST)) {
            $pdo = \App\Service\DB::get();
            $stmt = $pdo->prepare("
                INSERT INTO
                    `users` (
                         `email`,
                         `pass`,
                         `name`,
                         `privilege`
                    ) VALUES (
                         :email,
                         :pass,
                         :name,
                         :privilege
                    )
            ");
            $stmt->execute([
                ':email' => $_POST['email'],
                ':pass' => sha1($_POST['pass']),
                ':name' => $_POST['name'],
                ':privilege' => $_POST['privilege'],
            ]);

            header('Location: /users');
            return;
        }
        $view = new \App\View\Users\Form();
        $view->render([
            'title' => 'Создание нового пользователя',
            'data' => $_POST,
            'messages' => $validator->getMessages()
        ]);
    }

    private function getValidator () {
        $validator = new \App\Service\Validator();
        $validator
            ->setRule('email', function ($value) {
                return !is_null($value) && mb_strlen($value) > 0;
            }, 'Это поле обязательно')
            ->setRule('email', function ($value) {
                return preg_match_all('/^[^@]+@[^@]+$/', $value);
            }, 'Неправильный адрес электронной почты')
            ->setRule('name', function ($value) {
                return preg_match_all('/.{2,50}/', $value);
            }, 'Длина имени должно быть от 2-50 символов')
            ->setRule('pass', function ($value) {
                return preg_match_all('/.{8,100}/', $value);
            }, 'Длина пароля должно быть от 8-100 символов')
            ->setRule('conf-pass', function ($value, $data) {
                return isset($data['pass']) && $data['pass'] === $value;
            }, 'Введенный пароль не соответствует оригиналу')
            ->setRule('privilege', function ($value) {
                return in_array((int)$value, [0, 1]);
            }, 'Неверное значение привилегий');

        return $validator;
    }
}