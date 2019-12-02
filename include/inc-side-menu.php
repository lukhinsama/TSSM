<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
if(empty($_GET['pages'])){
  $_GET['pages'] = "";
}
 ?>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="header">เมนู</li>
      <?php
      if (($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 9) || ($_COOKIE['tsr_emp_permit'] == 101) ||
      ($_COOKIE['tsr_emp_permit'] == 102) || ($_COOKIE['tsr_emp_permit'] == 103) || ($_COOKIE['tsr_emp_permit'] == 104) || ($_COOKIE['tsr_emp_permit'] == 105) || ($_COOKIE['tsr_emp_permit'] == 106) || ($_COOKIE['tsr_emp_permit'] == 107)) {
       ?>
      <li <?=sidemenu($_GET['pages'],3)?>>
        <a href="#">
          <i class="fa fa-laptop"></i><span>ระบบจัดการข้อมูล</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">

          <li <?=sidemenu3($_GET['pages'],31)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>ระบบการเก็บเงิน</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php
                if (($_COOKIE['tsr_emp_permit']!=3)) {
               ?>
                  <!--
                  <li <?=sidemenu2($_GET['pages'],311)?>><a href="index.php?pages=sorting"><i class="fa fa-circle-o"></i>จัดวันเก็บเงิน</a></li>
                  <li <?=sidemenu2($_GET['pages'],312)?>><a href="index.php?pages=sorting1"><i class="fa fa-circle-o"></i>จัดลำดับพนักงานเก็บเงิน</a></li>
                  <li <?=sidemenu2($_GET['pages'],313)?>><a href="index.php?pages=tranCByE"><i class="fa fa-circle-o"></i>โอนลูกค้ารายบุคคล</a></li>
                  <li <?=sidemenu2($_GET['pages'],314)?>><a href="index.php?pages=tranCByS"><i class="fa fa-circle-o"></i>โอนลูกค้ารายเขต</a></li>-->
                  <li <?=sidemenu2($_GET['pages'],314)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferBigHeadByCarea.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนเขตย้ายสาย</a></li>
                  <?php
                }
                   ?>
                  <li <?=sidemenu2($_GET['pages'],319)?>>
                  <!--<a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_ByEmp.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนย้ายการ์ดภายในบิ๊กเฮด</a>-->
                  <a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_ByArea1.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนย้ายการ์ดภายในบิ๊กเฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],321)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_RedHouse.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>ข้อมูลโอนบ้านแดง/บิ๊กแฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],320)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_printTranfer_readhouse_barcode.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>พิมพ์รายการโอนบ้านแดง/บิ๊กแฮด</a></li>

                  <li <?=sidemenu2($_GET['pages'],322)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_tranferRedhouseAndBigHead.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนบ้านแดง/บิ๊กแฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],315)?>><a href="index.php?pages=tranCByC"><i class="fa fa-circle-o"></i>โอนลูกค้ารายสัญญา</a></li>
                  <li <?=sidemenu2($_GET['pages'],316)?>><a href="index.php?pages=tranCByCs"><i class="fa fa-circle-o"></i>โอนลูกค้าร้านค้า</a></li>
                  <!--<li <?=sidemenu2($_GET['pages'],317)?>><a href="index.php?pages=chkdateKep"><i class="fa fa-circle-o"></i>ตรวจสอบวันที่นัดเก็บเงิน</a></li>
                  <li <?=sidemenu2($_GET['pages'],320)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_ByEmp.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>รับโอนการ์ด</a></li>-->

                   <li <?=sidemenu2($_GET['pages'],318)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_AdAmtByTeam.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>เพิ่มรายการเก็บเงิน</a></li>
                   <li <?=sidemenu2($_GET['pages'],323)?>><a href="index.php?pages=chackContnoBighead"><i class="fa fa-circle-o"></i>ตรวจสอบสถานะสัญญา</a></li>

                  <!--
                  <li <?=sidemenu2($_GET['pages'],318)?>><a href="index.php?pages=addKep&sid=<?=$_COOKIE['tsr_emp_id']?>"><i class="fa fa-circle-o"></i> เพิ่มรายการเก็บเงิน</a></li>
                -->
            </ul>

          </li>
        </ul>
      </li>


      <li <?=sidemenu($_GET['pages'],2)?>>
        <a href="#">
          <i class="fa fa-desktop"></i> <span>ระบบมอนิเตอร์</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">

          <li <?=sidemenu3($_GET['pages'],21)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>ข้อมูลบ้านแดง/บิ๊กเฮด</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php
                if (($_COOKIE['tsr_emp_permit']!=3)) {
               ?>
                <li <?=sidemenu2($_GET['pages'],211)?>><a href="index.php?pages=monitordata1"><i class="fa fa-circle-o"></i> กระทบข้อมูลสัญญา</a></li>
                <li <?=sidemenu2($_GET['pages'],212)?>><a href="index.php?pages=monitordata2" target="_blank"><i class="fa fa-circle-o"></i> กระทบรายการเก็บเงิน</a></li>
                <?php
                }
                 ?>
                <li <?=sidemenu2($_GET['pages'],213)?>><a href="index.php?pages=monitorReportCredit1"><i class="fa fa-circle-o"></i> เทียบการเก็บเงินรายวัน</a></li>

            </ul>
          </li>
            <li <?=sidemenu2($_GET['pages'],221)?>><a href="http://app.thiensurat.co.th/tsr_monitor_app_sale/" target ="_blank"><i class="fa fa-circle-o"></i>มอนิเตอร์การเก็บเงิน</a></li>
            <li <?=sidemenu2($_GET['pages'],222)?>><a href="index.php?pages=monitorReceiptBle"><i class="fa fa-circle-o"></i>มอนิเตอร์ใบเสร็จ</a></li>
            <li <?=sidemenu2($_GET['pages'],223)?>><a href="index.php?pages=monitorContractCrdTele"><i class="fa fa-circle-o"></i>มอนิเตอร์ผู้แนะนำ</a></li>
        </ul>
      </li>
      <?php
        }
       ?>
       <?php
           if (($_COOKIE['tsr_emp_permit'] == 4 )) {
             if (substr($_COOKIE['tsr_emp_id'],0,1) == "0") {
               $EmpID['0'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
             }else {
               $EmpID['0'] = $_COOKIE['tsr_emp_id'];
             }
               $EmpID['1'] = $_COOKIE['tsr_emp_name'];

               $connss = connectDB_BigHead();
               //$sql_Empid = "SELECT DISTINCT SaleCode,ProcessType FROM Bighead_Mobile.dbo.EmployeeDetail WHERE EmployeeCode = '".$EmpID['0']."'";
               $sql_Empid = "SELECT *
from (SELECT DISTINCT EmployeeCode,ProcessType,1 AS Counts FROM Bighead_Mobile.dbo.EmployeeDetail
) as src
pivot (
    COUNT(Counts) for ProcessType in ([Sale],[Brn],[Tele],[Crd],[On],[Fa],[Credit],[Dept])
) as pv
WHERE EmployeeCode = '".$EmpID['0']."'";

               //echo $sql_Empid;

               $stmt = sqlsrv_query($connss,$sql_Empid);
               while ($rowdata = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                 //$EmpID['2'] = $rowss['SaleCode'];
                 //$EmpID['3'] = $rowss['ProcessType'];
                 $EmpTypeSale = $rowdata['Sale'];
                 $EmpTypeBrn = $rowdata['Brn'];
                 $EmpTypeTele = $rowdata['Tele'];
                 $EmpTypeCrd = $rowdata['Crd'];
                 $EmpTypeOn = $rowdata['On'];
                 $EmpTypeFa = $rowdata['Fa'];
                 $EmpTypeCredit = $rowdata['Credit'];
                 $EmpTypeDept = $rowdata['Dept'];
               }

               sqlsrv_close($connss);

               //$WHERE .= " AND R.ZoneCode = '".$EmpID['2']."'";



           }
      ?>
      <?php
        //if ((($_COOKIE['tsr_emp_permit'] != 4 ) && ($_COOKIE['tsr_emp_permit'] != 5 )) || ($EmpID['3'] == 'Sale' )) {
        if ((($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2 )
        || ($_COOKIE['tsr_emp_permit'] == 9) || ($_COOKIE['tsr_emp_permit'] == 6) || ($_COOKIE['tsr_emp_permit'] == 8)
        || ($_COOKIE['tsr_emp_permit'] == 18) || ($_COOKIE['tsr_emp_permit'] == 19 )
        || ($_COOKIE['tsr_emp_permit'] == 13)) || ($EmpTypeSale == '1' )) {

       ?>
      <li <?=sidemenu($_GET['pages'],1)?>>
        <a href="#">
          <i class="fa fa-money"></i><span>รายงานฝ่ายขาย</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
          <?php
            if ($_COOKIE['tsr_emp_permit'] == 8) {
           ?>
<li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=0" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
<?php
}else {
 ?>
          <li <?=sidemenu3($_GET['pages'],11)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานเก็บเงิน/ใบเสร็จ</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <?php
                if ($_COOKIE['tsr_emp_id'] == '003026') {
                  ?>
                  <li <?=sidemenu2($_GET['pages'],13)?>><a href="index.php?pages=reportsale13"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
                  <?php
                }else {
               ?>
              <!--<li <?=sidemenu2($_GET['pages'],111)?>><a href="index.php?pages=reportsale1"><i class="fa fa-circle-o"></i>เก็บงวดแรกรายวัน</a></li>-->
              <li <?=sidemenu2($_GET['pages'],112)?>><a href="index.php?pages=reportsale2"><i class="fa fa-circle-o"></i>เก็บงวดแรกบุคคล</a></li>
              <?php
                  if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2 ) || ($_COOKIE['tsr_emp_permit'] == 6 )){
               ?>
              <li <?=sidemenu2($_GET['pages'],113)?>><a href="index.php?pages=reportsale3"><i class="fa fa-circle-o"></i>เก็บงวดแรก(เต็มงวด)</a></li>
              <li <?=sidemenu2($_GET['pages'],116)?>><a href="index.php?pages=reportsale7"><i class="fa fa-circle-o"></i>เก็บงวดแรก(บางส่วนครบ)</a></li>
              <li <?=sidemenu2($_GET['pages'],117)?>><a href="index.php?pages=reportsale8"><i class="fa fa-circle-o"></i>เก็บงวดแรก(ยกเลิก)</a></li>
              <li <?=sidemenu2($_GET['pages'],114)?>><a href="index.php?pages=reportsale4"><i class="fa fa-circle-o"></i><span>เก็บงวดแรก(บางส่วน)</span></a></li>
              <li <?=sidemenu2($_GET['pages'],119)?>><a href="index.php?pages=reportsale10"><i class="fa fa-circle-o"></i><span>เก็บงวดแรก(บางส่วนไม่ครบ)</span></a></li>
                <li <?=sidemenu2($_GET['pages'],118)?>><a href="index.php?pages=reportsale9"><i class="fa fa-circle-o"></i><span>รายงานใบเสร็จมือ</span></a></li>
              <?php
                  }
               ?>
              <li <?=sidemenu2($_GET['pages'],1110)?>><a href="index.php?pages=reportsale12"><i class="fa fa-circle-o"></i><span>เก็บงวดแรกรวม</span></a></li>
            </ul>
          </li>
          <?php

            if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2 ) || ($_COOKIE['tsr_emp_permit'] == 6 )){
              $typeID = "1";
            }else {
              $typeID = "0";
            }

          ?>
          <li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=<?=$typeID?>" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
          <li <?=sidemenu2($_GET['pages'],13)?>><a href="index.php?pages=reportsale5"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
          <?php
            }
          }
           ?>
        </ul>

      </li>
      <?php
    }
    if (($_COOKIE['tsr_emp_permit'] == 1 ) OR ($_COOKIE['tsr_emp_permit'] == 2 ) OR ($_COOKIE['tsr_emp_permit'] == 7) || ($_COOKIE['tsr_emp_permit'] == 9) || ($_COOKIE['tsr_emp_permit'] == 13) || ($_COOKIE['tsr_emp_permit'] == 19 ) || ($EmpTypeBrn == '1')) {
       ?>

       <li <?=sidemenu($_GET['pages'],9)?>>
         <a href="#">
           <i class="fa fa-sitemap"></i><span>รายงานฝ่ายบริหารสาขา</span>
           <i class="fa fa-angle-left pull-right"></i>
         </a>

         <ul class="treeview-menu">

           <li <?=sidemenu3($_GET['pages'],91)?>>
             <a href="#">
               <i class="fa fa-circle-o"></i><span>รายงานเก็บเงิน/ใบเสร็จ</span>
               <i class="fa fa-angle-left pull-right"></i>
             </a>
             <ul class="treeview-menu">
               <li <?=sidemenu2($_GET['pages'],912)?>><a href="index.php?pages=reportbrn2"><i class="fa fa-circle-o"></i>เก็บงวดแรกบุคคล</a></li>
               <?php
                   if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2 ) || ($_COOKIE['tsr_emp_permit'] == 7 )){
                ?>
               <li <?=sidemenu2($_GET['pages'],913)?>><a href="index.php?pages=reportbrn3"><i class="fa fa-circle-o"></i>เก็บงวดแรก(เต็มงวด)</a></li>
               <li <?=sidemenu2($_GET['pages'],914)?>><a href="index.php?pages=reportbrn4"><i class="fa fa-circle-o"></i><span>เก็บงวดแรกบุคคล(บางส่วน)</span></a></li>
               <li <?=sidemenu2($_GET['pages'],916)?>><a href="index.php?pages=reportbrn7"><i class="fa fa-circle-o"></i>เก็บงวดแรก(บางส่วนครบ)</a></li>
               <li <?=sidemenu2($_GET['pages'],917)?>><a href="index.php?pages=reportbrn8"><i class="fa fa-circle-o"></i>เก็บงวดแรก(ยกเลิก)</a></li>
               <li <?=sidemenu2($_GET['pages'],918)?>><a href="index.php?pages=reportbrn9"><i class="fa fa-circle-o"></i><span>รายงานใบเสร็จมือ</span></a></li>
               <li <?=sidemenu2($_GET['pages'],9110)?>><a href="index.php?pages=reportoper17"><i class="fa fa-circle-o"></i><span>งวดแรกรวม ไม่แยกฝ่าย</span></a></li>
               <?php
}
               ?>
                <li <?=sidemenu2($_GET['pages'],919)?>><a href="index.php?pages=reportbrn12"><i class="fa fa-circle-o"></i><span>เก็บงวดแรกรวม</span></a></li>
             </ul>
           </li>
           <?php

             if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2 ) || ($_COOKIE['tsr_emp_permit'] == 6 ) || ($_COOKIE['tsr_emp_permit'] == 9) || ($_COOKIE['tsr_emp_permit'] == 7 )){
               $typeID = "1";
             }else {
               $typeID = "0";
             }

           ?>
           <li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=<?=$typeID?>" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
           <li <?=sidemenu2($_GET['pages'],93)?>><a href="index.php?pages=reportbrn5"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
         </ul>

       </li>

     <?php
   }
   if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 7) || ($_COOKIE['tsr_emp_permit'] == 9)
    || ($_COOKIE['tsr_emp_permit'] == 10) || ($_COOKIE['tsr_emp_permit'] == 13) || ($_COOKIE['tsr_emp_permit'] == 18) || ($_COOKIE['tsr_emp_permit'] == 14)
   || ($EmpTypeCrd == '1')) {
      ?>

      <li <?=sidemenu($_GET['pages'],10)?>>
        <a href="#">
          <i class="fa fa-share-alt"></i><span>รายงานฝ่ายธุรกิจต่อเนื่อง</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">

          <li <?=sidemenu3($_GET['pages'],101)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานเก็บเงิน/ใบเสร็จ</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li <?=sidemenu2($_GET['pages'],103)?>><a href="index.php?pages=reportcrd2_bak"><i class="fa fa-circle-o"></i>เก็บงวดแรกบุคคล</a></li>
              <li <?=sidemenu2($_GET['pages'],101)?>><a href="index.php?pages=reportcrd2"><i class="fa fa-circle-o"></i>เก็บงวดแรกรวม</a></li>
            </ul>
          </li>

          <li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=1" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
          <li <?=sidemenu2($_GET['pages'],102)?>><a href="index.php?pages=reportcrd5"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
        </ul>

      </li>

    <?php
  } if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2)
  || ($_COOKIE['tsr_emp_permit'] == 9) || ($_COOKIE['tsr_emp_permit'] == 11) || ($_COOKIE['tsr_emp_permit'] == 13) || ($_COOKIE['tsr_emp_permit'] == 14) || ($EmpTypeTele == '1')) {
      ?>

      <li <?=sidemenu($_GET['pages'],11)?>>
        <a href="#">
          <i class="fa fa-phone"></i><span>รายงานฝ่ายขายทางโทรศัพท์</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
          <!--
          <li <?=sidemenu3($_GET['pages'],1101)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานเก็บเงิน/ใบเสร็จ</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li <?=sidemenu2($_GET['pages'],1101)?>><a href="index.php?pages=reporttele2"><i class="fa fa-circle-o"></i>เก็บงวดแรกบุคคล</a></li>
            </ul>
          </li>
        -->
          <li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=1" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
          <li <?=sidemenu2($_GET['pages'],1102)?>><a href="index.php?pages=reporttele5"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
        </ul>

      </li>

    <?php
  } if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2) || ($_COOKIE['tsr_emp_permit'] == 9)
   || ($_COOKIE['tsr_emp_permit'] == 12) || ($_COOKIE['tsr_emp_permit'] == 13) || ($_COOKIE['tsr_emp_permit'] == 14) || ($EmpTypeTele == '1')) {
      ?>

      <li <?=sidemenu($_GET['pages'],12)?>>
        <a href="#">
          <i class="fa fa-hourglass-half"></i><span>รายงานฝ่ายติดตั้งผลิตภัณท์</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
          <!--
          <li <?=sidemenu3($_GET['pages'],1101)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานเก็บเงิน/ใบเสร็จ</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li <?=sidemenu2($_GET['pages'],1101)?>><a href="index.php?pages=reporttele2"><i class="fa fa-circle-o"></i>เก็บงวดแรกบุคคล</a></li>
            </ul>
          </li>
        -->
          <?php

            if (($_COOKIE['tsr_emp_permit'] == 1 )){
              $typeID = "1";
            }else {
              $typeID = "0";
            }

          ?>
          <li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=<?=$typeID?>" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
          <li <?=sidemenu2($_GET['pages'],1202)?>><a href="index.php?pages=reportinstall5"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
        </ul>
      </li>
    <?php
  }
      if(($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 15) || ($_COOKIE['tsr_emp_permit'] == 9)){
     ?>
      <li <?=sidemenu($_GET['pages'],7)?>>
        <a href="#">
          <i class="fa fa-users"></i> <span> ฝ่ายลูกค้าสัมพันธ์ </span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li <?=sidemenu2($_GET['pages'],71)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=0" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>

        </ul>
      </li>

    <?php
  }
       //if ( (($_COOKIE['tsr_emp_permit'] != 4 ) && ($_COOKIE['tsr_emp_permit'] != 5 ) && ($_COOKIE['tsr_emp_permit']!=6)) || ($EmpID['3'] == 'Credit' )) {
       if (($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2 ) || ($_COOKIE['tsr_emp_permit'] == 9)
         || ($_COOKIE['tsr_emp_permit'] == 13 ) || ($_COOKIE['tsr_emp_permit'] == 101)
         || ($_COOKIE['tsr_emp_permit'] == 102) || ($_COOKIE['tsr_emp_permit'] == 103)
         || ($_COOKIE['tsr_emp_permit'] == 104) || ($_COOKIE['tsr_emp_permit'] == 105)
         || ($_COOKIE['tsr_emp_permit'] == 106) || ($_COOKIE['tsr_emp_permit'] == 107)
         || ($EmpTypeCredit == '1') || ($_COOKIE['tsr_emp_permit'] == 3)) {
         //if(substr($_COOKIE['tsr_emp_id'],0,3) != "ZDP" ){
     ?>
      <li <?=sidemenu($_GET['pages'],4)?>>
        <a href="#">
          <i class="fa fa-credit-card"></i> <span> รายงานฝ่ายเครดิต </span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <?php
            if (($_COOKIE['tsr_emp_permit']!=4)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],41)?>><a href="index.php?pages=reportcredit1"><i class="fa fa-circle-o"></i>รายงานเก็บเงินรายวัน</a></li>
          <?php
        }
           ?>
          <!--<li <?=sidemenu2($_GET['pages'],42)?>><a href="index.php?pages=reportcredit2"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>-->
          <li <?=sidemenu2($_GET['pages'],49)?>><a href="index.php?pages=reportcredit2_back"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>
          <!--<li <?=sidemenu2($_GET['pages'],49)?>><a href="index.php?pages=reportcredit2_back"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล(ย้อนหลัง)</span></a></li>-->
          <?php
            if (($_COOKIE['tsr_emp_permit']!=4)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],47)?>><a href="index.php?pages=reportcredit7"><i class="fa fa-circle-o"></i>รายงานเก็บเงินรายวัน(บางส่วน)</a></li>
          <?php
        }
           ?>
          <li <?=sidemenu2($_GET['pages'],48)?>><a href="index.php?pages=reportcredit8"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล(บางส่วน)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],43)?>><a href="index.php?pages=reportcredit3"><i class="fa fa-circle-o"></i><span>รายงานการส่งเงิน</span></a></li>
          <?php
            if (($_COOKIE['tsr_emp_permit']!=4)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],44)?>><a href="index.php?pages=reportcredit4"><i class="fa fa-circle-o"></i><span>รายงานยกเลิกใบเสร็จ</span></a></li>
          <?php
          }
           ?>
          <li <?=sidemenu2($_GET['pages'],45)?>><a href="index.php?pages=reportcredit5"><i class="fa fa-circle-o"></i><span>รายงานยกเลิกใบเสร็จรายบุคคล</span></a></li>
          <li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=0" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
          <li <?=sidemenu2($_GET['pages'],46)?>><a href="index.php?pages=reportcredit6"><i class="fa fa-circle-o"></i><span>รายงานการวิ่งงานรายทริป</span></a></li>
          <li <?=sidemenu2($_GET['pages'],410)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_Receipt_Detail.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ทำฟอร์ม)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],411)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=1" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ปิดทริป)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],413)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=2" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินได้ </span></a></li>
          <li <?=sidemenu2($_GET['pages'],412)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=3" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินไม่ได้ </span></a></li>
          <li <?=sidemenu2($_GET['pages'],414)?>><a href="index.php?pages=reportReprint"><i class="fa fa-circle-o"></i>ค้นหาตามใบเสร็จ</a></li>
          <li <?=sidemenu2($_GET['pages'],415)?>><a href="index.php?pages=reportReprint2"><i class="fa fa-circle-o"></i>จำนวนพิมพ์ใบเสร็จซ้ำ</a></li>
        </ul>
      </li>
      <?php
        //}
      }
        //if ( (($_COOKIE['tsr_emp_permit'] != 4 ) && ($_COOKIE['tsr_emp_permit'] != 5 ) && ($_COOKIE['tsr_emp_permit']!=6))|| ($EmpID['3'] == 'DEPT' )) {
        if ( ($_COOKIE['tsr_emp_permit'] == 1 ) || ($_COOKIE['tsr_emp_permit'] == 2 ) || ($_COOKIE['tsr_emp_permit'] == 9)
         || ($_COOKIE['tsr_emp_permit'] == 3 ) || ($_COOKIE['tsr_emp_permit'] == 13) || ($EmpTypeDept == '1') || ($EmpTypeCredit == '1') ) {
      //if(substr($_COOKIE['tsr_emp_id'],0,3) != "ZCR" ){
      ?>
      <li <?=sidemenu($_GET['pages'],5)?>>
        <a href="#">
          <i class="fa fa-cab"></i> <span> รายงานฝ่ายเร่งรัด</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <?php
            if (($_COOKIE['tsr_emp_permit']!=4)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],51)?>><a href="index.php?pages=reportdept1"><i class="fa fa-circle-o"></i>รายงานเก็บเงินรายวัน</a></li>
          <?php
        }
           ?>
          <!--<li <?=sidemenu2($_GET['pages'],52)?>><a href="index.php?pages=reportdept2"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>-->
          <li <?=sidemenu2($_GET['pages'],59)?>><a href="index.php?pages=reportdept2_back"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>
          <?php
            if (($_COOKIE['tsr_emp_permit']!=4)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],57)?>><a href="index.php?pages=reportdept7"><i class="fa fa-circle-o"></i>รายงานเก็บเงินรายวัน(บางส่วน)</a></li>
          <?php
        }
           ?>
          <li <?=sidemenu2($_GET['pages'],58)?>><a href="index.php?pages=reportdept8"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล(บางส่วน)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],53)?>><a href="index.php?pages=reportdept3"><i class="fa fa-circle-o"></i><span>รายงานการส่งเงิน</span></a></li>
          <li <?=sidemenu2($_GET['pages'],54)?>><a href="index.php?pages=reportdept4"><i class="fa fa-circle-o"></i><span>รายงานยกเลิกใบเสร็จ</span></a></li>
          <li <?=sidemenu2($_GET['pages'],55)?>><a href="index.php?pages=reportdept5"><i class="fa fa-circle-o"></i><span>รายงานยกเลิกใบเสร็จรายบุคคล</span></a></li>
          <li <?=sidemenu2($_GET['pages'],56)?>><a href="index.php?pages=reportdept6"><i class="fa fa-circle-o"></i><span>รายงานการวิ่งงานรายทริป</span></a></li>
          <li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>&typeID=0" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
          <li <?=sidemenu2($_GET['pages'],510)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_Receipt_Detail.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ทำฟอร์ม)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],511)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=1" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ปิดทริป)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],513)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=2" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินได้ </span></a></li>
          <li <?=sidemenu2($_GET['pages'],512)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=3" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินไม่ได้ </span></a></li>

        </ul>
      </li>

      <?php
        //}
      }
      //if((substr($_COOKIE['tsr_emp_id'],0,3) != "ZCR") AND (substr($_COOKIE['tsr_emp_id'],0,3) != "ZDP") AND (($_COOKIE['tsr_emp_permit'] == 2) OR ($_COOKIE['tsr_emp_permit'] == 1) OR ($_COOKIE['tsr_emp_permit'] == 5) )){
      if(($_COOKIE['tsr_emp_permit'] == 2) OR ($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 9)
       OR ($_COOKIE['tsr_emp_permit'] == 5) || ($_COOKIE['tsr_emp_permit'] == 13 )){
      ?>
      <li <?=sidemenu($_GET['pages'],6)?>>
        <a href="#">
          <i class="fa fa-fax"></i> <span> รายงานฝ่ายปฏิบัตการ</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">

          <li <?=sidemenu2($_GET['pages'],61)?>><a href="index.php?pages=reportoper1"><i class="fa fa-circle-o"></i>รายงานเก็บเงินรายวัน</a></li>
          <!--<li <?=sidemenu2($_GET['pages'],62)?>><a href="index.php?pages=reportoper2"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>-->

          <li <?=sidemenu2($_GET['pages'],69)?>><a href="index.php?pages=reportoper2_back"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>
          <?php
            if (($_COOKIE['tsr_emp_permit'] != 8)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],67)?>><a href="index.php?pages=reportoper7"><i class="fa fa-circle-o"></i>รายงานเก็บเงินรายวัน(บางส่วน)</a></li>
          <li <?=sidemenu2($_GET['pages'],68)?>><a href="index.php?pages=reportoper8"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล(บางส่วน)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],616)?>><a href="index.php?pages=reportoper10"><i class="fa fa-circle-o"></i><span>การเก็บเงินงวดแรกรายวัน </span></a></li>
          <li <?=sidemenu2($_GET['pages'],617)?>><a href="index.php?pages=reportoper11"><i class="fa fa-circle-o"></i><span>เก็บเงินงวดแรกรายบุคคล </span></a></li>
          <li <?=sidemenu2($_GET['pages'],618)?>><a href="index.php?pages=reportoper12"><i class="fa fa-circle-o"></i><span>เก็บเงินงวดแรกรายวัน(บางส่วน)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],619)?>><a href="index.php?pages=reportoper13"><i class="fa fa-circle-o"></i><span>เก็บเงินงวดแรกรายบุคคล(บางส่วน)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],63)?>><a href="index.php?pages=reportoper3"><i class="fa fa-circle-o"></i><span>รายงานการส่งเงิน</span></a></li>
          <li <?=sidemenu2($_GET['pages'],64)?>><a href="index.php?pages=reportoper4"><i class="fa fa-circle-o"></i><span>รายงานยกเลิกใบเสร็จ</span></a></li>
          <li <?=sidemenu2($_GET['pages'],65)?>><a href="index.php?pages=reportoper5"><i class="fa fa-circle-o"></i><span>รายงานยกเลิกใบเสร็จรายบุคคล</span></a></li>
          <?php
        }
           ?>
          <li <?=sidemenu2($_GET['pages'],66)?>><a href="index.php?pages=reportoper6"><i class="fa fa-circle-o"></i><span>รายงานการวิ่งงานรายทริป</span></a></li>
          <?php
            if (($_COOKIE['tsr_emp_permit'] != 8)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],615)?>><a href="index.php?pages=reportoper9"><i class="fa fa-circle-o"></i><span>รายงานใบเสร็จมือ</span></a></li>
          <li <?=sidemenu2($_GET['pages'],611)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_Receipt_Detail.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ทำฟอร์ม)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],610)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=1" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ปิดทริป)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],613)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=2" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินได้ </span></a></li>
          <li <?=sidemenu2($_GET['pages'],612)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=3" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินไม่ได้ </span></a></li>
          <li <?=sidemenu2($_GET['pages'],6110)?>><a href="index.php?pages=reportoper15"><i class="fa fa-circle-o"></i> <span> รายงานผลประโยชน์เครดิต(ทีม) </span></a></li>
          <li <?=sidemenu2($_GET['pages'],6111)?>><a href="index.php?pages=reportoper16"><i class="fa fa-circle-o"></i> <span> รายงานผลประโยชน์เครดิต(คน) </span></a></li>
          <?php
        }
           ?>
        </ul>
      </li>
      <?php
      }
      if(($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2 )
      || ($_COOKIE['tsr_emp_permit'] == 9) || ($_COOKIE['tsr_emp_permit'] == 16) || ($EmpTypeFa == '1')){
      ?>
      <li <?=sidemenu($_GET['pages'],13)?>>
        <a href="#">
          <i class="fa fa-binoculars"></i><span>รายงานตัวแทนอิสระ</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
          <li <?=sidemenu2($_GET['pages'],1302)?>><a href="index.php?pages=reportfa5"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
          <li <?=sidemenu2($_GET['pages'],1301)?>><a href="index.php?pages=reportfa1"><i class="fa fa-circle-o"></i>ข้อมูลสถานะลูกค้า</a></li>
        </ul>

      </li>
      <?php
      }
      if(($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2 )
      || ($_COOKIE['tsr_emp_permit'] == 9) || ($_COOKIE['tsr_emp_permit'] == 16) || ($EmpTypeOn == 1)){
      ?>
      <li <?=sidemenu($_GET['pages'],14)?>>
        <a href="#">
          <i class="fa fa-child"></i><span>ธุรกิจสัมพันธ์</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">

          <li <?=sidemenu3($_GET['pages'],1401)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานเก็บเงิน/ใบเสร็จ</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li <?=sidemenu2($_GET['pages'],1401)?>><a href="index.php?pages=reporton1"><i class="fa fa-circle-o"></i>เก็บงวดแรกบุคคล</a></li>
              <li <?=sidemenu2($_GET['pages'],1402)?>><a href="index.php?pages=reporton2"><i class="fa fa-circle-o"></i>เก็บงวดแรกรวม</a></li>
            </ul>
          </li>
          <li <?=sidemenu2($_GET['pages'],1403)?>><a href="index.php?pages=reporton3"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
        </ul>

      </li>
      <?php
    }if(($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2 ) || ($_COOKIE['tsr_emp_permit'] == 19 )){
    ?>
      <li <?=sidemenu($_GET['pages'],15)?>>
        <a href="#">
          <i class="fa fa-hand-peace-o"></i><span>BP</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">

          <li <?=sidemenu3($_GET['pages'],1501)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานเก็บเงิน/ใบเสร็จ</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li <?=sidemenu2($_GET['pages'],1501)?>><a href="index.php?pages=reportib1"><i class="fa fa-circle-o"></i>เก็บงวดแรกบุคคล</a></li>
              <!--<li <?=sidemenu2($_GET['pages'],1502)?>><a href="index.php?pages=reportib2"><i class="fa fa-circle-o"></i>เก็บงวดแรกรวม</a></li>-->
            </ul>
          </li>
          <li <?=sidemenu2($_GET['pages'],1503)?>><a href="index.php?pages=reportib3"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
          <li <?=sidemenu2($_GET['pages'],1504)?>><a href="index.php?pages=reportbrn5_test"><i class="fa fa-circle-o"></i>รายงานติดตั้ง(ทุกฝ่าย)</a></li>
        </ul>

      </li>
      <li <?=sidemenu($_GET['pages'],8)?>>
        <a href="#">
          <i class="fa fa-database"></i> <span> งานระบบ </span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">

          <!--<li <?=sidemenu2($_GET['pages'],81)?>><a href="index.php?pages=reportReprint"><i class="fa fa-circle-o"></i>รายงานปริ๊นใบเสร็จ</a></li>-->
          <li <?=sidemenu3($_GET['pages'],81)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานพิมพ์ใบเสร็จ</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                  <li <?=sidemenu2($_GET['pages'],811)?>><a href="index.php?pages=reportReprint"><i class="fa fa-circle-o"></i>ค้นหาตามใบเสร็จ</a></li>
                  <?php
                    if($_COOKIE['tsr_emp_permit'] == 1){
                   ?>
                  <li <?=sidemenu2($_GET['pages'],812)?>><a href="index.php?pages=reportReprint1"><i class="fa fa-circle-o"></i>ค้นหาตามวันที่</a></li>
                  <?php
                    }
                   ?>
                  <li <?=sidemenu2($_GET['pages'],813)?>><a href="index.php?pages=reportReprint2"><i class="fa fa-circle-o"></i>จำนวนพิมพ์ใบเสร็จซ้ำ</a></li>
            </ul>
          </li>
          <li <?=sidemenu3($_GET['pages'],82)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานโอนการ์ด</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                  <li <?=sidemenu2($_GET['pages'],821)?>><a href="index.php?pages=reprotSendCard"><i class="fa fa-circle-o"></i>ข้อมูลโอนการ์ด</a></li>
                  <li <?=sidemenu2($_GET['pages'],822)?>><a href="index.php?pages=reprotSendCard1"><i class="fa fa-circle-o"></i>ค้นหาข้อมูลโอนการ์ด</a></li>
            </ul>
          </li>
          <?php
            if(($_COOKIE['tsr_emp_permit'] == 1)){
           ?>
            <li <?=sidemenu2($_GET['pages'],83)?>><a href="index.php?pages=reportsp1"><i class="fa fa-circle-o"></i>รายงานติดตั้ง(พี่หนุ่ม)</a></li>
            <li <?=sidemenu2($_GET['pages'],83)?>><a href="http://app.thiensurat.co.th/tsr_asp_tsr_Saleadvance/frm_group_Issue.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i> <span> ตั้งค่าแจ้งปัญหา </span></a></li>
            <!--<li <?=sidemenu2($_GET['pages'],84)?>><a href=href="index.php?pages=updatepackage"><i class="fa fa-circle-o"></i> <span> ตั้งค่าแจ้งปัญหา </span></a></li>-->
            <li <?=sidemenu2($_GET['pages'],85)?>><a href="index.php?pages=changeReceiptPeriod"><i class="fa fa-circle-o"></i>แก้ไขงวดใบเสร็จ</a></li>
            <li <?=sidemenu2($_GET['pages'],86)?>><a href="index.php?pages=receiptComeBack"><i class="fa fa-circle-o"></i>ยกเลิกใบเสร็จยกเลิก</a></li>
            <li <?=sidemenu2($_GET['pages'],87)?>><a href="index.php?pages=updateReceiptTable"><i class="fa fa-circle-o"></i>อัพเดดใบเสร็จย้อนหลัง</a></li>
            <?php
          }
            if(($_COOKIE['tsr_emp_permit'] == 1) || ($_COOKIE['tsr_emp_permit'] == 2) ){
             ?>
            <li <?=sidemenu2($_GET['pages'],89)?>><a href="index.php?pages=reportOperPrintReceipt"><i class="fa fa-circle-o"></i>พิมพ์ใบเสร็จ</a></li>
            <li <?=sidemenu2($_GET['pages'],8010)?>><a href="index.php?pages=reportReceiptSendMail"><i class="fa fa-circle-o"></i>รายงานใบเสร็จส่งจดหมาย</a></li>
            <li <?=sidemenu2($_GET['pages'],8011)?>><a href="index.php?pages=reuseproduct"><i class="fa fa-circle-o"></i>ดึงเครื่องกลับ</a></li>
            <li <?=sidemenu2($_GET['pages'],8012)?>><a href="index.php?pages=monitor_contract"><i class="fa fa-circle-o"></i>เทียบสัญญาบ้านแดงบิ๊กเฮด</a></li>
            <li <?=sidemenu2($_GET['pages'],8013)?>><a href="index.php?pages=contractedit"><i class="fa fa-circle-o"></i>ข้อมูลสัญญา</a></li>
            <li <?=sidemenu2($_GET['pages'],8014)?>><a href="index.php?pages=editReceiptManual"><i class="fa fa-circle-o"></i>แก้ไขเลขที่ใบเสร็จมือ</a></li>
            <?php
            }
             ?>
            <li <?=sidemenu2($_GET['pages'],8015)?>><a href="index.php?pages=reportdueturndate"><i class="fa fa-circle-o"></i>รายงานครบกำหนดสารกรอง</a></li>
        </ul>
      </li>
      <?php
    }
    if($_COOKIE['tsr_emp_permit'] == 1 ){
       ?>
      <li <?=sidemenu($_GET['pages'],7)?>>
        <a href="#">
          <i class="fa fa-wrench"></i> <span> ระบบควบคุม </span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <li <?=sidemenu2($_GET['pages'],71)?>><a href="index.php?pages=SetUpUser"><i class="fa fa-circle-o"></i>สิทธ์ผู้ใช้งาน</a></li>

        </ul>
      </li>
      <?php
        }
       ?>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
