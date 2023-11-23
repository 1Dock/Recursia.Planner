<?php

namespace App\View\Tasks;

class DeleteForm extends \App\View\Main {
    public function content (array $data = []) {
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal push-10-t" action="<?= $data['url']['approve'] ?>" method="post">
                            <input type="hidden" name="id" value="<?= $data['task']['id'] ?>">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="alert alert-danger alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h3 class="font-w300 push-15"><?= $data['title'] ?></h3>
                                        <p>Вы действительно хотите удалить задачу <a class="alert-link" href="javascript:void(0)"><?= $data['task']['title'] ?></a>?</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9">
                                    <button class="btn btn-sm btn-primary" type="submit">Удалить</button>
                                    <a class="btn btn-sm btn-default" href="<?= $data['url']['cancel'] ?>">Отмена</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}