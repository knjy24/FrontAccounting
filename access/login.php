<?php

	if (!isset($path_to_root) || isset($_GET['path_to_root']) || isset($_POST['path_to_root']))
		die(_("Restricted access"));
	include_once($path_to_root . "/includes/ui.inc");
	include_once($path_to_root . "/includes/page/header.inc");

	$js = "<script language='JavaScript' type='text/javascript'>
function defaultCompany()
{
	document.forms[0].company_login_name.options[".$_SESSION["wa_current_user"]->company."].selected = true;
}
</script>";
	add_js_file('login.js');
	// Display demo user name and password within login form if "$allow_demo_mode" is true
	if ($allow_demo_mode == true)
	{
	    $demo_text = _("Login as user: demouser and password: password");
	}
	else
	{
		$demo_text = _("Please login here");
    if (@$allow_password_reset) {
      $demo_text .= " "._("or")." <a href='$path_to_root/index.php?reset=1'>"._("request new password")."</a>";
    }
	}

	if (check_faillog())
	{
		$blocked_msg = '<span class=redfg>'._('Too many failed login attempts.<br>Please wait a while or try later.').'</span>';

	    $js .= "<script>setTimeout(function() {
	    	document.getElementsByName('SubmitUser')[0].disabled=0;
	    	document.getElementById('log_msg').innerHTML='$demo_text'}, 1000*$login_delay);</script>";
	    $demo_text = $blocked_msg;
	}
	if (!isset($def_coy))
		$def_coy = 0;
	$def_theme = "default";

	$login_timeout = $_SESSION["wa_current_user"]->last_act;

	$title = $login_timeout ? _('Authorization timeout') : $app_title." - "._("Login");
	$encoding = isset($_SESSION['language']->encoding) ? $_SESSION['language']->encoding : "iso-8859-1";
	$rtl = isset($_SESSION['language']->dir) ? $_SESSION['language']->dir : "ltr";
	$onload = !$login_timeout ? "onload='defaultCompany()'" : "";

	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
	echo "<html dir='$rtl' >\n";
	echo "<head profile=\"https://www.w3.org/2005/10/profile\"><title>$title</title>\n";
   	echo "<meta http-equiv='Content-type' content='text/html; charset=$encoding' />\n";
	echo "<link href='$path_to_root/themes/erp/default.css' rel='stylesheet' type='text/css'> \n";
 	echo "<link href='$path_to_root/themes/erp/images/favicon.ico' rel='icon' type='image/x-icon'> \n";
	echo "<link rel='stylesheet' type='text/css' href='$path_to_root/themes/erp/style.css' />";
	//echo "<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Varela+Round'>";
				
				
	send_scripts();
	if (!$login_timeout)
	{
		echo $js;
	}
	echo "</head>\n";

	echo "<body id='loginscreen' $onload>\n";

	//echo "<table class='titletext'><tr><td>$title</td></tr></table>\n";
	
	div_start('_page_body');
	br();br();
	start_form(false, false, $_SESSION['timeout']['uri'], "loginform");



			echo "<div id = 'login'>";
			echo "<center><img src='$path_to_root/themes/erp/images/logo.png'></center>";
			echo "<h2><span class='fontawesome-lock'></span></h2>";
					echo "<fieldset>";
					echo "<input type='hidden' id=ui_mode name='ui_mode' value='".$_SESSION["wa_current_user"]->ui_mode."' />\n";
						//echo "<p><label>Username:</label></p>";
						echo "<p><input type='text' id='text' name='user_name_entry_field' placeholder='Username' autocomplete='off'/></p>";
						//echo "<p><label>Password:</label></p>";
						echo "<p><input type='password' id='password' name='password' placeholder='Password' autocomplete='off'/></p>";
					
						
						if ($login_timeout) {
		hidden('company_login_name', $_SESSION["wa_current_user"]->company);
	} else {
		if (isset($_SESSION['wa_current_user']->company))
			$coy =  $_SESSION['wa_current_user']->company;
		else
			$coy = $def_coy;
		if (!@$text_company_selection) {
			echo "<div style='visibility:hidden;'>";
			echo "<select name='company_login_name'>\n";
			for ($i = 0; $i < count($db_connections); $i++)
				echo "<option value=$i ".($i==$coy ? 'selected':'') .">" . $db_connections[$i]["name"] . "</option>";
			echo "</select>\n";
			echo "</div>";
		} else {
//			$coy = $def_coy;
			text_row(_("Company"), "company_login_nickname", "", 20, 50);
		}
	}; 
	
						echo "<div class='bottom'>";
						echo "<center><input type='submit' value='&nbsp;&nbsp;"._("Login")."&nbsp;&nbsp;' name='SubmitUser'"
		.($login_timeout ? '':" onclick='set_fullmode();'").(isset($blocked_msg) ? " disabled" : '')." /></center>\n";
						echo "	<div class='clear'></div>";
						echo "</div>";
					echo "</form>";
				echo "</div>";
				echo "<div class='clear'></div>";
			echo "</div>";
		echo "</div>";

echo "</fieldset>";
			echo "</div>";
			
	foreach($_SESSION['timeout']['post'] as $p => $val) {
		// add all request variables to be resend together with login data
		if (!in_array($p, array('ui_mode', 'user_name_entry_field', 
			'password', 'SubmitUser', 'company_login_name'))) 
			echo "<input type='hidden' name='$p' value='$val'>";
	}
	end_form(1);
	$Ajax->addScript(true, "document.forms[0].password.focus();");

    echo "<script language='JavaScript' type='text/javascript'>
    //<![CDATA[
            <!--
            document.forms[0].user_name_entry_field.select();
            document.forms[0].user_name_entry_field.focus();
            //-->
    //]]>
    </script>";
    div_end();
	
	
	echo "</body></html>\n";

?>
