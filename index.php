<?php
//session_start();
include_once("include/inc-fuction.php");
if (!empty($_GET['empid'])) {
  header( "Location:login2.php?empid=".$_GET['empid'] );
  break;
}
check_login($_COOKIE['tsr_emp_id']);

 ?>
<!DOCTYPE html>
<html>
<head>
<?php include_once("include/inc-header-2.php"); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <?php include_once("analyticstracking.php") ?>

<div class="wrapper">
  <?php include_once("include/inc-top-menu.php"); ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php include_once("include/inc-side-menu.php"); ?>
  <!-- Content Wrapper. Contains page content -->
  <?php include_once("pages/main.php"); ?>
  <!-- /.content-wrapper -->
  <?php include_once("pages/footer.php"); ?>
  <!-- Control Sidebar -->
  <?php //include_once("pages/control-sidebar.php"); ?>
  <!-- /.control-sidebar -->
</div>
<?php include_once("include/inc-footer.php"); ?>
</body>
</html>
