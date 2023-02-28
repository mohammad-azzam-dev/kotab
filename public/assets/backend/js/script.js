function toggle_sidebar() {
  var element = document.getElementById("sidebar");
  element.classList.toggle("active");

  var element = document.getElementById("main-panel");
  element.classList.toggle("active");
}