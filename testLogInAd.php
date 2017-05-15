<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);
$username = $_REQUEST["username"];
$pass = $_REQUEST["password"];
echo "user : ".$username." pass :". $pass;


if($username !=null and $pass !=null){
   $server = "thiensurat.co.th";  //dc1-nu
   $user = $username."@thiensurat.co.th";
   echo "<BR> USER : ".$user;
// connect to active directory

   $ad = ldap_connect($server);
   echo "<br> AD : ".$ad;

   if(!$ad){
      die("Connect not connect to ".$server);
   //   include("chk_login_db.php");
   echo "ไม่สามารถติดต่อ server ได้";
   exit();
  }else{

    $b = ldap_bind($ad,$user,$pass);
    echo "<BR> ldap_bind : ".$b."<BR>";
    print_r($b);

    if(!$b) {

      echo "<BR> ท่านกรอกรหัสผ่านผิดพลาด";
      /*
  		die("<br><br>
  			<div align='center'>    ท่านกรอกรหัสผ่านผิดพลาด
  			<br>
  			</div>
  			<meta http-equiv='refresh' content='3 ;url=index.php'>");
        */
      }else{
      $info = ldap_get_entries($ad, $result );
			//login ผ่านแล้วมาทำไรก็ว่าไป
      echo "<BR> LOGIN สำเร็จ";
			//session_start();
		  }
/*
	echo "<script type=text/javascript>";
	echo "alert('ยินดีต้อนรับ ')";
	echo "</script>";
*/
	//echo "<meta http-equiv='refresh' content='0 ;url= index.php?case_i=13'>";
	exit();

	}

}
?>
