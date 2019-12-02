<?php
  ini_set('display_errors', 'on');
  ini_set('error_reporting', E_ALL);

  $con = connectDB_BigHead();
  //$tokenLineTestGroup = "AdSnqZWqrq1KsKCnlzDFiwPvEu73XBZkixb5sFZG1DB";
  //$tokenTessLukhin = 'TgX4Mj5uao3IJz9O6PdkCk5tCy9cEkIg3jysVOCm3AC';


  //ส่งรูปภาพ ฝ่ายขาย
    $line_api = 'https://notify-api.line.me/api/notify';

    //$access_token = $tokenLineTestGroup;

    $message = "รายงานสภาพอากาศวันนี้";    //text max 1,000 charecter
    $image_thumbnail_url = "https://www.tmd.go.th/programs/uploads/satda/latest.jpg?".time();  // max size 240x240px JPEG
    $image_fullsize_url = 'https://www.tmd.go.th/programs/uploads/satda/latest.jpg'; //max size 1024x1024px JPEG
    $imageFile = 'copy/240.jpg';
    $sticker_package_id = '';  // Package ID sticker
    $sticker_id = '';    // ID sticker

    if (function_exists('curl_file_create')) {
    $cFile = curl_file_create($imageFile );
    } else {
    $cFile = '@'.realpath($imageFile );
    }

    $message_data = array(
    'imageThumbnail' => $image_thumbnail_url,
    'imageFullsize' => $image_fullsize_url,
    'message' => $message,
    'imageFile' => $imageFile,
    //'imageFile' => $cFile ,
    'stickerPackageId' => $sticker_package_id,
    'stickerId' => $sticker_id
    );
    $tokenSale = "IwKC3Z39TdQMlWNCGX6nCheXrQnNaytijEJf1hXezIK";
    $result = send_notify_message($line_api, $tokenSale, $message_data);
    $tokenSale = "X06WZHEexNmtc4pJlUCiFnfVHkSxXyMRCmsDE08OnQo";
    $result = send_notify_message($line_api, $tokenSale, $message_data);
    //echo '<pre>';
    //print_r($result);
    //echo '</pre>';

  //ส่งรูปภาพ ฝ่ายขาย

  //ส่งรูปภาพ ฝ่ายสาขา

    //echo '<pre>';
    //print_r($result);
    //echo '</pre>';

  //ส่งรูปภาพ ฝ่ายสาขา

  sqlsrv_close($con);

  function send_notify_message($line_api, $access_token, $message_data){
     $headers = array('Method: POST', 'Content-type: multipart/form-data', 'Authorization: Bearer '.$access_token );

     $ch = curl_init();
     curl_setopt($ch, CURLOPT_URL, $line_api);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_POSTFIELDS, $message_data);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $result = curl_exec($ch);
     // Check Error
     if(curl_error($ch))
     {
        $return_array = array( 'status' => '000: send fail', 'message' => curl_error($ch) );
     }
     else
     {
        $return_array = json_decode($result, true);
     }
     curl_close($ch);
  return $return_array;
  }

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

   	$db_host = "192.168.110.133";
   	$db_name = "Bighead_Mobile";
   	$db_username = "TsrApp";
   	$db_password = "6z3sNrCzWp";

   	$connectionInfo = array("Database"=>$db_name, "UID"=>$db_username, "PWD"=>$db_password, 'CharacterSet' => 'UTF-8', "MultipleActiveResultSets"=>true);
     $conn = sqlsrv_connect( $db_host, $connectionInfo);

   	if( $conn === false ) {
       die( print_r( sqlsrv_errors(), true));
   	}

   	return $conn;
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
   ?>
