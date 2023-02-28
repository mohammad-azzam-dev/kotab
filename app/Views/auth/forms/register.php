<div class="form-group">
    <form action="<?php echo base_url('auth/register') ?>" method="POST">
        <div class="img">
            <img class="img-avatar" src="<?php echo base_url('public/assets/auth/img/avatar.svg'); ?>">
        </div>
        <h2><?php echo $page_title; ?></h2>

        <?php if (isset($validation))
        {
            echo $validation->listErrors();
        } ?>
        <?php if (isset($error))
        {
            echo $error;
        } ?>

        <div class="input-group">
            <div class="icon-input-field">
                <i class="material-icons">person</i>
            </div>
            <div>
                <input class="input-field" type="text" name="first_name" required>
                <h5>الإسم الأول</h5>
            </div>
        </div>

        <div class="input-group">
            <div class="icon-input-field">
                <i class="material-icons">person</i>
            </div>
            <div>
                <input class="input-field" type="text" name="last_name" required>
                <h5>الإسم الأخير</h5>
            </div>
        </div>

        <div class="input-group">
            <div class="icon-input-field">
                <i class="material-icons">email</i>
            </div>
            <div>
                <input class="input-field" type="text" name="email" required>
                <h5>البريد الإلكتروني</h5>
            </div>
        </div>
        
        <div class="input-group">
            <div class="icon-input-field">
                <i class="material-icons">https</i>
            </div>
            <div>
                <input class="input-field" type="password" name="password" required>
                <h5>كلمة المرور</h5>
            </div>
        </div>
        
        <div class="input-group">
            <div class="icon-input-field">
                <i class="material-icons">https</i>
            </div>
            <div>
                <input class="input-field" type="password" name="confirm_password" required>
                <h5>تأكيد كلمة المرور</h5>
            </div>
        </div>

        <div>
            <a href="<?php echo base_url('auth/login') ?>" class="auth-link">لدي حساب بالفعل!</a>
        </div>

        <input type="submit" class="btn-main" value="تسجيل" name="register">
    </form>
</div>