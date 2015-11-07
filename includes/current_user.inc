<?php

include_once($path_to_root . "/includes/prefs/userprefs.inc");
if (!defined('TB_PREF')) {
	define('TB_PREF', '&TB_PREF&');
}
//--------------------------------------------------------------------------

class current_user
{
	var $user = 0;
	var $loginname;
	var $username;
	var $name;
	var $email;
	var $company; // user's company
	var $pos;
	var $access;
	var $timeout;
	var $last_act;
	var $role_set = false;
	var $old_db;
	var $logged;
	var $ui_mode = 0;
	
	var $prefs;
	var $cur_con; // current db connection (can be different from $company for superuser)

	function current_user()
	{
		global $def_coy;
		
		$this->loginname = $this->username = $this->name = "";
		$this->company = isset($def_coy)? $def_coy : 0;
		$this->logged = false;

		$this->prefs = new user_prefs();
	}

	function logged_in()
	{
		return $this->logged;
	}

	function set_company($company)
	{
		$this->company = (int)$company;
	}

	function login($company, $loginname, $password)
	{
		global $security_areas, $security_groups, $security_headings, $path_to_root, $login_delay;

		$this->set_company($company);
	    $this->logged = false;

		set_global_connection();

		// Use external authentication source if any.
		// Keep in mind you need to have user data set for $loginname
		// in FA users table anyway to successfully log in.
		$Auth_Result = hook_authenticate($loginname, $password);

		if (!isset($Auth_Result))	// if not used: standard method
			$Auth_Result = get_user_auth($loginname, md5($password));

		if ($login_delay > 0)
			write_login_filelog($loginname, $Auth_Result);

		if ($Auth_Result)
		{
			$myrow = get_user_by_login($loginname);
			$this->old_db = isset($myrow["full_access"]);
			if (! @$myrow["inactive"]) {
				if ($this->old_db) { 
					// Transition code:
					// db was not yet upgraded after source update to v.2.2
					// give enough access for admin user to continue upgrade
					if (!isset($security_groups) || !isset($security_headings)) {
						echo "<center><br><br><font size='5' color='red'><b>";
						echo _('Before software upgrade you have to include old $security_groups and $security_headings arrays from old config.php file to the new one.');
						echo '<br>'."<a href=$path_to_root/index.php>"._("Back")."</a>";
						echo "</b></font><br><br></center>";
						exit;
					}
	        	    $this->access = $myrow["full_access"];
	        	    if (in_array(20, $security_groups[$this->access]))
						// temporary access for admin users
						$this->role_set[] = $security_areas['SA_SOFTWAREUPGRADE'][0];
					else {
						echo "<center><br><br><font size='5' color='red'><b>";
						echo _('System is available for site admin only until full database upgrade');
						echo "</b></font><br><br></center>";
						exit;
					}
	        	} else {
					$this->role_set = array();
	        	    $this->access = $myrow["role_id"];
					// store area codes available for current user role
					$role = get_security_role($this->access);
					if (!$role) 
						return false;
					foreach( $role['areas'] as $code )
						// filter only area codes for enabled security sections
						if (in_array($code&~0xff, $role['sections'])) 
							$this->role_set[] = $code;
	        	}
        	    $this->name = $myrow["real_name"];
        	    $this->pos = $myrow["pos"];
        	    $this->loginname = $loginname;
        	    $this->username = $this->loginname;
        	    $this->prefs = new user_prefs($myrow);
        	    $this->user = @$myrow["id"];
                $this->email = @$myrow["email"];
		    	update_user_visitdate($this->username);
		    	$this->logged = true;
				$this->last_act = time();
				$this->timeout = session_timeout();
			}
		}
		return $this->logged;
	}

	function reset_password($company, $email) {
		global $app_title;

		$this->set_company($company);
		$this->logged = false;

		set_global_connection();

		$myrow = get_user_by_email($email);

		if ($myrow['id'] != "") {

			$bytes = openssl_random_pseudo_bytes(8, $cstrong);
			$password   = base64_encode($bytes);

			$hash = md5($password);

			update_user_password($myrow['id'], $myrow['user_id'], $hash);

			mail($myrow['email'], _("New password for")." ".$app_title, $password);

			return true;
		}
   		return false;
    }

