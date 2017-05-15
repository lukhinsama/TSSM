<?php
function connectDB_TSR(){
	//config DB
	$db_host='TSR-SQL02-PRD';
	$db_name='TSR_Application';
	$db_username='tsr_application';
	$db_password='thiens1234';
	//$db_username='adisorn.c';
	//$db_password=']^dsbo8iy[';

	$connectionInfo = array("Database"=>$db_name, "UID"=>$db_username, "PWD"=>$db_password, 'CharacterSet' => 'UTF-8', "MultipleActiveResultSets"=>true);
  $conn = sqlsrv_connect( $db_host, $connectionInfo);

	if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true));
	}

	return $conn;
}

function connectDB_HR(){
	//config DB
	$db_host='TSR-SQL02-PRD';
	$db_name='HR_TSR_2016';
	$db_username='tsr_application';
	$db_password='thiens1234';
	//$db_username='adisorn.c';
	//$db_password=']^dsbo8iy[';

	$connectionInfo = array("Database"=>$db_name, "UID"=>$db_username, "PWD"=>$db_password, 'CharacterSet' => 'UTF-8', "MultipleActiveResultSets"=>true);
  $conn = sqlsrv_connect( $db_host, $connectionInfo);

	if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true));
	}

	return $conn;
}


function connectDB_BigHead(){
	//config DB
	//$db_host='203.150.54.248';
	//$db_host='192.168.110.133';
	//$db_host='203.154.204.241';

	$db_host='bof.thiensurat.co.th';
	$db_name='Bighead_Mobile';
	$db_username='TsrApp';
	$db_password='6z3sNrCzWp';
	//$db_username='adisorn.c';
	//$db_password=']^dsbo8iy[';

	$connectionInfo = array("Database"=>$db_name, "UID"=>$db_username, "PWD"=>$db_password, 'CharacterSet' => 'UTF-8', "MultipleActiveResultSets"=>true);
  $conn = sqlsrv_connect( $db_host, $connectionInfo);

	if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true));
	}

	return $conn;
}

function pagelimit($pages,$num_row,$page,$q,$startDate,$endDate,$searchText){
global $limit_per_page;

 $total_pages = ceil($num_row / $limit_per_page);
 //$q=base64_encode($q);

    $range = 2;
    $navi_start = $page - $range;
    $navi_end = $page + $range;

		$output = "<div class=\"box-footer clearfix\"><ul class=\"pagination pagination-sm no-margin pull-right\">";

    if ($navi_start <= 0)
        $navi_start = 1;
    if ($navi_end >= $total_pages)
        $navi_end = $total_pages;
    if ($page > 1) {
        $navi_back = $page - 1;
        if ($page > 2)
				$output .= "<li><a href=\"?pages=$pages&page=1&q=$q&startDate=$startDate&endDate=$endDate&searchText=$searchText\">&laquo;&laquo;</a></li>";
				$output .= "<li><a href=\"?pages=$pages&page=$navi_back&q=$q&startDate=$startDate&endDate=$endDate&searchText=$searchText\">&laquo;</a></li>";
        //$output .= "<a href=\"?pages=$pages&page=1&q=$q\" class=\"btn btn-outline btn-primary\">หน้าแรก</a> ";
        //$output .= "<a href=\"?pages=$pages&page=$navi_back&q=$q\" class=\"btn btn-outline btn-primary\">ก่อนหน้า</a> ";
    }
    for ($i = $navi_start; $i <= $navi_end; $i++) {
        if ($i == $page)
					$output .= "<li class=\"active\"><a href=\"?pages=$pages&page=$i&q=$q&startDate=$startDate&endDate=$endDate&searchText=$searchText\">$i</a></li>";
            //$output .= "<a href=\"?pages=$pages&page=$i&q=$q\" class=\"btn btn-primary\">$i</a> ";
        else
					$output .= "<li><a href=\"?pages=$pages&page=$i&q=$q&startDate=$startDate&endDate=$endDate&searchText=$searchText\">$i</a></li>";
            //$output .= "<a href=\"?pages=$pages&page=$i&q=$q\" class=\"btn btn-outline btn-primary\">$i</a> ";
    }
    if ($page < $total_pages) {
        $navi_next = $page + 1;
				$output .= "<li><a href=\"?pages=$pages&page=$navi_next&q=$q&startDate=$startDate&endDate=$endDate&searchText=$searchText\">&raquo;</a></li>";
        //$output .= "<a href=\"?pages=$pages&page=$navi_next&q=$q\"class=\"btn btn-outline btn-primary\" >ถัดไป</a> ";
        if (($page + 1) < $total_pages){
					$output .= "<li><a href=\"?pages=$pages&page=$total_pages&q=$q&startDate=$startDate&endDate=$endDate&searchText=$searchText\">&raquo;&raquo;</a></li>";
					//$output .= "<a href=\"?pages=$pages&page=$total_pages&q=$q\" class=\"btn btn-outline btn-primary\">หน้าสุดท้าย</a>";
				}
    }
		$output .= "</ul></div>";
    return $output;
}

