<?php

namespace App\View\Users;

class Form extends \App\View\Main {
    public function content (array $data = []) {
        $isNew = !isset($data['data']['id']);
        ?>
        <div class="row">
            <div class="col-lg-12">
                <div class="block">
                    <div class="block-content block-content-narrow">
                        <form class="form-horizontal push-10-t" action="<?= $isNew ? '/users/add' : '/users/update?id=' . $data['data']['id'] ?>" method="post">
                            <div class="form-group <?= isset($data['messages']['email']) ? 'has-error' : '' ?>">
                                <div class="col-sm-9">
                                    <div class="form-material">
                                        <input class="form-control" type="email" id="material-email" name="email" placeholder="Введите Email" value="<?= $data['data']['email'] ?? '' ?>">
                                        <label for="material-email">Email</label>
                                        <?php if (isset($data['messages']['email'])): ?>
                                            <div class="help-block text-right"><?= $data['messages']['email'] ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?= isset($data['messages']['pass']) ? 'has-error' : '' ?>">
                                <div class="col-sm-9">
                                    <div class="form-material">
                                        <input class="form-control" type="password" id="material-password" name="pass" placeholder="Введите пароль">
                                        <label for="material-password">Пароль</label>
                                        <?php if (isset($data['messages']['pass'])): ?>
                                            <div class="help-block text-right"><?= $data['messages']['pass'] ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?= isset($data['messages']['conf-pass']) ? 'has-error' : '' ?>">
                                <div class="col-sm-9">
                                    <div class="form-material">
                                        <input class="form-control" type="password" id="material-password" name="conf-pass" placeholder="Подтвердите пароль">
                                        <label for="material-password">Подтверждение Пароля</label>
                                        <?php if (isset($data['messages']['conf-pass'])): ?>
                                            <div class="help-block text-right"><?= $data['messages']['conf-pass'] ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?= isset($data['messages']['name']) ? 'has-error' : '' ?>">
                                <div class="col-sm-9">
                                    <div class="form-material">
                                        <input class="form-control" type="text" id="material-text" name="name" placeholder="Введите имя" value="<?= $data['data']['name'] ?? '' ?>">
                                        <label for="material-text">Имя</label>
                                        <?php if (isset($data['messages']['name'])): ?>
                                            <div class="help-block text-right"><?= $data['messages']['name'] ?></div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group <?= isset($data['messages']['privilege']) ? 'has-error' : '' ?>">
                                <div class="col-sm-9">
                                    <div class="form-material">
                                        <select class="form-control" id="material-select" name="privilege" size="1">
                                            <option value="0">Менеджер</option>
                                            <option value="1">Администратор</option>
                                        </select>
                                        <label for="material-select">Привилегия</label>
                                        <?php if (isset($data['messages']['privilege'])): ?>
                                            <div class="help-block text-right"><?= $data['messages']['privilege'] ?></div>
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