<?php 

if( isset($_POST['id'])){
  $id = $_POST['id'];
 }else{
  $id = $_POST['id'];
 }
$url = "https://tssm.thiensurat.co.th/api/customerreceiptapi.php?contno=".$id;
$curl_handle=curl_init();
curl_setopt($curl_handle, CURLOPT_URL,"$url");
curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false );
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl_handle, CURLOPT_HEADER, false);
$postoffice_data = curl_exec($curl_handle);
curl_close($curl_handle); 
$postoffice_data = json_decode($postoffice_data);
echo json_encode($postoffice_data);

?>