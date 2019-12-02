<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

if (isset($_GET['contno'])) {
  $con = connectDB_BigHead();
  //$con = connectDB_BigHeadUAT();
/*
  $SQL = "SELECT 'กรุณากรอกเบอร์โทร' AS Phone
UNION ALL
SELECT distinct replace(phone_number,'-','') AS Phone
FROM OPENQUERY(
    [192.168.116.21],
    'SELECT Confirm_Install_Date
,replace(replace(CASE WHEN RIGHT(installation_staff_name,1) = '']'' THEN SUBSTRING(installation_staff_name, 0, CHARINDEX(''['', installation_staff_name))
ELSE installation_staff_name END,''ET-'',''''),''AT-'','''') as installation_staff_name
,Contact_No
,Contact_Name
,phone_number
FROM [imind_tsr_db].[dbo].TSR_ReportInstallation(''20190609000000'', ''20190909999999'')
WHERE Credit_Approval_Code LIKE ''L01%''
AND Field_Audit_Code LIKE ''C01%'''
) as oq
WHERE oq.Contact_No = '".$_GET['contno']."' AND LEN(oq.phone_number) >= 10
  ";
*/
  $SQL = "SELECT 'กรุณากรอกเบอร์โทร' AS telno
  UNION ALL
  SELECT DISTINCT LTRIM(RTRIM(p.name)) AS telno
  FROM [TSRData_Source].[dbo].[TSSM_SurvayContract] as C
  OUTER APPLY (SELECT * FROM TSRData_Source.dbo.splitstring(C.[Phone])) as P 
  WHERE Contact_No = '".$_GET['contno']."' AND LEN(LTRIM(RTRIM(p.name))) >= 10";
  //$con = connectDB_BigHead();

  //echo $SQL;

  $stmt = sqlsrv_query($con,$SQL);
  $result=[];
  //$json_result=$arrayName = array('' => , );
  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $json_result = array('telno'=>$row['telno']
    ,'contno'=>$_GET['contno']
    );

    array_push($result,$json_result);
  }

  sqlsrv_close($con);
  echo json_encode(array('data' => $result));
  //echo json_encode($json_result);
}else {
  echo "ERROR";
}


 function DateThaiAPI($strDate){
 		$strDate = date_format(date_create($strDate),"Y-m-d H:i:s");

 		$strYear = date("Y",strtotime($strDate))+543;
 		$strMonth= date("n",strtotime($strDate));
 		$strDay= date("j",strtotime($strDate));
 		$strHour= date("H",strtotime($strDate));
 		$strMinute= date("i",strtotime($strDate));
 		$strSeconds= date("s",strtotime($strDate));
 		//$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
 		//$strMonthThai=$strMonthCut[$strMonth];
 		//return "$strDay $strMonthThai $strYear, $strHour:$strMinute";

    if (strlen($strMonth) == 1) {
      $strMonth = "0".$strMonth;
    }
 		return "$strDay/$strMonth/$strYear";
 }
?>
