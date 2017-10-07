<?php

echo "<script>function set_encoder_input(text) {document.forms.encoder.input.value = text;}</script><center><b>Mã Hóa:</b></center><form name=\"encoder\" method=\"POST\"><b>Input:</b><center><textarea name=\"encoder_input\" id=\"input\" cols=50 rows=5>".@htmlspecialchars($encoder_input)."</textarea><br><br><input type=submit value=\"calculate\"><br><br></center><b>Hashes</b>:<br><center>";
 foreach(array("md5","crypt","sha1","crc32") as $v)
 {
  echo $v." - <input type=text size=50 onFocus=\"this.select()\" onMouseover=\"this.select()\" onMouseout=\"this.select()\" value=\"".$v($encoder_input)."\" readonly><br>";
 }
 echo "</center><b>Url:</b><center><br>urlencode - <input type=text size=35 onFocus=\"this.select()\" onMouseover=\"this.select()\" onMouseout=\"this.select()\" value=\"".urlencode($encoder_input)."\" readonly>
 <br>urldecode - <input type=text size=35 onFocus=\"this.select()\" onMouseover=\"this.select()\" onMouseout=\"this.select()\" value=\"".urldecode($encoder_input)."\" readonly>
 <br></center><b>Base64:</b><center>base64_encode - <input type=text size=35 onFocus=\"this.select()\" onMouseover=\"this.select()\" onMouseout=\"this.select()\" value=\"".base64_encode($encoder_input)."\" readonly></center>";
 echo "<center>base64_decode - ";
 if (base64_encode(base64_decode($encoder_input)) != $encoder_input) {echo "<input type=text size=35 value=\"failed\" disabled readonly>";}
 else
 {
  $debase64 = base64_decode($encoder_input);
  $debase64 = str_replace("\0","[0]",$debase64);
  $a = explode("\r\n",$debase64);
  $rows = count($a);
  $debase64 = htmlspecialchars($debase64);
  if ($rows == 1) {echo "<input type=text size=35 onFocus=\"this.select()\" onMouseover=\"this.select()\" onMouseout=\"this.select()\" value=\"".$debase64."\" id=\"debase64\" readonly>";}
  else {$rows++; echo "<textarea cols=\"40\" rows=\"".$rows."\" onFocus=\"this.select()\" onMouseover=\"this.select()\" onMouseout=\"this.select()\" id=\"debase64\" readonly>".$debase64."</textarea>";}
  echo "&nbsp;<a href=\"#\" onclick=\"set_encoder_input(document.forms.encoder.debase64.value)\"><b>^</b></a>";
 }
 echo "</center><br><b>Base convertations</b>:<center>dec2hex - <input type=text size=35 onFocus=\"this.select()\" onMouseover=\"this.select()\" onMouseout=\"this.select()\" value=\"";
 $c = strlen($encoder_input);
 for($i=0;$i<$c;$i++)
 {
  $hex = dechex(ord($encoder_input[$i]));
  if ($encoder_input[$i] == "&") {echo $encoder_input[$i];}
  elseif ($encoder_input[$i] != "\\") {echo "%".$hex;}
 }
 echo "\" readonly><br></center></form>";