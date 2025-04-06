<?php
session_start();

session_unset();
session_destroy();

echo "<script type='text/javascript'>alert('Log out');</script>";
echo "<script>window.location.href = 'index.php';</script>";
exit;
?>