	function check_user_access()
	{
		global $security_groups;
		if ($this->old_db) {
			// notification after upgrade from pre-2.2 version
			return isset($security_groups) && is_array(@$security_groups[$this->access]);
		} else
			return !isset($security_groups) && is_array($this->role_set);
	}

	function can_access($page_level)
	{
		global $security_groups, $security_areas;
		if (isset($security_groups)) {
			return $this->company == 0 &&
				in_array(20, $security_groups[$this->access]);
		}

		if ($page_level === 'SA_OPEN') 
			return true;
		if ($page_level === 'SA_DENIED' || $page_level === '') 
			return false;

		$code = $security_areas[$page_level][0];

		// only first registered company has site admin privileges
		return $code && in_array($code, $this->role_set)
			&& ($this->company == 0 || (($code&~0xff) != SS_SADMIN));
	}

	function can_access_page($page_level)
	{
		return $this->can_access($page_level);
	}

	function check_application_access($waapp)
	{
		if (!$this->hide_inaccessible_menu_items())
		{
			return true;
		}

		foreach ($waapp->modules as $module)
		{
			if ($this->check_module_access($module))
			{
				return true;
			}
		}

		return false;

	}

	function check_module_access($module)
	{

		if (!$this->hide_inaccessible_menu_items())
		{
			return true;
		}

		if (sizeof($module->lappfunctions) > 0)
		{
			foreach ($module->lappfunctions as $appfunction)
			{
				if ($appfunction->label != "" && $this->can_access_page($appfunction->access))
				{
					return true;
				}
			}
		}

		if (sizeof($module->rappfunctions) > 0)
		{
			foreach ($module->rappfunctions as $appfunction)
			{
				if ($appfunction->label != "" && $this->can_access_page($appfunction->access))
				{
					return true;
				}
			}
		}

		return false;

	}

	function hide_inaccessible_menu_items()
	{
		global $hide_inaccessible_menu_items;

		if (!isset($hide_inaccessible_menu_items) || $hide_inaccessible_menu_items == 0)
		{
			return false;
		}

		else
		{
			return true;
		}
	}

	function set_db_connection($id = -1)
	{
		return set_global_connection($id);
	}

	function update_prefs($prefs)
	{
		global $allow_demo_mode;
		
		if(!$allow_demo_mode) {
			update_user_prefs($this->user, $prefs);
		}

		$this->prefs = new user_prefs(get_user($this->user));
	}
}

//--------------------------------------------------------------------------

function round2($number, $decimals=0)
{
	$delta = ($number < 0 ? -.0000000001 : .0000000001);
	return round($number+$delta, $decimals);
}

/*
	Returns number formatted according to user setup and using $decimals digits after dot 
	(defualt is 0). When $decimals is set to 'max' maximum available precision is used 
	(decimals depend on value) and trailing zeros are trimmed.
*/
function number_format2($number, $decimals=0)
{
	global $thoseps, $decseps;
	$tsep = $thoseps[$_SESSION["wa_current_user"]->prefs->tho_sep()];
	$dsep = $decseps[$_SESSION["wa_current_user"]->prefs->dec_sep()];
	//return number_format($number, $decimals, $dsep,	$tsep);
	if($decimals==='max')
		$dec = 15 - floor(log10(abs($number)));
	else {
		$delta = ($number < 0 ? -.0000000001 : .0000000001);
		$number += $delta;
		$dec = $decimals;
	}

	$num = number_format($number, $dec, $dsep, $tsep);

	return $decimals==='max' ? rtrim($num, '0') : $num;

}

/* price/float comparision helper to be used in any suspicious place for zero values? 
usage:
if (!floatcmp($value1, $value2)) 
	compare value is 0
*/

define('FLOAT_COMP_DELTA', 0.004);

function floatcmp($a, $b)
{
    return $a - $b > FLOAT_COMP_DELTA ? 1 : ($b - $a > FLOAT_COMP_DELTA ? -1 : 0);
}

//
//	Current ui mode.
//
function fallback_mode() {
    return $_SESSION["wa_current_user"]->ui_mode==0;
}

function price_format($number) {
    return number_format2($number,
	$_SESSION["wa_current_user"]->prefs->price_dec());
}

