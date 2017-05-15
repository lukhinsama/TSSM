<?
require("../insbo_sps/phpmailer/class.phpmailer.php");
include("postRequest.php");
$postRequest=new postRequest();
echo $d=$postRequest->do_post_request_re('http://360.monoinfosystems.com/insbo_monitor/total_charge_alert_monitor.php', "", $optional_headers = null);
//echo $d=$postRequest->do_post_request_re('http://360.monoinfosystems.com/insbo_monitor/test_charge_alert_monitor.php', "", $optional_headers = null);


    $message = new PHPMailer();
    $message->From= "adisorn@mono.co.th";
    $message->FromName = "Charge  Monitor (Game)";

    //$message->AddAddress("teerawut@monotechnology.com","???b);
    //$message->AddAddress("jirasak@monotechnology.com","????);
    //$message->AddAddress("mtit_vas@monotechnology.com","IT VAS");
    //$message->AddAddress("aekavut.g@mono.co.th","???");
    //$message->AddAddress("hataitip@monotechnology.com","???");
    //$message->AddAddress("saran@mono.co.th","????");
    //$message->AddAddress("issaranupong@mono.co.th","???);
    $message->AddAddress("sakunrudee@mono.co.th","พี่โอ๋");
    $message->AddAddress("kanda@mono.co.th","พี่โบล์");
    $message->AddAddress("adisorn@mono.co.th","น้องหิน");
    $message->AddAddress("pimoke.t@mono.co.th","pimoke");
    $message->AddAddress("ornwanya.c@mono.co.th","น้องเอ๊ะ");

    $message->Subject = "สรุปประจำวัน Charge  Monitor (Game)".date("Y-m-d H:i:s");
    $headers = "From: Charge  Monitor (Game)  \n";
    $message->IsHTML(true);
    $message->CharSet = "windows-874";
    $message->Body = $d;
    $message->Mailer = "smtp";
    //$message->Host = "mail.monotechnology.com";
    //$message->Host = "mail.monoinfosystems.com";
    //$message->Host = "localhost";
    $message->Host = "smtp.mono.co.th";
    #$RETURN = $message->Send();


     if(!$message->Send()) {
      echo " Mailer Error: " . $message->ErrorInfo."<br>";
     } else {
      echo " Message has been sent Sucsess<br>";
     }



?>
