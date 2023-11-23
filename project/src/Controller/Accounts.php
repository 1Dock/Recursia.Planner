<?php

namespace App\Controller;

class Accounts
{
    public function run()
    {
        $pdo = \App\Service\DB::get();
        $stmt = $pdo->prepare("
            SELECT
                *
            FROM
                `accounts`
        ");

        $stmt->execute();

        $view = new \App\View\Accounts();
        $view->render([
            'title' => 'Instagram - аккаунты',
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
                    `accounts` (
                         `login`,
                         `pass`,
                         `id_user`
                    ) VALUES (
                         :login,
                         :pass,
                         :idu
                    )
            ");

            $stmt->execute([
                ':login' => $_POST['login'],
                ':pass' => $_POST['pass'],
                ':idu' => $_SESSION['auth']['id']
            ]);

            header('Location: /accounts');
            return;
        }

        $view = new \App\View\Accounts\Form();
        $view->render([
            'title' => 'Добавление нового аккаунта',
            'data' => $_POST,
            'messages' => $validator->getMessages()
        ]);
    }

    public function runUpdate()
    {
        if (!isset($_GET['id'])) {
            header('Location: /accounts');
            return;
        }

        $pdo = \App\Service\DB::get();
        $stmt = $pdo->prepare("
                SELECT
                    *
                FROM
                    `accounts`
                WHERE
                    `id` = :id AND `id_user` = :idu
            ");

        $stmt->execute([
            ':id' => $_GET['id'],
            ':idu' => $_SESSION['auth']['id']
        ]);

        if (!$account = $stmt->fetch()) {
            header('Location: /accounts');
            return;
        }

        $validator = $this->getValidator(true);
        if ($_POST && $validator->check($_POST)) {
            $stmt = $pdo->prepare("
                UPDATE
                    `accounts`
                SET
                    `login` = :login,
                    `pass` = :pass
                WHERE
                    `id` = :id AND `id_user` = :idu
            ");

            $stmt->execute([
                ':login' => $_POST['login'],
                ':pass' => $_POST['pass'],
                ':id' => $_GET['id'],
                ':idu' => $_SESSION['auth']['id']
            ]);

            header('Location: /accounts');
            return;
        }

        $view = new \App\View\Accounts\Form();
        $view->render([
            'title' => 'Редактор Instagram - аккаунта',
            'data' => $account,
            'messages' => $validator->getMessages()
        ]);
    }

    public function runDelete()
    {
        $pdo = \App\Service\DB::get();

        if (isset($_POST['id'])) {
            $stmt = $pdo->prepare("
                DELETE FROM 
                    `accounts` 
                WHERE 
                    `id` = :id AND `id_user` = :idu
            ");

            $stmt->execute([
                ':id' => $_POST['id'],
                ':idu' => $_SESSION['auth']['id']
            ]);

            header('Location: /accounts');
            return;
        }

        if (!isset($_GET['id'])) {
            header('Location: /accounts');
            return;
        }

        $stmt = $pdo->prepare("
            SELECT
                *
            FROM
                `accounts`
            WHERE
                `id` = :id AND `id_user` = :idu
        ");

        $stmt->execute([
            ':id' => $_GET['id'],
            ':idu' => $_SESSION['auth']['id']
        ]);

        if (!$account = $stmt->fetch()) {
            header('Location: /accounts');
            return;
        }

        $view = new \App\View\Accounts\DeleteForm();
        $view->render([
            'title' => 'Удаление Instagram - аккаунта',
            'account' => $account,
            'url' => [
                'approve' => '/accounts/delete',
                'cancel' => '/accounts'
            ]
        ]);
    }

    private function getValidator($isUpdate = false)
    {
        $validator = new \App\Service\Validator();
        $validator
            ->setRule('login', function ($value) {
                return !is_null($value) && mb_strlen($value) > 0;
            }, 'Это поле обязательно')
            ->setRule('pass', function ($value) {
                return !is_null($value) && mb_strlen($value) > 0;
            }, 'Это поле обязательно');

        return $validator;
    }
}