function dateConvert($searchDate){
	$date = date_format(date_create($searchDate),"Y-m-d H:i:s");
	return $date;
}

function DateEng($strDate){


		$strYear = substr($strDate,6,4);
		$strMonth= substr($strDate,3,2);
		$strDay= substr($strDate,0,2);

		$strYear = $strYear - 543;
		$strDate = date_format(date_create($strYear."-".$strMonth."-".$strDay),"Y-m-d");
		//$strHour= date("H",strtotime($strDate));
		//$strMinute= date("i",strtotime($strDate));
		//$strSeconds= date("s",strtotime($strDate));
		//$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		//$strMonthThai=$strMonthCut[$strMonth];
		//return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
		return $strDate;
}

function DateThai($strDate){
		$strDate = date_format(date_create($strDate),"Y-m-d H:i:s");

		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		//return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
		return "$strDay $strMonthThai $strYear";
}


function DateTimeThai($strDate){
		$strDate = date_format(date_create($strDate),"Y-m-d H:i:s");

		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear $strHour:$strMinute";
}

function DateThaiLong($strDate){
		$strDate = date_format(date_create($strDate),"Y-m-d H:i:s");

		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤศภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤษจิกายน","ธันวาคม");
		$strMonthThai=$strMonthCut[$strMonth];
		//return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
		return "$strDay $strMonthThai $strYear";
}

function highlightKeyword($text, $words) {
    preg_match_all('~\w+~', $words, $m);
    if(!$m)
        return $text;
    $re = '~\\b(' . implode('|', $m[0]) . ')\\b~';
    return preg_replace($re, '<B>$0</B>', $text);
}

function write_data_for_export_excel($data , $file_name){

    if (is_writable($file_name)) {
        $obj = fopen($file_name , 'w');
        fwrite($obj , $data);
        fclose($obj);
    }else{
        echo "error";
    }
}

function generate_page_excel($report_type) {
  global $page, $record_limit_per_page, $q,$poll_id,$start_date,$end_date,$phone,$operator,$service_id,$urluse,$keywords;

  $output = "export_excel.php?report_type=$report_type";
  return $output;
}

function check_login($user){
	if(empty($user)){
			header( "Location: login.php" );
	}
	if (empty($_COOKIE['tsr_emp_permit'])) {
			header( "Location: login.php" );
	}
}

function sidemenu($pages,$position){
	switch ($pages) {
			case "monitordata1":
				if ($position == 2) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "monitordata2":
				if ($position == 2) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "monitorReportCredit1":
				if ($position == 2) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "sorting":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "sorting1":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "tranCByE":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "tranCByS":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "tranCByC":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "tranCByCs":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "chkdateKep":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "addKep":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "chackContnoBighead":
				if ($position == 3) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale1":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale2":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale3":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale4":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale5":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale6":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale7":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale8":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale9":
				if ($position == 1) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit1":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit2":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit2_back":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit3":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit4":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit5":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit6":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit7":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportcredit8":
				if ($position == 4) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper1":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper2":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper2_back":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper3":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper4":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper5":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper6":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper7":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper8":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper9":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper10":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper11":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper12":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportoper13":
				if ($position == 6) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept1":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept2":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept2_back":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept3":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept4":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept5":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept6":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept7":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportdept8":
				if ($position == 5) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reprotSendCard":
				if ($position == 8) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reprotSendCard1":
				if ($position == 8) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportReprint":
				if ($position == 8) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportReprint1":
				if ($position == 8) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportReprint2":
				if ($position == 8) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;

	    default:
				if ($position == 0) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
	}
	return $menu;
}