function price_decimal_format($number, &$dec)
{
	$dec = user_price_dec();
	$str = strval($number);
	$pos = strpos($str, '.');
	if ($pos !== false)
	{
		$len = strlen(substr($str, $pos + 1));
		if ($len > $dec)
			$dec = $len;
	}
	return number_format2($number, $dec);
}
// function money_format doesn't exist in OS Win.
if (!function_exists('money_format'))
{
	function money_format($format, $number) 
	{
		return price_format($number);
	} 
}	

// 2008-06-15. Added extra parameter $stock_id and reference for $dec
//--------------------------------------------------------------------
function qty_format($number, $stock_id=null, &$dec) {
	$dec = get_qty_dec($stock_id);
    return number_format2($number, $dec);
}

// and get_qty_dec
function get_qty_dec($stock_id=null)
{
	global $path_to_root;
	include_once($path_to_root."/inventory/includes/db/items_units_db.inc");
	if ($stock_id != null)
		$dec = get_unit_dec($stock_id);
	if ($stock_id == null || $dec == -1 || $dec == null)
		$dec = $_SESSION["wa_current_user"]->prefs->qty_dec();
	return $dec;
}
//-------------------------------------------------------------------
//
//	Maximum precision format. Strips trailing unsignificant digits.
//
function maxprec_format($number) {
    return number_format2($number, 'max');
}

function exrate_format($number) {
    return number_format2($number,
	$_SESSION["wa_current_user"]->prefs->exrate_dec());
}

function percent_format($number) {
    return number_format2($number,
	$_SESSION["wa_current_user"]->prefs->percent_dec());
}

function user_numeric($input) {
    global $decseps, $thoseps;

    $num = trim($input);
    $sep = $thoseps[user_tho_sep()];
    if ($sep!='')
    	$num = str_replace( $sep, '', $num);

    $sep = $decseps[user_dec_sep()];
    if ($sep!='.')
    	$num = str_replace( $sep, '.', $num);

    if (!is_numeric($num))
	  return false;
    $num = (float)$num;
    if ($num == (int)$num)
	  return (int)$num;
    else
	  return $num;
}

function user_company()
{
	global $def_coy;
	
	return isset($_SESSION["wa_current_user"]) ? $_SESSION["wa_current_user"]->company : $def_coy;
}

function user_pos()
{
	return $_SESSION["wa_current_user"]->pos;
}

function user_language()
{
	return $_SESSION["wa_current_user"]->prefs->language();
}

function user_qty_dec()
{
	return $_SESSION["wa_current_user"]->prefs->qty_dec();
}

function user_price_dec()
{
	return $_SESSION["wa_current_user"]->prefs->price_dec();
}

function user_exrate_dec()
{
	return $_SESSION["wa_current_user"]->prefs->exrate_dec();
}

function user_percent_dec()
{
	return $_SESSION["wa_current_user"]->prefs->percent_dec();
}

function user_show_gl_info()
{
	return $_SESSION["wa_current_user"]->prefs->show_gl_info();
}

function user_show_codes()
{
	return $_SESSION["wa_current_user"]->prefs->show_codes();
}

function user_date_format()
{
	return $_SESSION["wa_current_user"]->prefs->date_format();
}

function user_date_display()
{
	return $_SESSION["wa_current_user"]->prefs->date_display();
}

function user_date_sep()
{
	return $_SESSION["wa_current_user"]->prefs->date_sep();
}

function user_tho_sep()
{
	return $_SESSION["wa_current_user"]->prefs->tho_sep();
}

function user_dec_sep()
{
	return $_SESSION["wa_current_user"]->prefs->dec_sep();
}

function user_theme()
{
	return isset($_SESSION["wa_current_user"]) ?
		$_SESSION["wa_current_user"]->prefs->get_theme() : 'default';
}

function user_pagesize()
{
	return $_SESSION["wa_current_user"]->prefs->get_pagesize();
}

function user_hints()
{
	return $_SESSION["wa_current_user"]->prefs->show_hints();
}

function user_print_profile()
{
	return $_SESSION["wa_current_user"]->prefs->print_profile();
}

function user_rep_popup()
{
	return $_SESSION["wa_current_user"]->prefs->rep_popup();
}

function user_query_size()
{
	return $_SESSION["wa_current_user"]->prefs->query_size();
}

function user_graphic_links()
{
	return $_SESSION["wa_current_user"]->prefs->graphic_links();
}

function sticky_doc_date()
{
	return $_SESSION["wa_current_user"]->prefs->sticky_date();
}

