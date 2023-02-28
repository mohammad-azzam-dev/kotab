<?php if (isset($_SESSION) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'])
{
    if ($page_path != 'forms/change_password')
    {
        if (!array_key_exists('password_changed_at', $_SESSION) || $_SESSION['password_changed_at'] == null)
        {
          header("Location: ".base_url('auth/change-password'));
          exit();
        }
        header("Location: ".base_url('/dashboard'));
        exit();
    }
} ?>

<!DOCTYPE html>
<html dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=devide-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>مدرستي | <?php echo $page_title; ?></title>
        <!-- Custom fonts for this template -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
        <!-- Bootstrap -->
        <!--<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">-->
        <!-- Fonts and icons -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <!-- Custom styles for this template -->
        <link href="<?php echo base_url('public/assets/auth/css/style.css'); ?>" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="hero-auth">
            <img class="wave" src="">
            <div class="container">
                <div class="img img-auth">
                    <img src="<?php echo base_url('public/assets/auth/img/login_2.svg'); ?>">
                </div>

                <!-- Include Corresponding Auth Form -->
                <?php include($page_path.'.php'); ?>

            </div>
        </div>
    </body>
</html>