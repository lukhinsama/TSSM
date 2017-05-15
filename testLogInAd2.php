<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];

ECHO "USER : ".$username;
ECHO "<BR>PASS : ".$password;

$exp_username=explode('@',$username);
$username=$exp_username[0];
$branch_locator="thiensurat";

$ip_ad=array("thiensurat"=>"192.168.110.102","thiensurat2"=>"192.168.110.102");

$dn_base = 'DC='.$branch_locator.',DC=co,DC=th';
$dn_host = $ip_ad[$branch_locator];
$ldapusers = $username.'@'.$branch_locator.'.co.th';
$ldappasswd = $password;

// basic sequence with LDAP is connect, bind, search, interpret search
// result, close connect.
echo "<BR>Connecting to LDAP Server..$ip_ad[$branch_locator] <br/>";
$ldapconn = ldap_connect($ip_ad[$branch_locator], 389) or die("Could not connect to LDAP Server.");

//echo "Connect status is ".$ldapconn."<br>";

// Set some Variables
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

// Bind to LDAP Server // Binding Anonymously
$ldapbind = ldap_bind($ldapconn ,$ldapusers, $ldappasswd) or die ("0");
//$ldapbind = ldap_bind($ldapconn) or die ("Users or Password Invalid.");
//echo "Connect Authentication is: ".$ldapbind."<p>";
$justthese = array("ou", "sn", "givenname", "mail");
// Search
//$filter = "(&(mail=*)(pager=*))"; // CN = Common Name, SN = Surname , OU = Organization Unit
//$filter = "SAMACCOUNTNAME=$username"; // CN = Common Name, SN = Surname , OU = Organization Unit
 $filter = "userprincipalname=".$username.'@'.$branch_locator.'.co.th'; // CN = Common Name, SN = Surname , OU =
 //$filter="(|(sn=$ldapusers*)(givenname=$ldapusers*))";
// Search surname entry
$result = ldap_search($ldapconn,$dn_base,$filter,$justthese);

//$sr=ldap_search($ds, $dn, $filter, $justthese);

//echo "Search result is ".$result ."<br>";
//var_dump(ldap_count_entries($ldapconn,$result));
$entries = ldap_count_entries($ldapconn,$result );

//echo "Number of entires returned is:".$entries."<p>";
$info = ldap_get_entries($ldapconn, $result );
//echo "<BR>INFO : ".$info["count"];
echo "<BR>Data for " . $info["count"] . " items returned:<p>";

for ($i=0; $i<$info["count"]; $i++) {
    echo "dn is: " . $info[$i]["dn"] . "<br />";
    echo "givenname is: " . $info[$i]["givenname"][0] . "<br />";
    echo "sn is: " . $info[$i]["sn"][0] . "<br />";
    echo "ou is: " . $info[$i]["ou"]. "<br />";
    echo "email is: " . $info[$i]["mail"][0] . "<br /><hr />";
}
//["attribute"]["count"]
/*
for ($i=0; $i<$info["count"]; $i++) {
    //for ($j=0; $j < 6; $i++) {
      echo " is: " . $info[$i]["attribute"]["employeeid"] . "<br />";
      echo " is: " . $info[$i]["attribute"][0] . "<br />";
      echo " is: " . $info[$i]["attribute"][0] . "<br />";
      echo " is: " . $info[$i]["attribute"][0] . "<br />";
      echo " is: " . $info[$i]["attribute"][0] . "<br />";
      echo " is: " . $info[$i]["attribute"][0] . "<br />";
      echo " is: " . $info[$i]["attribute"][0] . "<br />";
      //echo "count ".count($info[$i]);
    //}
}

for ($i=0; $i<$info["count"]; $i++){
    // to show the attribute displayName (note the case!)
    //echo $info[$i][0][0];
    for ($j=0; $j < $info[$i][$j][0]; $j++) {
      echo $info[$i][$j][0]."<BR>";
    }
}
*/
echo "Closing connection";
ldap_close($ldapconn);
//print_r($info["count"]);

?>