function user_startup_tab()
{
	return $_SESSION["wa_current_user"]->prefs->start_up_tab();
}

function set_user_prefs($prefs)
{
	$_SESSION["wa_current_user"]->update_prefs($prefs);
}

function add_user_js_data() {
	global $path_to_root, $thoseps, $decseps, $date_system, $dateseps;

	$ts = $thoseps[user_tho_sep()];
	$ds = $decseps[user_dec_sep()];

    $js = "\n"
	  . "var user = {\n"
	  . "theme: '". $path_to_root . '/themes/'. user_theme().'/'."',\n"
	  . "loadtxt: '"._('Requesting data...')."',\n"
	  . "date: '".Today()."',\n"	// server date
	  . "datesys: ".$date_system.",\n"
	  . "datefmt: ".user_date_format().",\n"
	  . "datesep: '".$dateseps[user_date_sep()]."',\n"
	  . "ts: '$ts',\n"
	  . "ds: '$ds',\n"
	  . "pdec : " . user_price_dec() . "}\n";

  add_js_source($js);
}

//--------------------------------------------------------------------------

function session_timeout()
{
	$tout = @get_company_pref('login_tout'); // mask warning for db ver. 2.2
	return $tout ? $tout : ini_get('session.gc_maxlifetime');
}

//-----------------------------------------------------------------------------
//	Inserts $elements into $array at position $index.
//	$elements is list of any objects
//
function array_insert(&$array, $index, $elements)
{
	if (!is_array($elements)) $elements = array($elements);

	$head  = array_splice($array, 0, $index);
	$array = array_merge($head, $elements, $array);
}

function array_remove(&$array, $index, $len=1)
{
	array_splice($array, $index, $len);
}

function array_substitute(&$array, $index, $len, $elements)
{
	array_splice($array, $index, $len);
	array_insert($array, $index, $elements);
}

function array_append(&$array, $elements)
{
	foreach($elements as $key => $el) {
		if(is_int($key))
			$array[] = $el;
		else
			$array[$key] = $el;
	}
}
//
//	Search $needle in $haystack or in $haystack[][$valuekey]
//	returns $needle found or null.
//
function array_search_value($needle, $haystack, $valuekey=null)
{
	foreach($haystack as $key => $value) {
		$val = isset($valuekey) ? @$value[$valuekey] : $value;
		if ($needle == $val){
			return $value;
		}
	}
	return null;
}
//
//	Search $needle in $haystack or in $haystack[][$valuekey]
//	returns array of keys of $haystack elements found
//
function array_search_keys($needle, $haystack, $valuekey=null)
{
	$keys = array();
	if($haystack)
		foreach($haystack as $key => $value) {
			$val = isset($valuekey) ? @$value[$valuekey] : $value;
			if ($needle == $val){
				$keys[] = $key;
			}
		}
	return $keys;
}
//
//	Find first (single) $needle in $haystack or in $haystack[][$valuekey]
//	returns $haystack element found or null
//
function array_search_key($needle, $haystack, $valuekey=null)
{
	$keys = array_search_keys($needle, $haystack, $valuekey);
	return @$keys[0];
}

// Recalculate report columns if orientation is landscape.
function recalculate_cols(&$cols)
{
	$factor = (user_pagesize() == "A4" ? 1.4 : 1.3);
	foreach($cols as $key => $col)
		$cols[$key] = intval($col * $factor); 
}

function flush_dir($path, $wipe = false) 
{
	$dir = opendir($path);
	if(!$dir)
		return;
	while(false !== ($fname = readdir($dir))) {
		if($fname=='.' || $fname=='..' || $fname=='CVS' || (!$wipe && $fname=='index.php')) continue;
  		if(is_dir($path.'/'.$fname)) {
		    flush_dir($path.'/'.$fname, $wipe);
		    if ($wipe) @rmdir($path.'/'.$fname);
		} else
		    @unlink($path.'/'.$fname);
	}
}
/*
	Returns current path to company private folder.
	(Current path can change after chdir).
*/
function company_path($comp=null)
{
	global $path_to_root, $comp_path;

	if (!isset($comp))
		$comp = user_company();

	// if path is relative, set current path_to_root
	return ($comp_path[0]=='.' ? $path_to_root.'/'.basename($comp_path) : $comp_path)
			. '/'.$comp;
}


?>