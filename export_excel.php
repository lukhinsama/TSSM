<?php
ini_set('memory_limit','128M');

$reports = array(1=>"ReportCreditPar" , 2=>"ReportCreditPar2" , 3=>"ReportCreditPar3" , 4=>"ReportCreditPar4" , 5=>"report_msisdn");
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
	$file_name = $reports[$report_type];
	$content = file_get_contents($file_name);
	echo $content;
	?>
</body>
</html>
