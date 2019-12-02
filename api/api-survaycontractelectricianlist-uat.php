<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
include_once("../include/inc-fuction.php");

if (isset($_GET['empid'])) {
  $con = connectDB_BigHead();
  //$con = connectDB_BigHeadUAT();
/*
  $SQL = "SELECT distinct Confirm_Install_Date
,installation_staff_name
,Contact_No
,Contact_Name
,Emp.empid
,emp.namethai
,emp.surname
,emp.PositName
,emp.departname
,emp.divisionname
FROM OPENQUERY(
    [192.168.116.21],
    'SELECT Confirm_Install_Date
,replace(replace(CASE WHEN RIGHT(installation_staff_name,1) = '']'' THEN SUBSTRING(installation_staff_name, 0, CHARINDEX(''['', installation_staff_name))
ELSE installation_staff_name END,''ET-'',''''),''AT-'','''') as installation_staff_name
,Contact_No
,Contact_Name
,phone_number
FROM [imind_tsr_db].[dbo].TSR_ReportInstallation(''20190609000000'', ''20191120999999'')
WHERE Credit_Approval_Code LIKE ''L01%''
AND Field_Audit_Code LIKE ''C01%'''
) as oq
LEFT join [TSR_Application].[dbo].[TSR_Full_EmployeeLogic] AS Emp ON oq.installation_staff_name = emp.namethai+' '+emp.surname
WHERE DATEDIFF(DAY,Confirm_Install_Date,GETDATE()) = 0
AND Emp.status = 1
AND Emp.empid = '".$_GET['empid']."'
AND Contact_No NOT IN (SELECT contno
  FROM TSRData_Source.dbo.TSSM_LogSendSmsSurvay
  WHERE DATEDIFF(DAY,stamptime,GETDATE()) = 0
  GROUP BY contno)
ORDER BY Contact_Name
  ";
*/
/*
$SQL = "SELECT distinct Confirm_Install_Date
,installation_staff_name
,Contact_No
,Contact_Name
,Phone
,Address
,Product_Name
,empid
FROM OPENQUERY(
    [192.168.116.21],
    'SELECT 
DISTINCT Confirm_Install_Date
,replace(replace(CASE WHEN RIGHT(installation_staff_name,1) = '']'' THEN SUBSTRING(installation_staff_name, 0
, CHARINDEX(''['', installation_staff_name))
ELSE installation_staff_name END,''ET-'',''''),''AT-'','''') as installation_staff_name
,Contact_No
,Contact_Name
,replace(cPhone,'';'','' ,'') as Phone
,Address
,Product_Name
FROM [imind_tsr_db].[dbo].TSR_ReportInstallation(''20190609000000'', ''20191120999999'')
WHERE Credit_Approval_Code LIKE ''L01%''
AND Field_Audit_Code LIKE ''C01%''
AND (LEFT(installation_staff_name,3) = ''AT-'' OR LEFT(installation_staff_name,3) = ''ET-'')
AND DATEDIFF(DAY,Confirm_Install_Date,GETDATE()) = 0'
) as oq
LEFT join [TSR_Application].[dbo].[TSR_Full_EmployeeLogic] AS Emp ON oq.installation_staff_name = emp.namethai+' '+emp.surname
WHERE Emp.status = 1
AND Emp.empid = '".$_GET['empid']."'
AND Contact_No NOT IN (SELECT contno
  FROM TSRData_Source.dbo.TSSM_LogSendSmsSurvay
  WHERE DATEDIFF(DAY,stamptime,GETDATE()) = 0
  GROUP BY contno)
ORDER BY Contact_Name";
  */
  $SQL = "SELECT [Confirm_Install_Date]
  ,[installation_staff_name]
  ,[Contact_No]
  ,[Contact_Name]
  ,replace([Phone],'-','') as Phone
  ,[Address]
  ,[Product_Name]
  ,[empid]  
FROM [TSRData_Source].[dbo].[TSSM_SurvayContract]
WHERE DATEDIFF(DAY,Confirm_Install_Date,GETDATE()) = 0 
AND empid = '".$_GET['empid']."'
AND Contact_No NOT IN (SELECT contno
FROM TSRData_Source.dbo.TSSM_LogSendSmsSurvay
WHERE DATEDIFF(DAY,stamptime,GETDATE()) = 0
GROUP BY contno) ORDER BY [Contact_Name]
";
  //$con = connectDB_BigHead();

  //echo $SQL;

  $stmt = sqlsrv_query($con,$SQL);
  $result=[];
  //$json_result=$arrayName = array('' => , );
  while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
    $json_result = array('customername'=>$row['Contact_Name']
    ,'contno'=>$row['Contact_No']
    ,'telno'=>$row['Phone']
    ,'address'=>$row['Address']
    ,'productname'=>$row['Product_Name']
    ,'installdate'=>$row['Confirm_Install_Date']
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
