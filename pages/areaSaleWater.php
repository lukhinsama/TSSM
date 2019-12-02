<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
?>
<html>
<head>
<?php include_once("../include/inc-header-2.php"); ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
  <?php include_once("../analyticstracking.php") ?>

<div class="wrapper">

  <section class="content">
    <div class="row">
        <div class="col-md-12">
          <!-- MAP & BOX PANE -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> ค้นหา </h3>

                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">

            </div>
          </div>
        </div>
      </div>
  </section>
<?php include_once("body_footer.php") ?>
</div>

</body>
</html>
