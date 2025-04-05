<?php
//begin the session
session_start();

//check if the user is logged in if not redirect to login page
if (!isset($_SESSION['user_name'])) {
  header("Location:login_form.html");
  exit();
}
  //if logged in, obtain the fullname
  $user_email = $_SESSION['user_email'];
  $username = $_SESSION['user_name'];
  $user_id = $_SESSION['user_id'];

?>