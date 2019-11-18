<?php
require_once('library/HTMLPurifier.auto.php');
$purifier = new HTMLPurifier();
$uniqname = $purifier->purify($_REQUEST['uniqname']);

if (strlen($uniqname) != 0) {
// check the value is a valid uniqname
$ldap = ldap_connect('ldap.itd.umich.edu');
$result = ldap_search($ldap, "ou=People,dc=umich,dc=edu", "(uid=$uniqname)") or die ("Error in search query: ".ldap_error($ldap));
 $data = ldap_get_entries($ldap, $result);
 $count = $data['count'];
 if ($count == 0) {
    $result1 = ldap_search($ldap, "ou=User Groups,ou=Groups,dc=umich,dc=edu", "(cn=$uniqname)") or die ("Error in search query: ".ldap_error($ldap));
    $data1 = ldap_get_entries($ldap, $result1);
    $count1 = $data1['count'];
    if ($count1 == 0) {
         echo 'FALSE';
    }
  }
  else {
       if (strlen($data[0]["cn"][0]) > 0) {
                    $str = explode(" ", $data[0]["cn"][0]);
                    $name = $str[0] . " " . $str[count($str) - 1];
                    echo $name;
        }else{
            echo 'FALSE';
        }
  }
}else{
            echo 'FALSE';
        }
?>
