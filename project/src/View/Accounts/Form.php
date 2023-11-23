<?php

namespace App\View\Accounts;

class Form extends \App\View\Main {
    public function content (array $data = []) {
        $isNew = !isset($data['data']['id']);
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal push-10-t" action="<?= $isNew ? '/accounts/add' : '/accounts/update?id=' . $data['data']['id'] ?>" method="post">
                            <div class="form-group <?= isset($data['messages']['login']) ? 'has-error' : '' ?>">
                                <div class="col-sm-9">
                                    <div class="form-material">
                                        <input class="form-control" type="text" id="material-login" name="login" placeholder="Введите логин" value="<?= $data['data']['login'] ?? '' ?>">
                                        <label for="material-login">Логин</label>
                                        <?php if (isset($data['messages']['login'])): ?>
                                            <div class="help-block text-right"><?= $data['messages']['login'] ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?= isset($data['messages']['pass']) ? 'has-error' : '' ?>">
                                <div class="col-sm-9">
                                    <div class="form-material">
                                        <input class="form-control" type="password" id="material-password" name="pass" placeholder="Введите пароль" value="<?= $data['data']['pass'] ?? '' ?>">
                                        <label for="material-password">Пароль</label>
                                        <?php if (isset($data['messages']['pass'])): ?>
                                            <div class="help-block text-right"><?= $data['messages']['pass'] ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-9">
                                    <button class="btn btn-sm btn-primary" type="submit"><?= $isNew ? 'Создать' : 'Сохранить' ?></button>
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