function sidemenu3($pages,$position){
	switch ($pages) {
			case "monitordata1":
				if ($position == 21) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "monitordata2":
				if ($position == 21) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "monitorReportCredit1":
				if ($position == 21) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "sorting":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "sorting1":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "tranCByE":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "tranCByS":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "tranCByC":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "tranCByCs":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "chkdateKep":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "addKep":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "chackContnoBighead":
				if ($position == 31) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale1":
				if ($position == 11) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale2":
				if ($position == 11) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale3":
				if ($position == 11) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale7":
				if ($position == 11) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale8":
				if ($position == 11) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale9":
				if ($position == 11) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportsale":
				if ($position == 11) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reprotSendCard":
				if ($position == 82) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportReprint":
				if ($position == 81) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportReprint1":
				if ($position == 81) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportReprint2":
				if ($position == 81) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reprotSendCard1":
				if ($position == 82) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;

	    default:
				if ($position == 0) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
	}
	return $menu;
}

function sidemenu2($pages,$position){
	switch ($pages) {
			case "monitordata1":
				if ($position == 211) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "monitordata2":
				if ($position == 212) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "monitorReportCredit1":
				if ($position == 213) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "sorting":
				if ($position == 311) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "sorting1":
				if ($position == 312) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "tranCByE":
				if ($position == 313) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "tranCByS":
				if ($position == 314) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "tranCByC":
				if ($position == 315) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "tranCByCs":
				if ($position == 316) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "chkdateKep":
				if ($position == 317) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "addKep":
				if ($position == 318) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "chackContnoBighead":
				if ($position == 323) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportsale1":
				if ($position == 111) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportsale2":
				if ($position == 112) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportsale3":
				if ($position == 113) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportsale4":
				if ($position == 114) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportsale5":
				if ($position == 13) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportsale7":
				if ($position == 116) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportsale8":
				if ($position == 117) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportsale9":
				if ($position == 118) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportcredit1":
				if ($position == 41) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportcredit2":
				if ($position == 42) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportcredit2_back":
				if ($position == 49) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportcredit3":
				if ($position == 43) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportcredit4":
				if ($position == 44) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportcredit5":
				if ($position == 45) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportcredit6":
				if ($position == 46) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportcredit7":
				if ($position == 47) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
				break;
			case "reportcredit8":
				if ($position == 48) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper1":
				if ($position == 61) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper2":
				if ($position == 62) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper2_back":
				if ($position == 69) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper3":
				if ($position == 63) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper4":
				if ($position == 64) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper5":
				if ($position == 65) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper6":
				if ($position == 66) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper7":
				if ($position == 67) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
				break;
			case "reportoper8":
				if ($position == 68) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper9":
				if ($position == 615) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper10":
				if ($position == 616) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper11":
				if ($position == 617) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper12":
				if ($position == 618) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportoper13":
				if ($position == 619) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportdept1":
				if ($position == 51) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportdept2":
				if ($position == 52) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportdept2_back":
				if ($position == 59) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportdept3":
				if ($position == 53) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportdept4":
				if ($position == 54) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportdept5":
				if ($position == 55) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportdept6":
				if ($position == 56) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportdept7":
				if ($position == 57) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
				break;
			case "reportdept8":
				if ($position == 58) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reprotSendCard":
				if ($position == 821) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
			case "reportReprint":
				if ($position == 811) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportReprint1":
				if ($position == 812) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reportReprint2":
				if ($position == 813) {
					$menu = "class=\"active treeview\"";
				}else {
					$menu = "class=\"treeview\"";
				}
					break;
			case "reprotSendCard1":
				if ($position == 822) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
					break;
	    default:
				if ($position == 0) {
					$menu = "class=\"active\"";
				}else {
					$menu = "";
				}
	}
	return $menu;
}

