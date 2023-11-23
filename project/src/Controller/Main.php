<?php

namespace App\Controller;

class Main
{
    public function run()
    {
        $view = new \App\View\Main();
        $view->render();
    }
}