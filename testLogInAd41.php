<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
<?php
//ini_set('display_errors', 'on');
//ini_set('error_reporting', E_ALL);

$username = $_REQUEST["username"];
$password = $_REQUEST["password"];

$exp_username=explode('@',$username);
$username=$exp_username[0];
$branch_locator="thiensurat";

$dn_base = 'DC='.$branch_locator.',DC=co,DC=th';
$dn_host = "thiensurat.co.th";
$ldapusers = $username.'@'.$branch_locator.'.co.th';
$ldappasswd = $password;

// basic sequence with LDAP is connect, bind, search, interpret search
// result, close connect.
echo "Connecting to LDAP Server.. <br/>";
$ldapconn = ldap_connect($dn_host, 389) or die("Could not connect to LDAP Server.");

echo "Connect status is ".$ldapconn."<br>";

// Set some Variables
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

// Bind to LDAP Server // Binding Anonymously

$ldapbind = ldap_bind($ldapconn ,$ldapusers, $ldappasswd) or die ("Users or Password Invalid.");
echo "Connect Authentication is: ".$ldapbind."<p>";

// Search
//$filter = "(&(mail=*)(pager=*))"; // CN = Common Name, SN = Surname , OU = Organization Unit
$filter = "userprincipalname=".$username.'@'.$branch_locator.'.co.th';
$justthese = array("ou", "sn", "givenname", "mail");
// Search surname entry
$result = ldap_search($ldapconn,$dn_base,$filter);
echo "Search result is ".$result ."<br>";
$entries = ldap_count_entries($ldapconn,$result );
echo "Number of entires returned is:".$entries."<p>";
$info = ldap_get_entries($ldapconn, $result );

for ($i=0; $i<$info["count"]; $i++){
// Variable
echo "++++++++++++++++++++++++++++++++++++++++++++++";
echo "<BR> 1. ".$userNameID = $info[$i]["pager"][0];
echo "<BR> 2. ".$userAccount = $info[$i]["samaccountname"][0];
echo "<BR> 3. ".$firstName = $info[$i]["givenname"][0];
echo "<BR> 4. ".$sureName = $info[$i]["sn"][0];
echo "<BR> 5. ".$fullName = $info[$i]["displayname"][0];
echo "<BR> 6. ".$eMail = $info[$i]["mail"][0];
echo "<BR> 7. ".$position = $info[$i]["title"][0];
echo "<BR> 8. ".$location = $info[$i]["physicaldeliveryofficename"][0];
echo "<BR> 9. ".$tel = $info[$i]["ou"][0];
echo "<BR> 10. ".$tel = $info[$i]["dc"][0];
echo "<BR> 11. ".$tel = $info[$i]["cn"][0];
echo "<BR> 12. ".$tel = $info[$i]["dn"];
echo "<BR> 13. ".$tel = $info[$i]["empployeeID"];
//echo "<option value=\"". $info[$i]["displayname"][0] ."\">".$info[$i]["pager"][0]."&nbsp;&nbsp;".$info[$i]["displayname"][0]."</option>";
echo "<BR>++++++++++++++++++++++++++++++++++++++++++++++";
}

?>
