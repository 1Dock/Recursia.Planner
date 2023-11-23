<?php

namespace App\View;

class Accounts extends Main {
    public function content(array $data)
    {
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-content">
                        <div class="pull-right">
                            <a class="btn btn-info push-10" href="/accounts/add"><i class="fa fa-pencil"></i></a>
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
            'login' => [
                'label' => 'Логин',
                'class' => '',
                'style' => ''
            ],
            'table-action' => [
                'label' => 'Действие',
                'class' => 'text-center',
                'style' => 'width: 200px',
                'buttons' => [
                    'update' => [
                        'icon' => 'fa fa-pencil',
                        'url' => '/accounts/update'
                    ],
                    'delete' => [
                        'icon' => 'fa fa-trash',
                        'url' => '/accounts/delete'
                    ]
                ]
            ]
        ];
    }
}