function chk_member($emp_id){
	$conn = connectDB_HR();
	$sql = "SELECT EmpID,a.CompID as CompID,a.Sex as Sex,a.PositionID as PositionID,EmpFNameT+' '+EmpLNameT as EmpName,PositionNameT as EmpPosition,PartNameT as EmpDepartment,(select PartNameT FROM [HR_TSR_2016].[dbo].[tbMTPart] WHERE c.PartRef = PartID AND CompID = a.CompID) as EmpFaction,'' as EmpBossID ,'' as EmpBossName
	, (SELECT EmpStatus FROM [TSR_Application].[dbo].[HR_Leave_Profile] WHERE EmpID = a.EmpID ) EmpStatusMenu ,PositionDate as EmpComeIn,DATEDIFF(YEAR,PositionDate,GETDATE()) as yearin ,DATEDIFF(MONTH,PositionDate,GETDATE()) as monthin ,CONVERT(VARCHAR(10),PositionDate,105) as EmpCome,Images as EmpImage,StdCh3 as inTime
	,stdch4 as outTime FROM [HR_TSR_2016].[dbo].[tbMTEmpMain] as a Left JOIN [HR_TSR_2016].[dbo].[tbMTPosition] as b ON a.CompID = b.CompID AND a.PositionID=b.PositionID Inner join [HR_TSR_2016].[dbo].[tbMTPart] as c ON a.CompID = c.CompID and a.Level04 = c.PartID Left JOIN [HR_TSR_2016].[dbo].[tbMTWorkTime] as d ON a.CompID = d.CompID  WHERE EmpID = '".$emp_id."' AND d.Wtcode in (SELECT top 1 Wtcode FROM [HR_TSR_2016].[dbo].[tbTRTimeTransaction] WHERE a.EmpID = EmpID)";

	$stmt = sqlsrv_query( $conn, $sql );
	if ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
		setcookie("tsr_leave_id", $emp_id,time() + (86400) );
		setcookie("tsr_leave_name", $row['EmpName'],time() + (86400) );
		setcookie("tsr_leave_pos", $row['EmpPosition'] , time() + (86400));
		setcookie("tsr_leave_dep", $row['EmpDepartment'] , time() + (86400));
		setcookie("tsr_leave_fact", $row['EmpFaction'] , time() + (86400));
		setcookie("tsr_leave_boss", $row['EmpBossName'] , time() + (86400));
		setcookie("tsr_leave_date", $row['EmpCome'] , time() + (86400));
		setcookie("tsr_leave_yein", $row['yearin'] , time() + (86400));
		setcookie("tsr_leave_moin", $row['monthin'] , time() + (86400));
		setcookie("tsr_leave_sex", $row['Sex'] , time() + (86400));
		setcookie("tsr_leave_comp", $row['CompID'] , time() + (86400));
		setcookie("tsr_leave_intime", $row['inTime'] , time() + (86400));
		setcookie("tsr_leave_outtime", $row['outTime'] , time() + (86400));
		setcookie("tsr_leave_status", $row['EmpStatusMenu'] , time() + (86400));

		sqlsrv_close($conn);

		//ตรวจสอบว่าเคยมีข้อมูลปีปัจจุบันในระบบหรือไม่
		$sql_profile = "SELECT Top 1 EmpID FROM TSR_Application.dbo.HR_Leave_Profile WHERE EmpID = '".$emp_id."'";
		//echo $sql_profile;
		$conn = connectDB_TSR();
		$stmt = sqlsrv_query( $conn, $sql_profile );
		if ($row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
				//มีข้อมูล
				sqlsrv_close($conn);
				header( "Location:index.php" );
		}else {
				//ไม่มีข้อมูล ให้เพิ่มข้อมูล
				$sql_insert = "INSERT INTO TSR_Application.dbo.HR_Leave_Profile (EmpID,CompID,EmpName,EmpSex) VALUES (?,?,?,?)";
				echo $sql_insert;
				$params = array($emp_id,$row['CompID'],$row['EmpName'],$row['Sex']);
				print_r($params);
				$stmt_insert = sqlsrv_query( $conn, $sql_insert, $params);

				if( $stmt_insert === false ) {
					 die( print_r( sqlsrv_errors(), true));
				}else {
					//คำนวนวันลาพักร้อน


					if ($row['yearin'] < 1) {
						$Vacation = 0;
					}else {
						# code...
						$level = substr($row['PositionID'],1,2);
						if ($level > "09") {
							# code...
							if ($row['yearin'] <= 4) {
								$Vacation = 12;
							}elseif ($row['yearin'] <= 9) {
								# code...
								$Vacation = 15;
							}else {
								# code...
								$Vacation = 20;
							}
						}elseif ($level > "07") {
							# code...
							if ($row['yearin'] <= 4) {
								$Vacation = 10;
							}elseif ($row['yearin'] <= 9) {
								# code...
								$Vacation = 12;
							}else {
								# code...
								$Vacation = 15;
							}
						}elseif ($level > "05") {
							# code...
							if ($row['yearin'] <= 4) {
								$Vacation = 8;
							}elseif ($row['yearin'] <= 9) {
								# code...
								$Vacation = 10;
							}else {
								# code...
								$Vacation = 12;
							}
						}else {
							# code...
							if ($row['yearin'] <= 4) {
								$Vacation = 6;
							}elseif ($row['yearin'] <= 9) {
								# code...
								$Vacation = 8;
							}else {
								# code...
								$Vacation = 10;
							}
						}

					}

					//คำนวนวันลาพักร้อน
					//คำนวนวันลาอุปสมบท
					if ($row['yearin'] < 1) {
						# code...
						$Ordain = 7;
					}elseif ($row['yearin'] <= 2) {
						# code...
						$Ordain = 15;
					}elseif ($row['yearin'] <= 5) {
						# code...
						$Ordain = 30;
					}else {
						# code...
						$Ordain = 90;
					}
					//คำนวนวันลาอุปสมบท
					//คำนวนวันลาไปศึกษา
					if ($row['yearin'] >= 2) {
						# code...
						$Study = 10;
					}else {
						# code...
						$Study = 0;
					}
					//คำนวนวันลาไปศึกษา

					//เพิ่มวันลาประจำปี
					$sql_leave = "SELECT leaveTypeID,leave_day,leave_Cat FROM TSR_Application.dbo.HR_Leave_Type";
					//echo $sql_profile;
					$stmt_leave = sqlsrv_query( $conn, $sql_leave );
					while ($row_leave = sqlsrv_fetch_array( $stmt_leave, SQLSRV_FETCH_ASSOC)) {
						//$row_leave['leaveTypeID'];
						//$row_leave['leave_Cat'];
						if ($row_leave['leaveTypeID'] == 3) {
							$row_leave['leave_day'] = $Ordain;
						}
						if ($row_leave['leaveTypeID'] == 6) {
							$row_leave['leave_day'] = $Study;
						}
						if ($row_leave['leaveTypeID'] == 7) {
							$row_leave['leave_day'] = $Vacation;
						}

						if ($row_leave['leave_Cat'] === "A") {
							$leave_day = $row_leave['leave_day'];
						}else {
							if ($row['Sex'] == $row_leave['leave_Cat']) {
								$leave_day = $row_leave['leave_day'];
							}else {
								$leave_day = 0;
							}
						}

						//เพิ่มวันลาประจำปี
						$sql_insert = "INSERT INTO TSR_Application.dbo.HR_Leave_Alldate (EmpID,YearLeave,leaveTypeID,leaveDayAll,totalDay) VALUES (?,?,?,?,?)";
						$params = array($emp_id,$row_leave['leaveTypeID'],$leave_day,$leave_day);
						$stmt_insert = sqlsrv_query( $conn_tsr, $sql_insert, $params);

					}


					sqlsrv_close($conn);
					header( "Location:index.php" );
				}
		}
	}else {
		# code...
		  echo "<script type='text/javascript'>alert('ไม่พบชื่อผู้ใช้นี้ในระบบ')</script>";
	}
}

