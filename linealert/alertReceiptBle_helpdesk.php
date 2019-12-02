<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

//include("../include/inc-fuction.php");

define('LINE_API',"https://notify-api.line.me/api/notify");
$token = "oDZJZrBfmxfZu3LIMBhQEkz5PRzvvXqgcCi1sAaR7Ip";

$con = connectDB_BigHead();

//แจ้งงานใหม่
$SQL = "SELECT a.id
,b.first_name,b.last_name
,a.topic
,a.created_date
from helpdesk_sys a
left join employee_data b on a.user_staff = b.emp_id
where a.status=1
and job_status = 11 and category is not null
ORDER BY id desc";

$result = mysqli_query($con, $SQL);

$messageHead = "แจ้งงานใหม่ !! \r\n";
$message = "";
if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $message .= "<IssueNo>".$row['id']." <หัวข้อ> ".$row['topic']." <วันที่สร้าง> ".$row['created_date']." <ชื่อ> ".$row['first_name']." \r\n";
  }

} else {
    echo "0 results";
}

$msg = $messageHead." ".$message;

if (!empty($message)) {
  notify_message($msg,"","",$token);
}
//แจ้งงานใหม่

mysqli_close($con);

function notify_message($message,$stickerPkg,$stickerId,$token){
     $queryData = array(
      'message' => $message,
      'stickerPackageId'=>$stickerPkg,
      'stickerId'=>$stickerId
     );
     $queryData = http_build_query($queryData,'','&');
     $headerOptions = array(
         'http'=>array(
             'method'=>'POST',
             'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                 ."Authorization: Bearer ".$token."\r\n"
                       ."Content-Length: ".strlen($queryData)."\r\n",
             'content' => $queryData
         ),
     );
     $context = stream_context_create($headerOptions);
     $result = file_get_contents(LINE_API,FALSE,$context);
     $res = json_decode($result);
  return $res;
 }

 function connectDB_BigHead(){

 	$db_host = "192.168.110.134";
 	$db_name = "tsr_helpdesk";
 	$db_username = "root";
 	$db_password = "thiens1234";

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn,"utf8");

 	return $conn;
 }

 ?>
