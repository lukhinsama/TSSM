<?php
 class postRequest
 {
  function do_post_request($url, $data, $optional_headers = null)
   {

      $ch = curl_init($url);

      curl_setopt ($ch, CURLOPT_POST, 1);
      curl_setopt ($ch, CURLOPT_POSTFIELDS, "data=$data");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

      $content=curl_exec( $ch );

      curl_close ($ch);
    //  print_r($content);
      $allservice=split("\|\|",$content);
    //  print_r($allservice);
     //  echo "<br><br><br><br><br>";
      $reArray=array();
 //echo "start<br>";
    foreach ($allservice as &$node) {

     list($service, $operator,$amoutLine,$modify,$application)=split(',',$node);
//echo $service."<br>";
     list($tempName, $service_id)=split('=',$service);
     list($tempOpr, $operator_id)=split('=',$operator);
     list($tempamoutLine, $amoutLineValue)=split('=',$amoutLine);
     list($tempmodify, $modifyValue)=split('=',$modify);
     list($tempapplication, $applicationValue)=split('=',$application);

     $reArray[$service_id][$operator_id]["amoutLine"][$applicationValue]=$amoutLineValue;
     $reArray[$service_id][$operator_id]["modify"][$applicationValue]=$modifyValue;


    }
    //print_r($reArray);
    //echo "<br><br><br><br><br>";
      return $reArray;
   }
   function do_post_request_re($url, $data, $optional_headers = null)
   {

      $ch = curl_init($url);

      curl_setopt ($ch, CURLOPT_POST, 1);
      curl_setopt ($ch, CURLOPT_POSTFIELDS, "data=$data");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

      $content=curl_exec( $ch );
      curl_close ($ch);

      return $content;
   }
 }
?>
