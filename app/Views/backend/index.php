<?php if (!isset($_SESSION) || !array_key_exists('logged_in', $_SESSION) || (array_key_exists('logged_in', $_SESSION) && !$_SESSION['logged_in']))
{
  header("Location: ".base_url('/'));
  exit();
}
elseif (!array_key_exists('password_changed_at', $_SESSION) || $_SESSION['password_changed_at'] == null)
{
  header("Location: ".base_url('auth/change-password'));
  exit();
} ?>

<!-- Under Development -->
<?php /*if (!isRole($_SESSION['id'], 'admin'))
{
    echo "الموقع قيد التطوير حاليا يرجى العودة لاحقا";
    exit();
} */?>

<!doctype html>
<html lang="ar" dir="rtl">

<head>
  <title>مدرستي | <?php echo $page_title; ?></title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <!-- Material Kit CSS -->
  <link href="<?php echo base_url('public/assets/backend/material-dashboard/css/material-dashboard.css'); ?>" rel="stylesheet" />
  <link href="<?php echo base_url('public/assets/backend/material-dashboard/css/material-dashboard-rtl.css'); ?>" rel="stylesheet" />
  <!-- Custom Style -->
  <link href="<?php echo base_url('public/assets/common/css/style.css'); ?>" rel="stylesheet" />
  <!-- Bootstarp Select -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/2.2.5/css/dataTables.bootstrap.min.css" />
  <!-- jQuery UI -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>

<body>
  <div class="wrapper ">
    <div id="sidebar" class="sidebar" data-color="purple" data-background-color="white">
      <!-- Sidebar -->
      <?php include('inc/sidebar.php'); ?>
      <!-- End Sidebar -->
    </div>
    <div id="main-panel" class="main-panel">
      <!-- Navbar -->
      <?php include('inc/navbar.php'); ?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <!-- your content here -->
          <?php include($page_path.'.php'); ?>
        </div>
      </div>
      <footer class="footer">
        <!--<div class="container-fluid">
          <nav class="float-left">
            <ul>
              <li>
                <a href="https://www.creative-tim.com">
                  Creative Tim
                </a>
              </li>
            </ul>
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by
            <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a> for a better web.
          </div>-->
          <!-- your footer here -->
          <?php include('inc/footer_inc.php'); ?>
        </div>
      </footer>
    </div>
  </div>
</body>




<!-- Include Modals -->
<?php if (isset($modals_path)) include($modals_path.'.php'); ?>
<?php include('inc/common_modals.php'); ?>


<!-- jQuery -->
<script src="<?php echo base_url('public/assets/backend/material-dashboard/js/core/jquery.min.js'); ?>"></script>
<!-- Proper -->
<script src="<?php echo base_url('public/assets/backend/material-dashboard/js/core/popper.min.js'); ?>"></script>
<!-- Popper.JS -->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
-->
<!-- Bootstrap 4 -->
<script src="<?php echo base_url('public/assets/backend/material-dashboard/js/core/bootstrap-material-design.min.js'); ?>"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo base_url('public/assets/common/js/bootstrap-select.min.js'); ?>"></script>
<!-- Notification -->
<script src="<?php echo base_url('public/assets/backend/material-dashboard/js/plugins/bootstrap-notify.js'); ?>"></script>
<!-- DataTables -->
<script src="<?php echo base_url('public/assets/backend/material-dashboard/js/plugins/jquery.dataTables.min.js'); ?>"></script>
<!-- Bootstrap MultiSelect -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.13/dist/js/bootstrap-select.min.js"></script>
<!-- Custom Page Scripts -->
<?php if (isset($scripts_path)) include($scripts_path.'.php'); ?>

<!-- REQUIRED SCRIPTS -->
<?php include('inc/common_scripts.php'); ?>



<script src="<?php echo base_url('public/assets/backend/js/script.js'); ?>"></script>


</html>