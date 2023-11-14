<?php

namespace App\View;

class Users extends Main {
    public function content(array $data)
    {
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-content">
                        <div class="pull-right">
                            <a class="btn btn-info push-10" href="/users/add"><i class="fa fa-pencil"></i></a>
                        </div>
                        <?= $this->table($this->getColumns(), $data['data']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private function getColumns() {
        return [
            'id' => [
                'label' => '#',
                'class' => 'text-center',
                'style' => 'width: 50px'
            ],
            'email' => [
                'label' => 'Email пользователя',
                'class' => '',
                'style' => ''
            ],
            'name' => [
                'label' => 'Имя пользователя',
                'class' => '',
                'style' => ''
            ],
            'privilege' => [
                'label' => 'Привилегия',
                'class' => '',
                'style' => ''
            ]
        ];
    }
}