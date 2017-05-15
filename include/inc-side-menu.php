<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="header">เมนู</li>
      <?php
      if (($_COOKIE['tsr_emp_permit']!=4) && ($_COOKIE['tsr_emp_permit']!=3) && ($_COOKIE['tsr_emp_permit']!=5) && ($_COOKIE['tsr_emp_permit']!=6)) {
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
                <li <?=sidemenu2($_GET['pages'],211)?>><a href="index.php?pages=monitordata1"><i class="fa fa-circle-o"></i> ข้อมูลใบสั่งซื้อ</a></li>
                <li <?=sidemenu2($_GET['pages'],212)?>><a href="index.php?pages=monitordata2" target="_blank"><i class="fa fa-circle-o"></i> กระทบรายการเก็บเงิน</a></li>
                <?php
                }
                 ?>
                <li <?=sidemenu2($_GET['pages'],213)?>><a href="index.php?pages=monitorReportCredit1"><i class="fa fa-circle-o"></i> เทียบการเก็บเงินรายวัน</a></li>

            </ul>
          </li>
          <?php
            if (($_COOKIE['tsr_emp_permit']!=3)) {
           ?>
                <li <?=sidemenu2($_GET['pages'],221)?>><a href="http://app.thiensurat.co.th/tsr_monitor_app_sale/" target ="_blank"><i class="fa fa-circle-o"></i>มอนิเตอร์การเก็บเงิน</a></li>
            <?php
            }
             ?>
        </ul>
      </li>
      <?php
        }
        //if (($_COOKIE['tsr_emp_permit']==1)) {
       ?>
       <?php
     //}


           if (($_COOKIE['tsr_emp_permit'] == 4 )) {
             if (substr($_COOKIE['tsr_emp_id'],0,1) == "0") {
               $EmpID['0'] = "A".substr($_COOKIE['tsr_emp_id'],1,5);
               $EmpID['1'] = $_COOKIE['tsr_emp_name'];

               $connss = connectDB_BigHead();
               $sql_Empid = "SELECT SaleCode,ProcessType FROM Bighead_Mobile.dbo.EmployeeDetail WHERE EmployeeCode = '".$EmpID['0']."'";

               //echo $sql_Empid;

               $stmt = sqlsrv_query($connss,$sql_Empid);
               while ($rowss = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
                 $EmpID['2'] = $rowss['SaleCode'];
                 $EmpID['3'] = $rowss['ProcessType'];
               }

               sqlsrv_close($connss);

               $WHERE .= " AND R.ZoneCode = '".$EmpID['2']."'";


             }
           }
      ?>
      <?php
      //echo "EmpID ====".$EmpID['3'];
        if ((($_COOKIE['tsr_emp_permit'] != 4 ) && ($_COOKIE['tsr_emp_permit'] != 5 )) || ($EmpID['3'] == 'Sale' )) {
       ?>
      <li <?=sidemenu($_GET['pages'],1)?>>
        <a href="#">
          <i class="fa fa-money"></i><span>รายงานฝ่ายขาย</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">

          <li <?=sidemenu3($_GET['pages'],11)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>รายงานเก็บเงิน/ใบเสร็จ</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <!--<li <?=sidemenu2($_GET['pages'],111)?>><a href="index.php?pages=reportsale1"><i class="fa fa-circle-o"></i>เก็บงวดแรกรายวัน</a></li>-->
              <li <?=sidemenu2($_GET['pages'],112)?>><a href="index.php?pages=reportsale2"><i class="fa fa-circle-o"></i>เก็บงวดแรกบุคคล</a></li>
              <li <?=sidemenu2($_GET['pages'],113)?>><a href="index.php?pages=reportsale3"><i class="fa fa-circle-o"></i>เก็บงวดแรก(เต็มงวด)</a></li>
              <li <?=sidemenu2($_GET['pages'],116)?>><a href="index.php?pages=reportsale7"><i class="fa fa-circle-o"></i>เก็บงวดแรก(บางส่วนครบ)</a></li>
              <li <?=sidemenu2($_GET['pages'],117)?>><a href="index.php?pages=reportsale8"><i class="fa fa-circle-o"></i>เก็บงวดแรก(ยกเลิก)</a></li>
              <li <?=sidemenu2($_GET['pages'],114)?>><a href="index.php?pages=reportsale4"><i class="fa fa-circle-o"></i><span>เก็บงวดแรกบุคคล(บางส่วน)</span></a></li>
                <li <?=sidemenu2($_GET['pages'],118)?>><a href="index.php?pages=reportsale9"><i class="fa fa-circle-o"></i><span>รายงานใบเสร็จมือ</span></a></li>
              <!--
              <li <?=sidemenu2($_GET['pages'],115)?>><a href="index.php?pages=reportsale5"><i class="fa fa-circle-o"></i><span>รายงานใบเสร็จมือ</span></a></li>-->

            </ul>
          </li>

          <!--<li <?=sidemenu2($_GET['pages'],12)?>><a href="index.php?pages=reportsale1"><i class="fa fa-circle-o"></i>สต๊อกสินค้า</a></li>-->
          <li <?=sidemenu2($_GET['pages'],0)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_SearchContnoEmployee.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>ทะเบียนลูกค้า</a></li>
          <li <?=sidemenu2($_GET['pages'],13)?>><a href="index.php?pages=reportsale5"><i class="fa fa-circle-o"></i>รายงานติดตั้ง</a></li>
          <!--
          <li <?=sidemenu2($_GET['pages'],13)?>><a href="index.php?pages=reportsale6"><i class="fa fa-circle-o"></i>รายงานยกเลิกติดตั้ง</a></li>
          <li <?=sidemenu2($_GET['pages'],14)?>><a href="index.php?pages=reportsale1"><i class="fa fa-circle-o"></i>รายงานสัญญาจริง</a></li>
          <li <?=sidemenu2($_GET['pages'],15)?>><a href="index.php?pages=reportsale1"><i class="fa fa-circle-o"></i>รายงานสัญญาค้าง</a></li>
          <li <?=sidemenu2($_GET['pages'],16)?>><a href="index.php?pages=reportsale1"><i class="fa fa-circle-o"></i>รายงานขายประจำวัน</a></li>
          -->
        </ul>

      </li>
      <?php
    }
       ?>

     <?php
       if ( (($_COOKIE['tsr_emp_permit'] != 4 ) && ($_COOKIE['tsr_emp_permit'] != 5 ) && ($_COOKIE['tsr_emp_permit']!=6)) || ($EmpID['3'] == 'Credit' )) {
         if(substr($_COOKIE['tsr_emp_id'],0,3) != "ZDP" ){
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
          <li <?=sidemenu2($_GET['pages'],42)?>><a href="index.php?pages=reportcredit2"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>
          <li <?=sidemenu2($_GET['pages'],49)?>><a href="index.php?pages=reportcredit2_back"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล(ย้อนหลัง)</span></a></li>
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
          <li <?=sidemenu2($_GET['pages'],46)?>><a href="index.php?pages=reportcredit6"><i class="fa fa-circle-o"></i><span>รายงานการวิ่งงานรายทริป</span></a></li>
          <li <?=sidemenu2($_GET['pages'],410)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_Receipt_Detail.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ทำฟอร์ม)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],411)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=1" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ปิดทริป)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],413)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=2" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินได้ </span></a></li>
          <li <?=sidemenu2($_GET['pages'],412)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=3" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินไม่ได้ </span></a></li>
        </ul>
      </li>
      <?php
        }
      }
        if ( (($_COOKIE['tsr_emp_permit'] != 4 ) && ($_COOKIE['tsr_emp_permit'] != 5 ) && ($_COOKIE['tsr_emp_permit']!=6))|| ($EmpID['3'] == 'DEPT' )) {
      if(substr($_COOKIE['tsr_emp_id'],0,3) != "ZCR" ){
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

          <li <?=sidemenu2($_GET['pages'],510)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_Receipt_Detail.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ทำฟอร์ม)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],511)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=1" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ปิดทริป)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],513)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=2" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินได้ </span></a></li>
          <li <?=sidemenu2($_GET['pages'],512)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=3" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินไม่ได้ </span></a></li>

        </ul>
      </li>

      <?php
        }
      }
      if((substr($_COOKIE['tsr_emp_id'],0,3) != "ZCR") AND (substr($_COOKIE['tsr_emp_id'],0,3) != "ZDP") AND (($_COOKIE['tsr_emp_permit'] == 2) OR ($_COOKIE['tsr_emp_permit'] == 1) OR ($_COOKIE['tsr_emp_permit'] == 5) )){
      ?>
      <li <?=sidemenu($_GET['pages'],6)?>>
        <a href="#">
          <i class="fa fa-fax"></i> <span> รายงานฝ่ายปฏิบัตการ</span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
          <?php
            if (($_COOKIE['tsr_emp_permit']!=4)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],61)?>><a href="index.php?pages=reportoper1"><i class="fa fa-circle-o"></i>รายงานเก็บเงินรายวัน</a></li>
          <?php
        }
           ?>
          <!--<li <?=sidemenu2($_GET['pages'],62)?>><a href="index.php?pages=reportoper2"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>-->
          <li <?=sidemenu2($_GET['pages'],69)?>><a href="index.php?pages=reportoper2_back"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล</span></a></li>
          <?php
            if (($_COOKIE['tsr_emp_permit']!=4)) {
           ?>
          <li <?=sidemenu2($_GET['pages'],67)?>><a href="index.php?pages=reportoper7"><i class="fa fa-circle-o"></i>รายงานเก็บเงินรายวัน(บางส่วน)</a></li>
          <?php
        }
           ?>
          <li <?=sidemenu2($_GET['pages'],68)?>><a href="index.php?pages=reportoper8"><i class="fa fa-circle-o"></i><span>รายงานเก็บเงินรายบุคคล(บางส่วน)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],616)?>><a href="index.php?pages=reportoper10"><i class="fa fa-circle-o"></i><span>การเก็บเงินงวดแรกรายวัน </span></a></li>
          <li <?=sidemenu2($_GET['pages'],617)?>><a href="index.php?pages=reportoper11"><i class="fa fa-circle-o"></i><span>เก็บเงินงวดแรกรายบุคคล </span></a></li>
          <li <?=sidemenu2($_GET['pages'],618)?>><a href="index.php?pages=reportoper12"><i class="fa fa-circle-o"></i><span>เก็บเงินงวดแรกรายวัน(บางส่วน)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],619)?>><a href="index.php?pages=reportoper13"><i class="fa fa-circle-o"></i><span>เก็บเงินงวดแรกรายบุคคล(บางส่วน)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],63)?>><a href="index.php?pages=reportoper3"><i class="fa fa-circle-o"></i><span>รายงานการส่งเงิน</span></a></li>
          <li <?=sidemenu2($_GET['pages'],64)?>><a href="index.php?pages=reportoper4"><i class="fa fa-circle-o"></i><span>รายงานยกเลิกใบเสร็จ</span></a></li>
          <li <?=sidemenu2($_GET['pages'],65)?>><a href="index.php?pages=reportoper5"><i class="fa fa-circle-o"></i><span>รายงานยกเลิกใบเสร็จรายบุคคล</span></a></li>
          <li <?=sidemenu2($_GET['pages'],66)?>><a href="index.php?pages=reportoper6"><i class="fa fa-circle-o"></i><span>รายงานการวิ่งงานรายทริป</span></a></li>
          <li <?=sidemenu2($_GET['pages'],615)?>><a href="index.php?pages=reportoper9"><i class="fa fa-circle-o"></i><span>รายงานใบเสร็จมือ</span></a></li>
          <li <?=sidemenu2($_GET['pages'],611)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_Receipt_Detail.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ทำฟอร์ม)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],610)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=1" target="_blank"><i class="fa fa-circle-o"></i> <span> รายการเก็บเงิน (ปิดทริป)</span></a></li>
          <li <?=sidemenu2($_GET['pages'],613)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=2" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินได้ </span></a></li>
          <li <?=sidemenu2($_GET['pages'],612)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_PayMoneyList.aspx?act=3" target="_blank"><i class="fa fa-circle-o"></i> <span> รายงานรายการเก็บเงินไม่ได้ </span></a></li>
        </ul>
      </li>
      <?php
      }
      if(($_COOKIE['tsr_emp_permit'] == 1) OR ($_COOKIE['tsr_emp_permit'] == 2 )){
      ?>
      <li <?=sidemenu($_GET['pages'],8)?>>
        <a href="#">
          <i class="fa fa-database"></i> <span> รายงานระบบ </span>
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

            <li <?=sidemenu2($_GET['pages'],83)?>><a href="index.php?pages=reportsp1"><i class="fa fa-circle-o"></i>รายงานติดตั้ง(พี่หนุ่ม)</a></li>
            <li <?=sidemenu2($_GET['pages'],83)?>><a href="http://app.thiensurat.co.th/tsr_asp_tsr_Saleadvance/frm_group_Issue.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i> <span> ตั้งค่าแจ้งปัญหา </span></a></li>
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
          <li <?=sidemenu2($_GET['pages'],71)?>><a href="index.php?pages=reportoper1"><i class="fa fa-circle-o"></i>สิทธ์การเข้าถึงเมนู</a></li>

        </ul>
      </li>
      <?php
        }
       ?>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
