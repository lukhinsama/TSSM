<?php
session_start();
//session_destroy();
header("Content-Type: text/html; charset=utf-8");


include('include/lib.php');
if(strcmp($cookie_emp_id,"")!=0){
$emp_id=$cookie_emp_id;
$info["count"]=1;
goto check_id;
}
//$username="xerox";
//$password="thiensmail";
//	 $username ="xerox";
//	 $password="thiensmail";
$exp_username=explode('@',$username);
 $username=$exp_username[0];
$branch_locator="thiensurat";

//$ip_ad=array("thiensurat"=>"192.168.110.102","thiensurat2"=>"192.168.110.101");

$dn_base = 'DC='.$branch_locator.',DC=co,DC=th';
//$dn_host = $ip_ad[$branch_locator];
$dn_host = "thiensurat.co.th";
 $ldapusers = $username.'@'.$branch_locator.'.co.th';
 $ldappasswd = $password;

// basic sequence with LDAP is connect, bind, search, interpret search
// result, close connect.
//echo "Connecting to LDAP Server..$ip_ad[$branch_locator] <br/>";
//$ldapconn = ldap_connect($ip_ad[$branch_locator], 389) or die("Could not connect to LDAP Server.");
$ldapconn = ldap_connect($dn_host, 389) or die("Could not connect to LDAP Server.");

//echo "Connect status is ".$ldapconn."<br>";

// Set some Variables
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

// Bind to LDAP Server // Binding Anonymously

$ldapbind = ldap_bind($ldapconn ,$ldapusers, $ldappasswd) or die ("0");
//$ldapbind = ldap_bind($ldapconn) or die ("Users or Password Invalid.");
//echo "Connect Authentication is: ".$ldapbind."<p>";

// Search
//$filter = "(&(mail=*)(pager=*))"; // CN = Common Name, SN = Surname , OU = Organization Unit
//$filter = "SAMACCOUNTNAME=$username"; // CN = Common Name, SN = Surname , OU = Organization Unit
 $filter = "userprincipalname=".$username.'@'.$branch_locator.'.co.th'; // CN = Common Name, SN = Surname , OU =
// Search surname entry
$result = ldap_search($ldapconn,$dn_base,$filter);
//echo "Search result is ".$result ."<br>";
$entries = ldap_count_entries($ldapconn,$result );
//echo "Number of entires returned is:".$entries."<p>";
$info = ldap_get_entries($ldapconn, $result );
//echo "<pre>";
//print_r($info);
//echo "</pre>";
for ($i=0; $i<$info["count"]; $i++){
// Variable
$userNameID = $info[$i]["pager"][0];
$userAccount = $info[$i]["samaccountname"][0];
$firstName = $info[$i]["givenname"][0];
$sureName = $info[$i]["sn"][0];
$fullName = $info[$i]["displayname"][0];
$eMail = $info[$i]["mail"][0];
$position = $info[$i]["title"][0];
$location = $info[$i]["physicaldeliveryofficename"][0];
$tel = $info[$i]["telephonenumber"][0];
$emp_id = $info[$i]["employeeid"][0];
//echo $info[$i]["pager"][0]."&nbsp;&nbsp;".$info[$i]["displayname"][0]."<br>";
	$_SESSION["displayname"]=$info[$i]["displayname"][0];
	$_SESSION["userprincipalname"]=$info[$i]["userprincipalname"][0];
	$_SESSION["ad_name"]=$userAccount ;
	$_SESSION["emp_id"]=$emp_id ;
}
check_id:
	if($info["count"]!=0)
	{

		$sql="SELECT *,a.emp_position_1 position_id,b.divisionname section_name,b.* FROM employee_data a  join Division b on a.emp_section=b.divisionid and a.emp_company=b.comid where a.emp_id like'%$emp_id' or ad_name='$username'";
		$value=Nget($sql);
		$val=$value[0];
		//userprincipalname
		$_SESSION['user_validation']=789456123;
		$_SESSION['emp_id']=$val['emp_id'];
		$_SESSION['division_id']=$val['divisionid'];
		$_SESSION['depart_id']=$val['departid'];
		$_SESSION['company_id']=$val['comid'];
			$_SESSION['emp_section']=$val['emp_section'];
			$_SESSION['user_id']=$val['id'];
		$division=explode("=",$p_type[1]);
							$_SESSION['branch_id']=$val['branch_id'];
					$_SESSION['section_id']=$val['section_id'];
					/*
								$_SESSION['section_name']=iconv("tis-620","utf-8",$val['section_name']);
		$_SESSION['abs_position_name']=iconv("tis-620","utf-8",$val['position_name']);
		$_SESSION['position_name']=iconv("tis-620","utf-8",$val['emp_position_name']);
		$_SESSION['fname']=iconv("tis-620","utf-8",$val['first_name']);
		$_SESSION['lname']=iconv("tis-620","utf-8",$val['last_name']);
					*/
			$_SESSION['section_name']=$val['section_name'];
		$_SESSION['abs_position_name']=$val['position_name'];
		$_SESSION['position_id']=$val['position_id'];
		$_SESSION['position_name']=$val['emp_position_name'];
		$_SESSION['fname']=$val['first_name'];
		$_SESSION['lname']=$val['last_name'];
				$_SESSION['telno_1']=$val['telno_1'];
								$_SESSION['telno_2']=$val['telno_2'];

/*		$_SESSION['user_id']=$info[0]["usncreated"][0];
		$p_type=explode(",", $info[0]["distinguishedname"][0]);
		$division=explode("=",$p_type[1]);
		$_SESSION['personal_type']=$division[1];
		$_SESSION['fname']=$info[0]["givenname"][0];
		$_SESSION['lname']=$info[0]["sn"][0];*/
		//$info[0]["displayname"][0];

		echo json_encode($_SESSION);
		//json_decode($jd);
	//	echo $section_name;
	}
	else echo "0"; // cancel bc check login above line 32 ^

//print_r($_SESSION);

?>
