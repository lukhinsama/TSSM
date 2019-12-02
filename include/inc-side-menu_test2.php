<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
 ?>

<aside class="main-sidebar">
  <section class="sidebar">
    <ul class="sidebar-menu">
      <li class="header">เมนู</li>

<?php
$conn = connectDB_TSR();
/*
$SQL = "SELECT id as menu_item_id, parent_id as menu_parent_id, title as menu_item_name, [url],menu_order,menu_icon AS icon
FROM TSR_Application.dbo.TSSM_SidebarMenu
ORDER BY menu_order";
*/

$SQL = "SELECT id as menu_item_id, parent_id as menu_parent_id, title as menu_item_name, [url],menu_order,menu_icon AS icon
  FROM [TSR_Application].[dbo].[TSSM_SidebarMenu]
  WHERE id IN (SELECT menuID
  FROM [TSR_Application].[dbo].[TSSM_UserPermit] AS UP
  INNER JOIN TSR_Application.dbo.TSSM_GroupUSERPermit AS GUP ON UP.permitAccess = GUP.id_permit
  INNER JOIN TSR_Application.dbo.TSS_M_User AS U ON u.permission = GUP.id_permit
  WHERE u.emp_id = '".$_COOKIE['tsr_emp_id']."')
  ORDER BY menu_order";

//echo $SQL;

if (empty($_GET['pages'])) {
  $_GET['pages'] = '';
}

$refs = array();
$list = array();

$stmt = sqlsrv_query($conn,$SQL);
while ($data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  //echo $row['menu_item_id'];

  $thisref = &$refs[ $data['menu_item_id'] ];
  $thisref['menu_item_id'] = $data['menu_item_id'];
  $thisref['menu_parent_id'] = $data['menu_parent_id'];
  $thisref['menu_item_name'] = $data['menu_item_name'];
  $thisref['url'] = $data['url'];
  $thisref['icon'] = $data['icon'];

  if ($data['menu_parent_id'] == 0){
      $list[ $data['menu_item_id'] ] = &$thisref;
    }else{
      $refs[ $data['menu_parent_id'] ]['children'][ $data['menu_item_id'] ] = &$thisref;
    }

}
sqlsrv_close($conn);

function create_list( $arr ,$urutan){

  if($urutan==0){
    $html = "\n<ul class='sidebar-menu'>\n";
  }else{
    $html = "\n<ul class='treeview-menu'>\n";
    //$html = "\n<ul ".ActiveSideMenu1($_GET['pages'],$v['menu_item_id']).">\n";
  }

  foreach ($arr as $key=>$v){

    if (array_key_exists('children', $v)){
      //$html .= "<li class='treeview'>\n";
      $html .= "<li ".ActiveSideMenu1($_GET['pages'],$v['menu_item_id']).">\n";
      $html .= '<a href="#">
                    <i class="'.$v['icon'].'"></i>
                    <span>'.$v['menu_item_name'].'</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>';

      $html .= create_list($v['children'],1);
      $html .= "</li>\n";
    }else{
      //$html .= '<li><a href="'.$v['url'].'">';
      //$html .= "<li><a href=\"".$v['url']."\">";
      $html .= "<li ".ActiveSideMenu2($_GET['pages'],$v['menu_item_id'])."><a href=\"".$v['url']."\">";
      if($urutan==0){
        $html .=	"<i class=\"".$v['icon']."\"></i>";
      }
      if($urutan==1){
        $html .=	"<i class=\"fa fa-angle-double-right\"></i>";
      }
      $html .= $v["menu_item_name"]."</a></li>\n";
    }
  }

  $html .= "</ul>\n";
  return $html;
}
echo create_list( $list,0 );
 ?>

</ul>
</section>
</aside>
