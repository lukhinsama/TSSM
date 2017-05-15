<!DOCTYPE html>
<html>
<?php session_start(); include("header.php");
include("../inc-fuction.php");
/*
if(!isset($_SESSION['username'])){
    echo "<SCRIPT type='text/javascript'> //not showing me this
        alert('กรุณาลงชื่อเข้าระบบก่อนเข้าใช้งาน');
        window.location.replace(\"../login.php\");
    </SCRIPT>";
}
*/
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<body class="hold-transition skin-blue sidebar-mini fixed">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>T</b>SR</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>TSR</b>Theinsurat</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="dist/img/useravatar.png" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="dist/img/useravatar.png" class="img-circle" alt="User Image">

                                <p>
                                    <?php echo $_SESSION['username']; ?>
                                    <small>Member since Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <!-- <div class="pull-left">
                                     <a href="#" class="btn btn-default btn-flat">Profile</a>
                                 </div>-->
                                <div class="pull-right">
                                    <a href="../logout.php" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="dist/img/useravatar.png" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p> <?php echo $_SESSION['username']; ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>
            <!-- search form -->
            <form action="#" method="get" class="sidebar-form">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
                </div>
            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">MAIN NAVIGATION</li>
                <li class="active treeview">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>ขอสินเชื่อ</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="active"><a href="index.php"><i class="fa fa-circle-o"></i> แบบประเมินผู้ขอสินเชื่อ</a></li>
                        <?php $status =  $_SESSION['acc_sta'];
                        if($status != 1) {
                            ?>
                            <li><a href="index2.php"><i class="fa fa-circle-o"></i> รายงานผู้ขอสินเชื่อ</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                แบบประเมินผู้ขอสินเชื่อ
                <!-- <small>Control panel</small>-->
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">ผู้ขอสินเชื่อ</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <!-- quick email widget -->
                    <form class="form-horizontal" name ="burgz" action="resultass.php" method="post">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <i class="fa fa-envelope"></i>

                            <h3 class="box-title">ข้อมูลส่วนตัวผู้ประเมิน</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
                            </div>
                            <?php
                            $conn = connectDB();
                            $idcard = $_POST['idcard'];

                            echo '<br>';
                            $strSQL = "SELECT * FROM [Asses_borrower] WHERE [card_id]='".$idcard."'";
                            $objQuery = sqlsrv_query($conn, $strSQL) or die (sqlsrv_errors($conn,$strSQL));
                            $displayTitle = sqlsrv_fetch_array($objQuery);
                             $displayTitle['borrowerId'];

                                if (empty($displayTitle['card_id'])) {
                                    $sql = "INSERT INTO [Asses_borrower] ( bor_name, bor_sure, card_id,jobid) VALUES ( '".$_POST['name']."', '".$_POST['surename']."', '".$_POST['idcard']."', '".$_POST['jobOption']."')";
                                    $objQuery2 =  sqlsrv_query($conn, $sql);
                             $strSQL = "SELECT * FROM [Asses_borrower] WHERE [card_id]='".$idcard."'";
                            $objQuery = sqlsrv_query($conn, $strSQL) or die (sqlsrv_errors($conn,$strSQL));
                            $displayTitle = sqlsrv_fetch_array($objQuery);
                                    echo '<input type="hidden" name="borrowerId" value="'. $displayTitle["borrowerId"].'" id="borrowerId" />';

                                }else{
                                    echo '<input type="hidden" name="borrowerId" value="'. $displayTitle["borrowerId"].'" id="borrowerId" />';
                                    //echo '<script>';
                                  //  echo 'alert("รหัสบัตรประชาชนนี้มีอยู่แล้ว!");';
                                   // echo 'location.href="ratecredit2.php"';
                                   // echo '</script>';
                                }
                            if($_POST["name"]!=''){
                            echo'&nbsp;&nbsp;&nbsp;';   echo $_POST["name"]; echo'&nbsp;&nbsp;&nbsp;'; echo $_POST["surename"]; echo '&nbsp; &nbsp; '; echo $_POST["idcard"]; echo '&nbsp; &nbsp; ';
                                $jobid = $_POST["jobOption"];
                                if ($conn){
                                    if(($result = sqlsrv_query($conn,"SELECT * FROM [Asses_job] WHERE jobid='$jobid' ")) !== false){
                                        while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                                            echo ''.$row['jobname'].'';
                                        }
                                    }
                                }else{
                                    die(print_r(sqlsrv_errors(), true));
                                }
                            }else{
                                echo "<SCRIPT type='text/javascript'>
              alert('ทำรายการไม่ถูกต้อง');
              window.location.replace(\"index.php\");
          </SCRIPT>";
                            }
                            ?>
                            <!-- tools box -->
                            <!-- <div class="pull-right box-tools">
                               <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                                    <i class="fa fa-times"></i></button>
                            </div>
                            <!-- /. tools -->
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <?php

                                $jobid = $_POST["jobOption"];
                                if ($conn){
                                    if(($result = sqlsrv_query($conn,"SELECT * FROM [Asses_category]")) !== false){
                                        while($row4=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
                                            $projects[] = $row4;
                                        }
                                        $count = 0;
                                        foreach ($projects as $key ) {
                                            //generate output
                                            $idcc= $key['cate_id'];
                                            echo ' <div class="form-group">
                                <label for="OT" class="col-sm-2 control-label"></label>
                                <div class="col-sm-4">';
                                            echo '<h4>'.$key['cate_name'].'</h4>';
                                            echo '</div></div>';
                                            $count++;
                                            $counter = 0;
                                            $sql = sqlsrv_query($conn, "SELECT * FROM [Asses_question] where cate_id='$idcc' and jobid='$jobid'");
                                            while ($row = sqlsrv_fetch_array($sql, SQLSRV_FETCH_ASSOC)){
                                                $counter++;
                                                echo ' <div class="form-group">
                                <label for="OT" class="col-sm-2 control-label"></label>
                                <div class="col-sm-4">';
                                                echo '<h5>'.$count.'.'.$counter.'.&nbsp;&nbsp;'.$row['quest_name'].'</h5>';
                                                echo '</div></div>';
                                                $idq = $row['ques_id'];
                                                //echo '<input type="hidden" name="question[]" value="'.$row["ques_id"].'" id="$idq" />';
                                                echo '<input type="hidden" name="size['.$idq.']" value="" id="ans_t2_'.$idq.'" />';
                                                $sql2 = sqlsrv_query($conn, "SELECT * FROM [Asses_ans] JOIN [TSR_Application].[dbo].[Asses_question] on [Asses_question].ques_id=[Asses_ans].questid WHERE [Asses_ans].questid='$idq'");
                                                while ($row2 = sqlsrv_fetch_array($sql2, SQLSRV_FETCH_ASSOC)){
                                                    $idans =   $row2['ans_id'];
                                                    $idqust =   $row2['questid'];
                                                    echo ' <div class="form-group">
                                <label for="OT" class="col-sm-2 control-label"></label>
                                <div class="radio col-sm-6"> <label>';
                                                    echo "<input required ".(($idqust=='31' ||$idqust=='32' ||$idqust=='33' || $idqust=='43'|| $idqust=='44'|| $idqust=='45')? 'disabled' : '')."  type='radio'  value='".$row2['score']."' id='$idans' name='size2[$idq]' onchange=\"document.getElementById('ans_t2_$idq').value='".$row2['ans_id']."'\"> ".$row2['ans_name']."";

                                                    // echo '<input type="hidden" name="ans[]" value="'.$row2["ans_id"].'" id="$idq" />';
                                                    echo '</label></div></div>';
                                                }
                                            }
                                        }
                                    }
                                }else{
                                    die(print_r(sqlsrv_errors(), true));
                                }
                                ?>
                                <div class="form-group">
                                    <label for="OT" class="col-sm-4 control-label">Total Ans</label>

                                    <div class="col-sm-6">
                                        <input type="text" name="totalSum" id="totalSum" value="0" readonly="readonly">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="box box-default">

                            <div class="box-header with-border">
                                <h3 class="box-title">ข้อมูลส่วนตัวผู้ขอสินเชื่อ</h3>
                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                    <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>-->
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="salary" class="col-sm-4 control-label">เงินเดือนพื้นฐาน</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" id="v0" name="v0" placeholder="เงินเดือนพื้นฐาน"  onkeyup="calculate1()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="OT" class="col-sm-4 control-label">OT + Commission</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" name="commit" id="v1" placeholder="OT + Commission"  onkeyup="calculate1()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="totalincome" class="col-sm-4 control-label">รายได้รวม (เงินเดือนพื้นฐาน + OT + Commission )</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" name="sumincome" id="s0"  placeholder="รายได้รวม" readonly ">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">ค่าใช้จ่าย 1</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" name="p0" id="p0" placeholder="ค่าใช้จ่าย 1" onkeyup="calculate2()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">ค่าใช้จ่าย 2</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" name="p1" id="p1" placeholder="ค่าใช้จ่าย 2" onkeyup="calculate2()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">ค่าใช้จ่าย 3</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" name="p2" id="p2" placeholder="ค่าใช้จ่าย 3" onkeyup="calculate2()">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">ค่าใช้จ่ายรวม ( 1 + 2 + 3)</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" name="sumpay" id="s1" placeholder="่ค่าใช้จ่ายรวม" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">รายได้สุทธิ</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" onkeyup="calculate4()" readonly name="netincome" id="netincome" placeholder="รายได้สุทธิ">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="ยอดผ่านต่อเดือน" class="col-md-7 control-label"> <font color = "#0000e6">ยอดผ่อนต่อเดือน</font></label>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">จำนวนเงินกู้ (เงินต้น)</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" name="Amountloan" id="a1" onkeyup="calculate4()" placeholder="จำนวนเงินกู้">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">ระยะเวลาผ่อน</label>

                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" required pattern="\d{1,13}" name="timepay" id="a2" onkeyup="calculate4()" placeholder="ระยะเวลาผ่อน">
                                        </div>  <label for="inputPassword3" class="col-sm-1 control-label">เดือน</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">ดอกเบี้ยและค่าธรรมเนียม</label>

                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" required pattern="\d{1,13}"  onkeyup="calculate4()" name="fee" id="a3" placeholder="ดอกเบี้ยและค่าธรรมเนียม">
                                        </div>  <label for="inputPassword3" class="col-sm-2 control-label">% ต่อเดือน</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">ยอดผ่อนต่อเดือน</label>
                                        <div class="col-sm-3">
                                            <input type="text" readonly class="form-control" name="paymonth" id="a4" placeholder="ยอดผ่อนต่อเดือน">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputPassword3" class="col-sm-4 control-label">ค่างวดการผ่อนชำระต่อเดือนรายได้สุทธิ</label>

                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" readonly name="sumpercent" id="sumpercent" placeholder="ค่างวดการผ่อนชำระต่อเดือนรายได้สุทธิ">
                                        </div>  <label for="inputPassword3" required pattern="\d{1,7}"  class="col-sm-2 control-label"></label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ยอดผ่านต่อเดือน" class="col-md-6 control-label"> <font color = "#FF0000">Rate Book</font></label>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-4 control-label">ยอดขอกู้</label>

                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" required pattern="\d{1,13}" name="rate1" id="rate1" onkeyup="calculate5()" placeholder="ยอดขอกู้">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-4 control-label">Rate Book ที่จัดได้</label>

                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" required pattern="\d{1,13}" name="rate2" id="rate2" onkeyup="calculate5()" placeholder="Rate Book ที่จัดได้">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword3" class="col-sm-4 control-label">จำนวนกู้เทียบ Rate Book ที่จัดได้</label>

                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" required pattern="\d{1,13}" name="rate3" id="rate3" readonly placeholder="จำนวนกู้เทียบ Rate Book ที่จัดได้">
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix">

                                <button type="submit" class="pull-left btn btn-default" id="senddata">Back
                                    <i class="fa fa-arrow-circle-left"></i></button>
                                <button type="submit" class="pull-right btn btn-success"  value="submit" id="submit" name="submit">Send
                                    <i class="fa fa-arrow-circle-right"></i></button>
                            </div>
                            <!-- /.box-footer -->
                    </div>
                    </form>
                </section>
            </div>
            <!-- /.row (main row) -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.3.8
        </div>
        <strong>Copyright &copy; 2014-2016 <a href="http://almsaeedstudio.com">3ewJiz</a>.</strong> All rights
        reserved.
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <!--  <h3 class="control-sidebar-heading">Recent Activity</h3>
                  <ul class="control-sidebar-menu">
                      <li>
                          <a href="javascript:void(0)">
                              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                              <div class="menu-info">
                                  <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                  <p>Will be 23 on April 24th</p>
                              </div>
                          </a>
                      </li>
                      <li>
                          <a href="javascript:void(0)">
                              <i class="menu-icon fa fa-user bg-yellow"></i>

                              <div class="menu-info">
                                  <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                  <p>New phone +1(800)555-1234</p>
                              </div>
                          </a>
                      </li>
                      <li>
                          <a href="javascript:void(0)">
                              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                              <div class="menu-info">
                                  <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                  <p>nora@example.com</p>
                              </div>
                          </a>
                      </li>
                      <li>
                          <a href="javascript:void(0)">
                              <i class="menu-icon fa fa-file-code-o bg-green"></i>

                              <div class="menu-info">
                                  <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                  <p>Execution time 5 seconds</p>
                              </div>
                          </a>
                      </li>
                  </ul>
                  <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Update Resume
                                <span class="label label-success pull-right">95%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Laravel Integration
                                <span class="label label-warning pull-right">50%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Back End Framework
                                <span class="label label-primary pull-right">68%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Allow mail redirect
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Other sets of options are available
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Expose author name in posts
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Allow the user to show his name in blog posts
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Show me as online
                            <input type="checkbox" class="pull-right" checked>
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Turn off notifications
                            <input type="checkbox" class="pull-right">
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Delete chat history
                            <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                        </label>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<?php
include("footer.php");
?>
<script type="text/javascript">
    $("input[type=radio]").change(function() {
        var total = 0;
        $("input[type=radio]:checked").each(function() {

            total += parseFloat($(this).val());
        });

        $("#totalSum").val(total);
    });

</script>
<script type="text/javascript">
    //passive
    var g1;
    var g2;
    //document.get
    function calculate1(){
        var result = document.getElementById('s0');
        var el, i = 0, total = 0;
        while(el = document.getElementById('v'+(i++)) ) {
            el.value = el.value.replace(/\\D/,"");
            total = total + Number(el.value);
        }
        result.value = total;
        if(document.getElementById('v0').value =="" && document.getElementById('v1').value =="" ){
            result.value ="";
        }
        g1 = result.value;
        if(isNaN(parseInt(g1))){
            alert("Please only enter numeric characters only! (Allowed input:0-9)");
            result.value ="0";
        }
        calculate3();
        return result.value;
    }
    //pay
    function calculate2(){
        var result = document.getElementById('s1');
        var el, i = 0, total = 0;
        while(el = document.getElementById('p'+(i++)) ) {
            el.value = el.value.replace(/\\D/,"");
            total = total + Number(el.value);
        }
        result.value = total;
        if(document.getElementById('p0').value =="" && document.getElementById('p1').value ==""&& document.getElementById('p2').value =="" ){
            result.value ="";
        }
        g2 = result.value;
        if(isNaN(parseInt(g2))){
            alert("Please only enter numeric characters only! (Allowed input:0-9)");
            result.value ="0";
        }
        calculate3();
        return result.value;
    }
    function calculate3(){
        //document.getElementById('netincome').value=document.getElementById('s0').value+document.getElementById('s1').value;
        var result3 = document.getElementById('netincome');
        //var cal1= calculate1();
        //var cal2= calculate2();
//result3 = cal1 + cal2;
        if(isNaN(parseInt(g1))){
            result3.value ="0";
        }else if(isNaN(parseInt(g2))){
            result3.value ="0";
        }else{
            result3.value = parseInt(g1) - parseInt(g2);
        }
    }
    function calculate4(){
        var a1 = document.getElementById('a1').value;
        var a2 = document.getElementById('a2').value;
        var a3 = document.getElementById('a3').value;
        var summ = document.getElementById('netincome').value;
        //result_a4.value = parseFloat(((parseInt(a1) * parseInt(a2) * (parseInt(a3)/100)+ parseInt(a1))/ parseInt(a2))/ parseInt(summ)*100).toFixed(2);
        var result_a4 = document.getElementById('a4');
        var result_sum = document.getElementById('sumpercent');
        if(isNaN(parseInt(a1))){
            result_a4.value ="0";
            result_sum.value ="0";
        }else if(isNaN(parseInt(a2))){
            result_a4.value ="0";
            result_sum.value ="0";
        }else if(isNaN(parseInt(a3))){
            result_a4.value ="0";
            result_sum.value ="0";
        }else if(isNaN(parseInt(summ))){
            result_sum.value ="0";
            result_a4.value = parseFloat((parseInt(a1) * parseInt(a2) * (parseInt(a3)/100)+ parseInt(a1))/ parseInt(a2)).toFixed(2);
        }
        else{
            result_a4.value = parseFloat((parseInt(a1) * parseInt(a2) * (parseInt(a3)/100)+ parseInt(a1))/ parseInt(a2)).toFixed(2);
            result_sum.value = Math.round(parseFloat(((parseInt(a1) * parseInt(a2) * (parseInt(a3)/100)+ parseInt(a1))/ parseInt(a2))/ parseInt(summ)*100).toFixed(2))+ "%";
            var sum_borrow =  Math.round(parseFloat(((parseInt(a1) * parseInt(a2) * (parseInt(a3)/100)+ parseInt(a1))/ parseInt(a2))/ parseInt(summ)*100).toFixed(2));
            if(document.getElementById('148') && document.getElementById('118').value)
            {
                var  ansbor = '148'; var  ansbor2 = '149'; var  ansbor3 = '150'; var  ansbor4 = '151';
            }else if(document.getElementById('152') && document.getElementById('152').value)
            {
                var  ansbor = '152'; var  ansbor2 = '153'; var  ansbor3 = '154'; var  ansbor4 = '155';
            }else if(document.getElementById('156') && document.getElementById('156').value)
            {
                var  ansbor = '156'; var  ansbor2 = '157'; var  ansbor3 = '158'; var  ansbor4 = '159';
            }
            if (sum_borrow <= '25'){
                document.getElementById(ansbor).checked = true;
                if (document.getElementById(ansbor).checked == true) {
                    var totalb =  document.getElementById(ansbor).value;

                }
            }else if(sum_borrow > '25' && sum_borrow <= '35'){
                document.getElementById(ansbor2).checked = true;
                if (document.getElementById(ansbor2).checked == true) {
                    var totalb =  document.getElementById(ansbor2).value;

                }
            }else if(sum_borrow > '35' && sum_borrow <= '40'){
                document.getElementById(ansbor3).checked = true;
                if (document.getElementById(ansbor3).checked == true) {
                    var totalb =  document.getElementById(ansbor3).value;

                }
            }else if(sum_borrow > '40'){
                document.getElementById(ansbor4).checked = true;
                if (document.getElementById(ansbor4).checked == true) {
                    var totalb =  document.getElementById(ansbor4).value;

                }
            }
            totalsum();
        }
    }
    function calculate5(){
        //document.getElementById('netincome').value=document.getElementById('s0').value+document.getElementById('s1').value;
        var rate1 = document.getElementById('rate1').value;
        var rate2 = document.getElementById('rate2').value;
        var rate3 = document.getElementById('rate3');
        if(isNaN(parseInt(rate1))){
            rate3.value ="0";
        }else if(isNaN(parseInt(rate2))){
            rate3.value ="0";
        }else{
            rate3.value = Math.floor((parseInt(rate1)/parseInt(rate2))*100)+ "%";
            var ratesum = Math.floor((parseInt(rate1)/parseInt(rate2))*100);
            if(document.getElementById('118') && document.getElementById('118').value)
            {
              var  ansId = '118'; var  ansId2 = '117'; var  ansId3 = '116'; var  ansId4 = '115';
            }else if(document.getElementById('119') && document.getElementById('119').value)
            {
                var  ansId = '122'; var  ansId2 = '121'; var  ansId3 = '120'; var  ansId4 = '119';
            }else if(document.getElementById('123') && document.getElementById('123').value)
            {
                var  ansId = '126'; var  ansId2 = '125'; var  ansId3 = '124'; var  ansId4 = '123';
            }
            if (ratesum >= '86'){
                document.getElementById(ansId).checked = true;
                if (document.getElementById(ansId).checked == true) {
                    var total3 =  document.getElementById(ansId).value;

                }
            }else if (ratesum >= '71' && rate3.value <= '85'){
                document.getElementById(ansId2).checked = true;
                if (document.getElementById(ansId2).checked == true) {
                    var total3 =  document.getElementById(ansId2).value;

                }
            }else if (ratesum >= '61' && rate3.value <= '70'){
                document.getElementById(ansId3).checked = true;
                if (document.getElementById(ansId3).checked == true) {
                    var total3 =  document.getElementById(ansId3).value;

                }
            }else if(ratesum >= '0' && rate3.value < '61'){
                document.getElementById(ansId4).checked = true;
                if (document.getElementById(ansId4).checked == true) {
                    var total3 =  document.getElementById(ansId4).value;
                }
            }
            totalsum();
        }
    }
    function totalsum() {
        $('#totalSum').each(function(){
            var totalPoints = 0;
            $("input[type=radio]:checked").each(function() {
                totalPoints += parseInt($(this).val()); //<==== a catch  in here !! read below
            });
            $("#totalSum").val(totalPoints);
        });
    }
</script>
</body>
</html>
