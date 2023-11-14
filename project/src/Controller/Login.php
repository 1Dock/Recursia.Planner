<?php

namespace App\Controller;

class Login {
    public function run () {
        $message = null;
        if (isset($_POST['email'], $_POST['pass'])) {
            $pdo = \App\Service\DB::get();

            $stmt = $pdo->prepare("
                SELECT
                    *
                FROM
                    `users`
                WHERE
                    `email` = :email AND `pass` = :pass
            ");
            $result = $stmt->execute([
                ':email' => $_POST['email'],
                ':pass' => sha1($_POST['pass'])
            ]);

            if ($user = $stmt->fetch()) {
                $_SESSION['auth'] = $user;
                header('Location: /');
                return;
            } else {
                $message = 'Вы ввели не неверные данные, пожалуйста перепроверьте и попробуйте снова!';
            }
        }

        $view = new \App\View\Login();
        $view->render([
            'title' => 'Авторизация',
            'message' => $message
        ]);
    }

    public function runLogout () {
        unset($_SESSION['auth']);
        header('Location: /');
    }
}