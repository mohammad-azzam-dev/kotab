  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo base_url('/'); ?>" class="nav-link">الصفحة الرئيسية</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <!--<form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>-->

    <!-- Right navbar links -->
    <?php /*<ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>
    </ul> */ ?>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <!--<img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">-->
      <span class="brand-text font-weight-light">مدرستي</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!--<img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">-->
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $_SESSION['first_name'].' '.mb_substr($_SESSION['last_name'], 0, 2).'.'; ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->
            <?php if (isRole($_SESSION['id'], 'admin')): ?>
              <li class="nav-item">
                  <a href="<?php echo base_url('dashboard') ?>" class="nav-link {{(request()->is('dashboard')) ? 'active' : '' }}">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>لوحة التحكم</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="<?php echo base_url('dashboard/users') ?>" class="nav-link {{(request()->is('dashboard')) ? 'active' : '' }}">
                      <i class="nav-icon fas fa-users"></i>
                      <p>المستخدمين</p>
                  </a>
              </li>
            <?php endif; ?>

            <li class="nav-item has-treeview {{(request()->is('')) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{(request()->is('')) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-university"></i>
                    <p>
                        آكاديمي
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                  <?php if (isRole($_SESSION['id'], 'admin')): ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('dashboard/classes/all-classes') ?>" class="nav-link {{(request()->is('posts')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>كل الصفوف</p>
                        </a>
                    </li>
                  <?php endif; ?>
                  <?php if (isRole($_SESSION['id'], 'student')): ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('dashboard/classes/my-classes') ?>" class="nav-link {{(request()->is('posts')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>صفوفي</p>
                        </a>
                    </li>
                  <?php endif; ?>
                  <?php if (isRole($_SESSION['id'], 'parent')): ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('dashboard/classes/my-children-classes') ?>" class="nav-link {{(request()->is('posts')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>صفوف أولادي</p>
                        </a>
                    </li>
                  <?php endif; ?>
                  <?php if (isRole($_SESSION['id'], 'instructor')): ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('dashboard/classes/instructor-classes') ?>" class="nav-link {{(request()->is('posts')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>الصفوف التي أدرسها</p>
                        </a>
                    </li>
                  <?php endif; ?>
                  <?php if (isRole($_SESSION['id'], 'admin')): ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url('dashboard/majors') ?>" class="nav-link {{(request()->is('classes')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>الإختصاصات</p>
                        </a>
                    </li>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url('dashboard/courses') ?>" class="nav-link {{(request()->is('classes')) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>المواد</p>
                        </a>
                    </li>
                  <?php endif; ?>
                </ul>
            </li>
        </ul>
    </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>