function chkFileImage($img){
	$filename = "image/profile/".$img.".jpg";
	$part = "";
	if (file_exists($filename)) {
	    $part = "image/profile/".$img.".jpg";
	} else {
	   $part = "image/profile/icon.jpg";
	}
	return $part;
}

function ShowDateLeave($leaveTypeE,$row_profile,$num){
	switch ($leaveTypeE) {
		case 'Sick':
			switch ($num) {
				case '1':
					echo $row_profile['Sick'];
					break;
				case '2':
					echo $row_profile['Sick_use_day'];
					break;
				case '3':
					echo $row_profile['Sick_use_hour'];
					break;
				case '4':
					echo "0";
					break;
				case '5':
					echo "0";
					break;
			}
			break;
		case 'Errand':
			switch ($num) {
				case '1':
					echo $row_profile['Errand'];
					break;
				case '2':
					echo $row_profile['Errand_use_day'];
					break;
				case '3':
					echo $row_profile['Errand_use_hour'];
					break;
				case '4':
					echo "0";
					break;
				case '5':
					echo "0";
					break;
			}
			break;
		case 'Ordain':
			switch ($num) {
				case '1':
					echo $row_profile['Ordain'];
					break;
				case '2':
					echo $row_profile['Ordain_use_day'];
					break;
				case '3':
					echo "0";
					break;
				case '4':
					echo $row_profile['Ordain'] - $row_profile['Ordain_use_day'];
					break;
				case '5':
					echo "0";
					break;
			}
			break;
		case 'Calve':
			switch ($num) {
				case '1':
					echo $row_profile['Calve'];
					break;
				case '2':
					echo $row_profile['Calve_use_day'];
					break;
				case '3':
					echo "0";
					break;
				case '4':
					echo $row_profile['Calve'] - $row_profile['Calve_use_day'];
					break;
				case '5':
					echo "0";
					break;
			}
			break;
		case 'Soldier':
			switch ($num) {
				case '1':
					echo $row_profile['Soldier'];
					break;
				case '2':
					echo $row_profile['Soldier_use_day'];
					break;
				case '3':
					echo "0";
					break;
				case '4':
					echo $row_profile['Soldier'] - $row_profile['Soldier_use_day'];
					break;
				case '5':
					echo "0";
					break;
			}
			break;
		case 'Study':
			switch ($num) {
				case '1':
					echo $row_profile['Study'];
					break;
				case '2':
					echo $row_profile['Study_use_day'];
					break;
				case '3':
					echo $row_profile['Study_use_hour'];
					break;
				case '4':
					echo "0";
					break;
				case '5':
					echo "0";
					break;
			}
			break;
		case 'Vacation':
			switch ($num) {
				case '1':
					echo $row_profile['Vacation'];
					break;
				case '2':
					echo $row_profile['Vacation_use_day'];
					break;
				case '3':
					echo "0";
					break;
				case '4':
				 	echo $row_profile['Vacation'] - $row_profile['Vacation_use_day'];
					break;
				case '5':
					echo "0";
					break;
			}
			break;
		case 'Sterilization':
			switch ($num) {
				case '1':
					echo $row_profile['Sterilization'];
					break;
				case '2':
					echo $row_profile['Sterilization_use_day'];
					break;
				case '3':
					echo "0";
					break;
				case '4':
					echo $row_profile['Sterilization'] - $row_profile['Sterilization_use_day'];
					break;
				case '5':
					echo "0";
					break;
			}
			break;
		}
}

