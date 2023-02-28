<header class="frontend-header">
    <div class="container">
        <nav class="frontend-nav">
            <div class="nav-brand">
                <a href="index.html">
                    <img src="{{asset('storage/frontend/img/logo.png')}}" alt="">
                </a>
            </div>

            <div class="menu-icons open">
                <i class="material-icons" onClick="toggle_sidebar();">menu</i>
            </div>

            <ul id="nav-list" class="nav-list">
                <li class="nav-item">
                    <a href="#" class="nav-link current">الصفحة الرئيسية</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">عنا</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">تواصل معنا</a>
                </li>

                
                <!-- START: Authentication Links -->
                <?php if (!isset($_SESSION) || !array_key_exists('logged_in', $_SESSION) || (array_key_exists('logged_in', $_SESSION) && !$_SESSION['logged_in'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('auth/login'); ?>">تسجيل الدخول</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo base_url('auth/register'); ?>">تسجيل كعضو جديد</a>
                    </li>
                <?php elseif ($_SESSION['logged_in']): ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('dashboard'); ?>" class="nav-link">لوحة التحكم</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url('auth/logout'); ?>" class="nav-link">
                            تسجيل الخروج
                        </a>
                    </li>
                <?php endif; ?>
                <!-- END: Authentication Links -->

            </ul>
        </nav>
    </div>
</header>