<?php

$ch = curl_init();
$text = "webuser=monoinfo&webpass=2105&partner_id=2105&telno=66849537900&message=5555|Uknow Games&msgtype=T&sender=MonoMobile&opr=1&transact_id=123456";
extract($_REQUEST);
curl_setopt($ch, CURLOPT_URL,"http://partner.monoinfosystems.com/insbo_coperate/insbo_coperate_api_app.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $text);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec ($ch);

echo ">>>>".$server_output." ------ ".$text;
curl_close ($ch);
 ?>