function statusLabelTime($timein,$timeout,$LateHrs,$LateMin,$LeaveHrs,$LeaveMin,$DayType){
	if (empty($timein)) {
		$timein = "00:00";
	}
	if (empty($timeout)) {
		$timeout = "00:00";
	}
	$Late = ShowTimeFormat($LateHrs).":".ShowTimeFormat($LateMin);
	$Leave = ShowTimeFormat($LeaveHrs).":".ShowTimeFormat($LeaveMin);
	if ($DayType == "O") {
		$status = "class=\"label label-success\"";
		$text = "ปกติ";
		$textTimeIn = "style=\"color:black;\"";
		$textTimeOut = "style=\"color:black;\"";
	}elseif ($DayType == "A") {
		$status = "class=\"label label-danger\"";
		$text = "ขาดงาน";
		$textTimeIn = "style=\"color:red;\"";
		$textTimeOut = "style=\"color:red;\"";
	}else {
		if (($LateHrs != 0) || ($LateMin != 0)) {
			$status = "class=\"label label-warning\"";
			$text = "เข้าสาย";
			$textTimeIn = "style=\"color:red;\"";
			$textTimeOut = "style=\"color:black;\"";
		}elseif (($LeaveHrs != 0) || ($LeaveMin != 0)) {
			$status = "class=\"label label-warning\"";
			$text = "ลางาน";
			$textTimeIn = "style=\"color:black;\"";
			$textTimeOut = "style=\"color:red;\"";
		}else {
			$status = "class=\"label label-success\"";
			$text = "ปกติ";
			$textTimeIn = "style=\"color:black;\"";
			$textTimeOut = "style=\"color:black;\"";
		}
	}
	return array($status,$text,$textTimeIn,$textTimeOut,$timein,$timeout,$Late,$Leave);
}

