<div class="form-group">
    <form action="<?php echo base_url('auth/change-password') ?>" method="POST">
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
                <i class="fas fa-user"></i>
            </div>
            <div>
                <input class="input-field" type="password" name="current_password" required>
                <h5>كلمة المرور الحالية</h5>
            </div>
        </div>

        <div class="input-group">
            <div class="icon-input-field">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <input class="input-field" type="password" name="new_password" required>
                <h5>كلمة المرور الجديدة</h5>
            </div>
        </div>

        <div class="input-group">
            <div class="icon-input-field">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <input class="input-field" type="password" name="confirm_new_password" required>
                <h5>تأكيد كلمة المرور الجديدة</h5>
            </div>
        </div>

        <div>
            <a href="<?php echo base_url('auth/logout') ?>" class="auth-link">تسجيل الخروج</a>
        </div>

        <input type="submit" class="btn-main" value="إرسال" name="change_password">
    </form>
</div>