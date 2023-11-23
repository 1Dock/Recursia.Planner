<?php

namespace App\Controller;

class Tasks
{
    public function run()
    {
        $pdo = \App\Service\DB::get();
        $stmt = $pdo->prepare("
            SELECT
                `tasks` . *,
                `accounts` . `login`
            FROM
                `tasks`
            LEFT JOIN 
                `accounts`
                ON `tasks` . `id_account` = `accounts` . `id`
        ");

        $stmt->execute();

        $view = new \App\View\Tasks();
        $view->render([
            'title' => 'Список задач',
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
                    `tasks` (
                         `id_user`,
                         `id_account`,
                         `title`,
                         `description`,
                         `date_plan`
                    ) VALUES (
                          :idu,
                          :ida,
                          :title,
                          :desc,
                          :dplan
                    )      
            ");

            $stmt->execute([
                ':idu' => $_SESSION['auth']['id'],
                ':ida' => $_POST['id_account'],
                ':title' => $_POST['title'],
                ':desc' => $_POST['description'],
                ':dplan' => $this->formatDateTime($_POST['date_plan'])
            ]);

            header('Location: /tasks');
            return;
        }

        $view = new \App\View\Tasks\Form();
        $view->render([
            'title' => 'Добавление новои задачи',
            'data' => $_POST,
            'messages' => $validator->getMessages(),
            'accounts' => $this->getUserAccounts()
        ]);
    }

    public function runUpdate()
    {
        if (!isset($_GET['id'])) {
            header('Location: /tasks');
            return;
        }

        $pdo = \App\Service\DB::get();
        $stmt = $pdo->prepare(" 
                SELECT
                    *
                FROM
                    `tasks`
                WHERE
                    `id` = :id AND `id_user` = :idu
            ");

        $stmt->execute([
            ':id' => $_GET['id'],
            ':idu' => $_SESSION['auth']['id']
        ]);

        if (!$task = $stmt->fetch()) {
            header('Location: /tasks');
            return;
        }

        $validator = $this->getValidator(true);
        if ($_POST && $validator->check($_POST)) {
            $stmt = $pdo->prepare("
                UPDATE
                    `tasks`
                SET
                    `id_account` = :ida,
                    `title` = :title,
                    `description` = :desc,
                    `date_plan` = :dplan
                WHERE
                    `id` = :id AND `id_user` = :idu
            ");

            $stmt->execute([
                ':ida' => $_POST['id_account'],
                ':title' => $_POST['title'],
                ':desc' => $_POST['description'],
                ':dplan' => $this->formatDateTime($_POST['date_plan']),
                ':id' => $_GET['id'],
                ':idu' => $_SESSION['auth']['id']

            ]);

            header('Location: /tasks');
            return;
        }

        $view = new \App\View\Tasks\Form();
        $view->render([
            'title' => 'Редактор задачи',
            'data' => $task,
            'messages' => $validator->getMessages(),
            'accounts' => $this->getUserAccounts()
        ]);
    }

    public function runDelete()
    {
        $pdo = \App\Service\DB::get();

        if (isset($_POST['id'])) {
            $stmt = $pdo->prepare("DELETE FROM `tasks` WHERE `id` = :id AND `id_user` = :idu");
            $stmt->execute([
                ':id' => $_POST['id'],
                ':idu' => $_SESSION['auth']['id']
            ]);
            header('Location: /tasks');
            return;
        }
        if (!isset($_GET['id'])) {
            header('Location: /tasks');
            return;
        }

        $stmt = $pdo->prepare("
            SELECT
                *
            FROM
                `tasks`
            WHERE
                `id` = :id AND `id_user` = :idu
        ");
        $stmt->execute([
            ':id' => $_GET['id'],
            ':idu' => $_SESSION['auth']['id']
        ]);

        if (!$tasks = $stmt->fetch()) {
            header('Location: /tasks');
            return;
        }

        $view = new \App\View\Tasks\DeleteForm();
        $view->render([
            'title' => 'Удаление задачи',
            'task' => $tasks,
            'url' => [
                'approve' => '/tasks/delete',
                'cancel' => '/tasks'
            ]
        ]);
    }

    private function getValidator($isUpdate = false)
    {
        $validator = new \App\Service\Validator();
        $validator
            ->setRule('id_account', function ($value) {
                $userAccounts = $this->getUserAccounts();
                $accountsId = [];
                foreach ($userAccounts as $account) {
                    $accountsId[] = $account['id'];
                }
                return !is_null($value) && in_array($value, $accountsId);
            }, 'Неверный аккаунт')
            ->setRule('date_plan', function ($value) {
                return !is_null($value) && preg_match('/^[0-9]{2}\.[0-9]{2}\.[0-9]{4} [0-9]{2}\:[0-9]{2}/', $value);
            }, 'Это поле обязательно и должно соответствовать формату ДД:ММ:ГГГГ чч:мм')
            ->setRule('title', function ($value) {
                return !is_null($value) && mb_strlen($value) > 0;
            }, 'Это поле обязательно')
            ->setRule('description', function ($value) {
                return !is_null($value) && mb_strlen($value) > 0;
            }, 'Это поле обязательно');

        return $validator;
    }

    private function getUserAccounts()
    {
        $pdo = \App\Service\DB::get();
        $stmt = $pdo->prepare("
            SELECT
                *
            FROM
                `accounts`
            WHERE
                `id_user` = :idu
        ");

        $stmt->execute([
            ':idu' => $_SESSION['auth']['id']
        ]);

        return $stmt->fetchAll();
    }

    private function formatDateTime($dateTime)
    {
        $datePlan = new \DateTime($dateTime);
        return $datePlan->format('Y-m-d H:i');
    }
}