function statusLabelLeave($status){
	if ($status == "1") {
		$labalStatus = "class=\"label label-info\"";
		$labalText = "รอผู้รับงานแทน";
		$disableType = "";
	}elseif ($status == "2") {
		$labalStatus = "class=\"label label-warning\"";
		$labalText = "รอหัวหน้าอนุมัติ";
		$disableType = "disabled";
	}elseif ($status == "3") {
		$labalStatus = "class=\"label label-warning\"";
		$labalText = "รอผู้จัดการอนุมัติ";
		$disableType = "disabled";
	}elseif ($status == "4") {
		$labalStatus = "class=\"label label-warning\"";
		$labalText = "รอฝ่ายบุคคลอนุมัติ";
		$disableType = "disabled";
	}elseif ($status == "5") {
		$labalStatus = "class=\"label label-success\"";
		$labalText = "ฝ่ายบุคคลอนุมัติ";
		$disableType = "disabled";
	}elseif ($status == "6") {
		$labalStatus = "class=\"label label-danger\"";
		$labalText = "ฝ่ายบุคคลไม่อนุมัติ";
		$disableType = "disabled";
	}elseif ($status == "7") {
		$labalStatus = "class=\"label label-danger\"";
		$labalText = "หัวหน้าไม่อนุมัติ";
		$disableType = "disabled";
	}elseif ($status == "8") {
		$labalStatus = "class=\"label label-danger\"";
		$labalText = "ผู้จัดการไม่อนุมัติ";
		$disableType = "disabled";
	}elseif ($status == "9") {
		$labalStatus = "class=\"label label-danger\"";
		$labalText = "ไม่ยินยอมรับงานแทน";
		$disableType = "disabled";
	}else {
		$labalStatus = "class=\"label label-danger\"";
		$labalText = "ยกเลิกการลา";
		$disableType = "disabled";
	}
	return array($labalStatus,$labalText,$disableType);
}

function statusLabelAccept($status,$pointer){
	if ($status < $pointer) {
		$labalStatus = "class=\"label label-success\"";
		$labalText = "ยินยอมรับงานแทน";
	}else {
		$labalStatus = "class=\"label label-danger\"";
		$labalText = "ไม่ยินยอมรับงานแทน";
	}
	return array($labalStatus,$labalText);
}

function statusLabelLeaveHR($status,$pointer){
	if ($status < $pointer) {
		$labalStatus = "class=\"label label-success\"";
		$labalText = "ยินยอมรับงานแทน";
	}else {
		$labalStatus = "class=\"label label-danger\"";
		$labalText = "ไม่ยินยอมรับงานแทน";
	}
	return array($labalStatus,$labalText);
}


function statusLabelApprove($status,$pointer){
	if ($status < $pointer) {
		$labalStatus = "class=\"label label-success\"";
		$labalText = "อนุมัติลา";
	}else {
		$labalStatus = "class=\"label label-danger\"";
		$labalText = "ไม่อนุมัติลา";
	}
	return array($labalStatus,$labalText);
}

function numDateLeave($dayLeave,$hourLeave){

	if ($dayLeave > 0) {
		$numDateLeave = $dayLeave." วัน";
	}else {
		$numDateLeave = "";
	}
	$hour = explode(".",$hourLeave);
	if ($hour[0] > 0) {
		$numDateLeave .= $hour[0]." ชั่วโมง";
	}
	if (isset($hour[1])) {
		$numDateLeave .= "30 นาที";
	}
	return $numDateLeave;
}

function ShowTimeFormat($num){
	if (strlen($num) < 2) {
		$time = "0".$num;
	}else {
		$time = $num;
	}
	return $time;
}

function convertTime($time){
	$times = explode(":",$time);
	if ($times[1] > 0) {
		$minWork = 0.5;
	}else {
		$minWork = 0.0;
	}
	$hour = $times[0];
	$min = $minWork;
	return array($hour,$min);
}

function checkNumRow($con,$sql){
	$param = array();
	$option =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$query = sqlsrv_query($con,$sql,$param,$option );
	$num_row = sqlsrv_num_rows($query);
	return $num_row;
}

function DateDiff($strDate1,$strDate2){
			 return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
	}
