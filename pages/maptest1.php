<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

 if (!empty($_REQUEST['province']) AND !empty($_REQUEST['distict']) AND !empty($_REQUEST['subdistict'])) {
   $con = connectDB_BigHead();

   $sql = "SELECT top 1 ChangwatName FROM TSRData_Source.dbo.TSSM_ChangwatGPS WHERE ChangwatID = '".$_REQUEST['province']."'";

   $stmt = sqlsrv_query($con,$sql);
   if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
     $ChangwatName = $row['ChangwatName'];
   }

   $sql = "SELECT top 1 AmphoeName FROM TSRData_Source.dbo.TSSM_AmphoeGPS WHERE AmphoeID = '".$_REQUEST['distict']."'";

   $stmt = sqlsrv_query($con,$sql);
   if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
     $AmphoeName = $row['AmphoeName'];
   }

   $sql = "SELECT top 1 TambonName FROM TSRData_Source.dbo.TSSM_TambonGPS WHERE TambonID = '".$_REQUEST['subdistict']."'";

   $stmt = sqlsrv_query($con,$sql);
   if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
     $TambonName = $row['TambonName'];
   }
   if ($ChangwatName == "กรุงเทพมหานคร") {
     $TextSearch = "แขวง+".$TambonName."+เขต".$AmphoeName."+".$ChangwatName;
   }else {
     $TextSearch = "ตำบล+".$TambonName."+อำเภอ".$AmphoeName."+".$ChangwatName;
   }

}else {
  $TextSearch = 'ประเทศไทย';
}
$GoogleKey = "AIzaSyAJRbibWMFUhe14Zuum9cxy8bxz4hqAdLQ";

?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="row">
      <div class="col-md-3">
        <h4>
          ข้อมูลพื้นที่การขาย
        </h4>
      </div>
      <div class="col-md-3">

      </div>
      <div class="col-md-4">

      </div>

    </div>
    <ol class="breadcrumb">
      <li><a href="index.php?pages=info"><i class="fa fa-user"></i> งานระบบ</a></li>
      <li class="active">ข้อมูลพื้นที่การขาย</li>
    </ol>
  </section>

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
              <form role="form" data-toggle="validator" id="formSearch" name="formSearch" method="post" action="index.php?pages=saleArea">
                <div class="col-md-3">
                  <div class="form-group group-sm">
                    <select class="form-control select2 group-sm" name="province" id = "province">
                      <option id="province">กรุณาเลือกจังหวัด</option>
                        <?php
                        $sql = "SELECT ChangwatName,ChangwatID FROM TSRData_Source.dbo.TSSM_ChangwatGPS ORDER BY ChangwatName";
                        //echo $sql_case;
                        $con = connectDB_BigHead();
                        $stmt = sqlsrv_query($con,$sql);
                        while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                        ?>
                        <option <?php if ($_GET['province'] == $row['ChangwatName']) { echo "selected" ;} ?> value="<?=$row['ChangwatID']?>" ><?=$row['ChangwatName']?>
                        </option>
                        <?php
                          }
                          sqlsrv_close($con);
                         ?>
                      </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group group-sm">
                    <select class="form-control select2 group-sm" name="distict" id = "distict">
                      <option id="distict">อำเภอ/เขต</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group group-sm">
                    <select class="form-control select2 group-sm" name="subdistict" id = "subdistict">
                        <option id="subdistict">ตำบล/แขวง</option>
                      </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group group-sm">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>


            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"> แผนที่ </h3>
                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                  <!--<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>-->
                </div>
            </div>
            <div class="box-body no-padding">
                <iframe width="100%" height="400" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=<?=$GoogleKey?>&q=<?=$TextSearch?>" allowfullscreen></iframe>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"> ข้อมูลน้ำ </h3>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                  <iframe width="100%" height="400" frameborder="0" style="border:0" scrolling ="auto" src="https://tssm.thiensurat.co.th/areaSaleWater.php" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"> ข้อมูลลูกค้า </h3>
                    <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">

                </div>
              </div>
            </div>
          </div>

        </div>

      </div>
  </section>
</div>
<script>

    $(function(){
      //แสดงข้อมูล อำเภอ  โดยใช้คำสั่ง change จะทำงานกรณีมีการเปลี่ยนแปลงที่ #province
      $("#province").change(function(){
        //กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
        var province = $(this).val();
        //alert(province);
        $.ajax({
          url:"pages/getdata_amphoe.php",//url:"get_data.php",
          dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
          data:{province:province},//ส่งค่าตัวแปร province_id เพื่อดึงข้อมูล อำเภอ ที่มี province_id เท่ากับค่าที่ส่งไป
          success:function(data){
            //กำหนดให้ข้อมูลใน #amphur เป็นค่าว่าง
            $("#distict").text("");
            //วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
            $.each(data, function( index, value ) {
              //แทรก Elements ข้อมูลที่ได้  ใน id amphur  ด้วยคำสั่ง append
                $("#distict").append("<option value='"+ value.id +"'> " + value.name + "</option>");
            });
          }
        });
      });

      $("#distict").change(function(){
        //กำหนดให้ ตัวแปร province มีค่าเท่ากับ ค่าของ #province ที่กำลังถูกเลือกในขณะนั้น
        var distict = $(this).val();
        //alert(distict);
        $.ajax({
          url:"pages/getdata_tambon.php",//url:"get_data.php",
          dataType: "json",//กำหนดให้มีรูปแบบเป็น Json
          data:{distict:distict},//ส่งค่าตัวแปร province_id เพื่อดึงข้อมูล อำเภอ ที่มี province_id เท่ากับค่าที่ส่งไป
          success:function(data){
            //กำหนดให้ข้อมูลใน #amphur เป็นค่าว่าง
            $("#subdistict").text("");
            //วนลูปแสดงข้อมูล ที่ได้จาก ตัวแปร data
            $.each(data, function( index, value ) {
              //แทรก Elements ข้อมูลที่ได้  ใน id amphur  ด้วยคำสั่ง append
                $("#subdistict").append("<option value='"+ value.id +"'> " + value.name + "</option>");
            });
          }
        });
      });

    });
</script>
