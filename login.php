<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
include("include/inc-fuction.php");
if (isset($_POST['username'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $ch = curl_init();
  extract($_REQUEST);
  curl_setopt($ch, CURLOPT_URL,"http://192.168.110.122/employee_authen/check_login.php");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, "username=$username&password=$password");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $server_output = curl_exec ($ch);
  //echo $username."</BR>".$password;
  //$_SESSION=json_decode($server_output,true);
  $result_login = json_decode($server_output,true);
  //echo ">>>>".$server_output." ".$username." ".$password;
  curl_close ($ch);

  if ($result_login == "0") {
    echo "<script type='text/javascript'>alert('ชื่อผู้ใช้ หรือ รหัสผ่าน ไม่ถูกต้อง')</script>";
  }else {
    $conn = connectDB_TSR();

    $sql = "SELECT top 1 permission FROM [TSR_Application].[dbo].[TSS_M_User] WHERE (user_id = '".$username."' OR ad_accountname = '".$username."')";

    $stmt = sqlsrv_query($conn,$sql);
    if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      setcookie("tsr_emp_permit", ereg_replace("A","0",$row['permission']) , time() + (86400 * 30));
      setcookie("tsr_emp_id", ereg_replace("A","0",$result_login['emp_id']) , time() + (86400 * 30));
      setcookie("tsr_emp_name", ereg_replace("A","0",$result_login['displayname']) , time() + (86400 * 30));
      header( "Location:index.php" );
      //echo "aaa";

    }else {
      //setcookie("tsr_emp_permit", ereg_replace("A","0","3") , time() + (86400 * 30));
      echo "<script type='text/javascript'>alert('ชื่อผู้ใช้ หรือ รหัสผ่าน ไม่ถูกต้อง')</script>";
    }
    sqlsrv_close($conn);

    //setcookie("tsr_emp_id", ereg_replace("A","0",$result_login['emp_id']) , time() + (86400 * 30));
    //setcookie("tsr_emp_name", ereg_replace("A","0",$result_login['displayname']) , time() + (86400 * 30));

    //chk_member(ereg_replace("A","0",$result_login['emp_id']));
    //header( "Location:index.php" );
    //echo ereg_replace("A","0",$result_login['emp_id']);
  }

}else {
  setcookie("tsr_emp_id", "", time() - 3600);
  setcookie("tsr_emp_name", "", time() - 3600);
  setcookie("tsr_emp_permit", "", time() - 3600);
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TSR Report | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="login.php"><b>TSS</b>Management</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg"> เข้าสู่ระบบ </p>

    <form action="login.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name="username" placeholder="ชื่อผู้ใช้" required autofocus>
        <span class="glyphicon glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-8">
          <!--
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div
        -->
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">เข้าสู่ระบบ</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="plugins/jQuery/jQuery-2.2.0.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