function TimeDiff($strTime1,$strTime2){
		 return (strtotime($strTime2) - strtotime($strTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
}
function DateTimeDiff($strDateTime1,$strDateTime2){
		 return (strtotime($strDateTime2) - strtotime($strDateTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
}


function SendMail($logLeaveID){
	require("../PHPMailer_v5.0.2/class.phpmailer.php");
	if ($logLeaveID === "NEW") {
		$top = "TOP 1";
		$LeaveID = "";
	}else {
		$top = "";
		$LeaveID = "AND a.logLeaveID = '".$logLeaveID."'";
	}

	$mail = new PHPMailer();

	$conn = connectDB_TSR();
	$sql = "SELECT $top (SELECT leaveTypeT FROM [TSR_Application].[dbo].[HR_Leave_Type] where a.leaveTypeID = leaveTypeID) as leaveTypeT ,CONVERT(varchar,startDate,105) as startDate ,CONVERT(varchar(5),startDate,108) as timeStart , CONVERT(varchar,endDate,105) as endDate ,CONVERT(varchar(5),endDate,108) as timeEnd ,logLeaveDeteil ,dayLeave ,hourLeave ,totalDay ,totalHour ,case statusLeave when '2' then (SELECT ad_accountname FROM [TSR_Application].[dbo].[employee_data] WHERE emp_id = ('A'+SUBSTRING(a.headEmpID1,2,5)))
	when '3' then (SELECT ad_accountname FROM [TSR_Application].[dbo].[employee_data] WHERE emp_id = ('A'+SUBSTRING(a.headEmpID2,2,5)))
	else (SELECT ad_accountname FROM [TSR_Application].[dbo].[employee_data] WHERE emp_id = ('A'+SUBSTRING(a.agreeWorkID,2,5)))
	end as Mail ,case statusLeave
	when '2' then (SELECT ad_name FROM [TSR_Application].[dbo].[employee_data] WHERE emp_id = ('A'+SUBSTRING(a.headEmpID1,2,5)))
	when '3' then (SELECT ad_name FROM [TSR_Application].[dbo].[employee_data] WHERE emp_id = ('A'+SUBSTRING(a.headEmpID2,2,5)))
	else (SELECT ad_name FROM [TSR_Application].[dbo].[employee_data] WHERE emp_id = ('A'+SUBSTRING(a.agreeWorkID,2,5)))
	end as ad_name
	FROM [TSR_Application].[dbo].[HR_Leave_Log_sys] as a LEFT OUTER JOIN [TSR_Application].[dbo].[HR_Leave_DayAll] as b ON a.empLeaveID=b.EmpID AND b.leaveTypeID = a.leaveTypeID WHERE empLeaveID = '".$_COOKIE['tsr_leave_id']."' AND b.EmpID = a.empLeaveID $LeaveID order by logLeaveID desc ";
	$stmt = sqlsrv_query( $conn, $sql );
	while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {

		$url = "http://app.thiensurat.co.th/tsr_leave/pages/pageMail.php?startDate=".$row['startDate']."&endDate=".$row['endDate']."&timeStart=".$row['timeStart']."&timeEnd=".$row['timeEnd']."&leaveTypeT=".urlencode($row['leaveTypeT'])."&logLeaveDeteil=".urlencode($row['logLeaveDeteil'])."&dayLeave=".$row['dayLeave']."&hourLeave=".$row['hourLeave']."&totalDay=".$row['totalDay']."&totalHour=".$row['totalHour']."&tsr_leave_name=".urlencode($_COOKIE['tsr_leave_name'])."&tsr_leave_pos=".urlencode($_COOKIE['tsr_leave_pos'])."";

		$body = file_get_contents($url, "", $optional_headers = null);

		$mail->CharSet = "utf-8";
		$mail->IsSMTP();
		$mail->IsHTML(true);

		$mail->SMTPDebug = 0;
		$mail->SMTPAuth = true;
		$mail->Host = "smtp.thiensurat.co.th"; // SMTP server
		$mail->Port = 25; // พอร์ท
		$mail->Username = "adisorn.c@thiensurat.co.th"; // account SMTP
		$mail->Password = "]^dsbo8iy["; // รหัสผ่าน SMTP

		$mail->SetFrom("adisorn.c@thiensurat.co.th", "TSRLeave");
		$mail->AddReplyTo("adisorn.c@thiensurat.co.th", "TSRLeave");
		$mail->Subject = "ระบบลางานออนไลน์ (TSRLeave)";

		//$mail->MsgHTML($body);
		//$mail->Subject = $Subject;
		$mail->Body    = $body;

		//$mail->AddAddress("adisorn.c@thiensurat.co.th", "adisorn.c"); // ผู้รับคนที่หนึ่ง
		$mail->AddAddress($row['Mail'], $row['ad_name']); // ผู้รับคนที่สอง

		if(!$mail->Send()) {
				echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
				echo "Message sent!";
		}

	}

}

?>
