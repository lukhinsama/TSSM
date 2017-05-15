<?php
ini_set('memory_limit','128M');

$reports = array(1=>"ReportCreditPar" , 2=>"report_sub_unsub" , 3=>"report_charge_log" , 4=>"report_charge_error" , 5=>"report_msisdn");
if(isset($_REQUEST['report_type']) && is_numeric($_REQUEST['report_type'])){
	$report_type = $_REQUEST['report_type'];

}
header("Content-Type: application/vnd.ms-excel ;");
//header('Content-Disposition: attachment; filename="Service ' . $service_name . " CP " .$start_date . " - " . $end_date . '.xls"');
header('Content-Disposition: attachment; filename="' . $reports[$report_type] . '.xls"');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="Generator" content="Microsoft Excel 10">
	<style>
	body{
		font-family : arial;
		font-size : 0.9em;
		line-height: 130%;
	}
	table , td{
		border : 1px solid black;
		border-collapse:collapse;
	}
	.Y{background-color:#ccccff;color:#ffffff;border-collapse:collapse;}
	.Z{background-color: #d6d6e4;}
	</style>

</head>
<body>
	<?php
	$http="<table width=\"100%\" border=\"1\">
                  <tbody><tr>
                    <td>พนักงานเก็บเงิน</td>
                    <td></td>
                    <td>จำนวนใบเสร็จ</td>
                    <td>จำนวนสัญญา</td>
                    <td>รวมจำนวนเงิน</td>
                  </tr> <tr>
                  <td>10101036</td>
                  <td>บัญญัติ แซ่เตีย</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,000.00</td>
                </tr><tr>
                  <td>10103947</td>
                  <td>อนุสรณ์ นัยนา</td>
                  <td>2</td>
                  <td>2</td>
                  <td>1,500.00</td>
                </tr><tr>
                  <td>10126091</td>
                  <td>บัญชา แซ่เตีย</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,200.00</td>
                </tr><tr>
                  <td>10201018</td>
                  <td>โกวิท คิดจำนงค์</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,000.00</td>
                </tr><tr>
                  <td>10201072</td>
                  <td>นพดล หอมเย็น</td>
                  <td>1</td>
                  <td>1</td>
                  <td>500.00</td>
                </tr><tr>
                  <td>10301906</td>
                  <td>พชร กันตะเพ็ชร</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,000.00</td>
                </tr><tr>
                  <td>10303127</td>
                  <td>ศุภณัฐ ชื่นกรมรักษ์</td>
                  <td>1</td>
                  <td>1</td>
                  <td>500.00</td>
                </tr><tr>
                  <td>10303130</td>
                  <td>กฤษณะ แตงสมุทร</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,400.00</td>
                </tr><tr>
                  <td>10402125</td>
                  <td>อำนาจ ชาวทำนา</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,000.00</td>
                </tr><tr>
                  <td>10403015</td>
                  <td>เอกศิษฐ์ ศศิอภินันท์สุข</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,000.00</td>
                </tr><tr>
                  <td>10403030</td>
                  <td>ถาวร สมานเพ็ชร</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,000.00</td>
                </tr><tr>
                  <td>10601075</td>
                  <td>ประสพ ศาสนเมธา</td>
                  <td>1</td>
                  <td>1</td>
                  <td>400.00</td>
                </tr><tr>
                  <td>10602060</td>
                  <td>ยุทธนา สอนศรี</td>
                  <td>1</td>
                  <td>1</td>
                  <td>400.00</td>
                </tr><tr>
                  <td>10702038</td>
                  <td>พงษ์เทพ ลาพึง</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,000.00</td>
                </tr><tr>
                  <td>10702049</td>
                  <td>สวัสเทพ พันธุ์ใหม่</td>
                  <td>1</td>
                  <td>1</td>
                  <td>500.00</td>
                </tr><tr>
                  <td>10703932</td>
                  <td>อนุวัฒน์ สุดทองคง</td>
                  <td>1</td>
                  <td>1</td>
                  <td>1,000.00</td>
                </tr></tbody></table>";
echo 				$http;
	$file_name = $reports[$report_type];
	$content = file_get_contents($file_name);
	echo $content;
	?>
</body>
</html>
