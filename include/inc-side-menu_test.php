<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
 ?>

<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="header">เมนู</li>
      <?php
      $conn = connectDB_TSR();

      $sql_case = "SELECT Menu.menuID,menuname,menuLink,menuicon
      FROM TSR_Application.dbo.TSS_M_User AS USERs
      INNER JOIN TSR_Application.dbo.TSS_M_Permit AS PerMit
      ON USERs.permission = perMit.permitAccess
      INNER JOIN TSR_Application.dbo.TSS_M_Menu AS Menu
      ON perMit.menuID = Menu.menuID
      WHERE user_id = (select ad_name from TSR_Application.dbo.employee_data WHERE emp_id = 'A".substr($_COOKIE['tsr_emp_id'],1)."')";

      $stmt = sqlsrv_query($conn,$sql_case);
      while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
      ?>

      <li>
        <a href="<?=$row['menuLink']?>">
          <i class="fa <?=$row['menuicon']?>"></i><span><?=$row['menuname']?></span>
          <i class="fa fa-angle-left pull-right"></i>
        </a>

        <ul class="treeview-menu">
          <!--
          <li>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>ระบบการเก็บเงิน</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                  <li <?=sidemenu2($_GET['pages'],314)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferBigHeadByCarea.aspx?SID=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนเขตย้ายสาย</a></li>
                  <li <?=sidemenu2($_GET['pages'],319)?>>
                  <a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_ByArea1.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนย้ายการ์ดภายในบิ๊กเฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],321)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_RedHouse.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>ข้อมูลโอนบ้านแดง/บิ๊กแฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],320)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_printTranfer_readhouse_barcode.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>พิมพ์รายการโอนบ้านแดง/บิ๊กแฮด</a></li>

                  <li <?=sidemenu2($_GET['pages'],322)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_tranferRedhouseAndBigHead.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนบ้านแดง/บิ๊กแฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],315)?>><a href="index.php?pages=tranCByC"><i class="fa fa-circle-o"></i>โอนลูกค้ารายสัญญา</a></li>
                  <li <?=sidemenu2($_GET['pages'],316)?>><a href="index.php?pages=tranCByCs"><i class="fa fa-circle-o"></i>โอนลูกค้าร้านค้า</a></li>
                   <li <?=sidemenu2($_GET['pages'],318)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_AdAmtByTeam.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>เพิ่มรายการเก็บเงิน</a></li>
                   <li <?=sidemenu2($_GET['pages'],323)?>><a href="index.php?pages=chackContnoBighead"><i class="fa fa-circle-o"></i>ตรวจสอบสถานะสัญญา</a></li>
            </ul>
          </li>
          -->
        </ul>      
      </li>
      <?php
        }
        sqlsrv_close($conn);
      ?>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
