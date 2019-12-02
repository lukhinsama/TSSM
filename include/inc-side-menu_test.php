<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="header">เมนู</li>

      <!-- Loop สร้างเมนู -->
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

                  <li <?=sidemenu2($_GET['pages'],319)?>>
                  <a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_ByArea1.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนย้ายการ์ดภายในบิ๊กเฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],321)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_RedHouse.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>ข้อมูลโอนบ้านแดง/บิ๊กแฮด</a></li>

            </ul>

          </li>

          <li <?=sidemenu3($_GET['pages'],31)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>ระบบการเก็บเงิน</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>

            <ul class="treeview-menu">

                  <li <?=sidemenu2($_GET['pages'],319)?>>
                  <a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_ByArea1.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนย้ายการ์ดภายในบิ๊กเฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],321)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_RedHouse.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>ข้อมูลโอนบ้านแดง/บิ๊กแฮด</a></li>

            </ul>

          </li>

        </ul>

      </li>

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

                  <li <?=sidemenu2($_GET['pages'],319)?>>
                  <a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_ByArea1.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนย้ายการ์ดภายในบิ๊กเฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],321)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_RedHouse.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>ข้อมูลโอนบ้านแดง/บิ๊กแฮด</a></li>

            </ul>

          </li>

          <li <?=sidemenu3($_GET['pages'],31)?>>
            <a href="#">
              <i class="fa fa-circle-o"></i><span>ระบบการเก็บเงิน</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>

            <ul class="treeview-menu">

                  <li <?=sidemenu2($_GET['pages'],319)?>>
                  <a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_ByArea1.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>โอนย้ายการ์ดภายในบิ๊กเฮด</a></li>
                  <li <?=sidemenu2($_GET['pages'],321)?>><a href="http://app.thiensurat.co.th/tsr_ASP_tsr_SaleAdvance/frm_TranferAllRefNo_RedHouse.aspx?sid=<?=$_COOKIE['tsr_emp_id']?>" target="_blank"><i class="fa fa-circle-o"></i>ข้อมูลโอนบ้านแดง/บิ๊กแฮด</a></li>

            </ul>

          </li>

        </ul>

      </li>
      <!-- Loop สร้างเมนู -->

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
