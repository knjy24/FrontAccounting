<?php

define("FA_LOGOUT_PHP_FILE","");

$page_security = 'SA_OPEN';
$path_to_root="..";
include($path_to_root . "/includes/session.inc");
add_js_file('login.js');

include($path_to_root . "/includes/page/header.inc");
page_header(_("Logout"), true, false, '');

echo "<table width='100%' border='0'>
  <tr>
	<td align='center'></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align='center'><font size=2>";
	$mg = "<image src = '$path_to_root/themes/erp/images/logo.png'>";
echo $mg;

//echo "<strong>$app_title</strong>";

echo "</font></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align='center'>";
echo "<a href='$path_to_root/index.php'><b>" . _("Click here to Login Again.") . "</b></a>";
echo "</div></td>
  </tr>
</table>
<br>\n";
end_page(false, true);
session_unset();
@session_destroy();
?>


