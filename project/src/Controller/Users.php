<?php

namespace App\Controller;

class Users
{
    public function run()
    {
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

    public function runAdd()
    {
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

    public function runUpdate()
    {
        if (!isset($_GET['id'])) {
            header('Location: /users');
            return;
        }

        $pdo = \App\Service\DB::get();
        $stmt = $pdo->prepare("
                SELECT
                    *
                FROM
                    `users`
                WHERE
                    `id` = :id
            ");

        $stmt->execute([
            ':id' => $_GET['id']
        ]);

        if (!$user = $stmt->fetch()) {
            header('Location: /users');
            return;
        }

        $validator = $this->getValidator(true);
        if ($_POST && $validator->check($_POST)) {
            if ($_POST['pass'] == '') {
                $stmt = $pdo->prepare("
                    UPDATE
                        `users`
                    SET
                        `email` = :email,
                        `name` = :name,
                        `privilege` = :privilege
                    WHERE
                        `id` = :id
                ");

                $stmt->execute([
                    ':id' => $_GET['id'],
                    ':email' => $_POST['email'],
                    ':name' => $_POST['name'],
                    ':privilege' => $_POST['privilege'],
                ]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE
                        `users`
                    SET
                        `email` = :email,
                        `name` = :name,
                        `pass` = :pass,
                        `privilege` = :privilege
                    WHERE
                        `id` = :id
                ");

                $stmt->execute([
                    ':id' => $_GET['id'],
                    ':email' => $_POST['email'],
                    ':name' => $_POST['name'],
                    ':pass' => sha1($_POST['pass']),
                    ':privilege' => $_POST['privilege']
                ]);
            }

            header('Location: /users');
            return;
        }

        $view = new \App\View\Users\Form();
        $view->render([
            'title' => 'Редактор пользователя',
            'data' => $user,
            'messages' => $validator->getMessages()
        ]);
    }

    public function runDelete()
    {
        $pdo = \App\Service\DB::get();

        if (isset($_POST['id'])) {
            $stmt = $pdo->prepare("
                DELETE FROM 
                    `users` 
                WHERE
                    `id` = :id
            ");

            $stmt->execute([
                ':id' => $_POST['id']
            ]);

            header('Location: /users');
            return;
        }
        if (!isset($_GET['id'])) {
            header('Location: /users');
            return;
        }

        $stmt = $pdo->prepare("
            SELECT
                *
            FROM
                `users`
            WHERE
                `id` = :id
        ");

        $stmt->execute([
            ':id' => $_GET['id']
        ]);

        if (!$user = $stmt->fetch()) {
            header('Location: /users');
            return;
        }

        $view = new \App\View\Users\DeleteForm();
        $view->render([
            'title' => 'Удаление пользователя',
            'user' => $user,
            'url' => [
                'approve' => '/users/delete',
                'cancel' => '/users'
            ]
        ]);
    }

    private function getValidator($isUpdate = false)
    {
        $validator = new \App\Service\Validator();
        $validator
            ->setRule('email', function ($value) {
                return !is_null($value) && mb_strlen($value) > 0;
            }, 'Это поле обязательно')
            ->setRule('email', function ($value) {
                return preg_match('/^[^@]+@[^@]+$/', $value);
            }, 'Неправильный адрес электронной почты')
            ->setRule('name', function ($value) {
                return preg_match('/.{2,50}/', $value);
            }, 'Длина имени должно быть от 2-50 символов')
            ->setRule('privilege', function ($value) {
                return in_array((int)$value, [0, 1]);
            }, 'Неверное значение привилегий')
            ->setRule('conf-pass', function ($value, $data) {
                return isset($data['pass']) && $data['pass'] === $value;
            }, 'Введенный пароль не соответствует оригиналу');

        if ($isUpdate) {
            $validator
                ->setRule('pass', function ($value) {
                    return $value == '' || preg_match('/.{8,100}/', $value);
                }, 'Длина пароля должно быть от 8-100 символов');
        } else {
            $validator
                ->setRule('pass', function ($value) {
                    return preg_match('/.{8,100}/', $value);
                }, 'Длина пароля должно быть от 8-100 символов');
        }

        return $validator;
    }
}