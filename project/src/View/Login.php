<?php

namespace App\View;

class Login extends Base {
    public function container (array $data) {
        ?>
        <div class="bg-white pulldown">
            <div class="content content-boxed overflow-hidden">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                        <div class="push-30-t push-50 animated fadeIn">
                            <div class="text-center push-20">
                                <i class="fa fa-3x fa-paper-plane text-primary"></i>
                                <h3 class="text-muted">Recursia.Planner</h3>
                            </div>

                            <?php if (isset($data['message'])): ?>
                                    <div class="alert alert-warning alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <p><?= $data['message'] ?? '' ?></p>
                                    </div>
                            <?php endif; ?>

                            <form class="js-validation-login form-horizontal push-30-t" action="/login" method="post">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-primary floating">
                                            <input class="form-control" type="text" id="login-username" name="email">
                                            <label for="login-username">Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <div class="form-material form-material-primary floating">
                                            <input class="form-control" type="password" id="login-password" name="pass">
                                            <label for="login-password">Пароль</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="font-s13">
                                            <a href="base_pages_reminder_v2.html">Забыли пароль?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group push-30-t">
                                    <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                                        <button class="btn btn-sm btn-block btn-primary" type="submit">Войти</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pulldown push-30-t text-center animated fadeInUp">
            <small class="text-muted"><span class="js-year-copy"></span> &copy; OneUI 3.4</small>
        </div>
        <?php
    }
}