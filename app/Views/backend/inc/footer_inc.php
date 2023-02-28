

<div class="container-fluid">
<nav class="float-left">
  <ul>
    <li>
      <a href="https://www.saidadawah.com">
        مدرستي
      </a>
    </li>
  </ul>
</nav>
<div class="copyright float-right">
  &copy;
  <script>
    document.write(new Date().getFullYear())
  </script> - 
  <a href="https://saidadawah.com" target="_blank">مدرستي</a>
</div>



<?php if (session()->getFlashdata('error') != ''): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function(event) {
      // Your code to run since DOM is loaded and ready
      _notify('danger', '<?php print_r(str_replace(array("\n", "\r"), '', session()->getFlashdata("error"))); ?>');
    });
  </script>
<?php elseif (session()->getFlashdata('success') != ''): ?>
  <script>
    document.addEventListener("DOMContentLoaded", function(event) {
      // Your code to run since DOM is loaded and ready
      _notify('success', '<?php print_r(str_replace(array("\n", "\r"), '', session()->getFlashdata("success"))); ?>');
    });
  </script>
<?php endif; ?>