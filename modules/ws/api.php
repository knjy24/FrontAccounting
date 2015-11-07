<?php

/**
 * Copyright (C) FrontAccounting, LLC.
 * @license <http://www.gnu.org/licenses/gpl-3.0.html>.
 */
 
/*******************************************************************************
 * Copyright(c) @2011 ANTERP SOLUTIONS. All rights reserved.
 *
 * Released under the terms of the GNU General Public License, GPL, 
 * as published by the Free Software Foundation, either version 3 
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 * See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
 *
 * Authors		    tclim
 * Date Created     Aug 09, 2011 2:06:39 PM
 ******************************************************************************/
global $security_areas, $security_groups, $security_headings, $path_to_root, $db, $db_connections;

$path_to_root = "../..";

$page_security = 'SA_OPEN';
ini_set('xdebug.auto_trace', 1);
include_once ($path_to_root . "/includes/session-custom.inc");

add_access_extensions();
$app = & $_SESSION["App"];
if (isset ($_GET['application']))
	$app->selected_application = $_GET['application'];

include_once ("Zend/Json.php");
include_once ($path_to_root . "/includes/types.inc");
include_once ($path_to_root . "/includes/db/connect_db.inc");
include_once ($path_to_root . "/admin/db/users_db.inc");
include_once ($path_to_root . "/includes/Log.php");
include_once ($path_to_root . "/sales/includes/cart_class.inc");
include_once ($path_to_root . "/sales/includes/sales_ui.inc");
include_once ($path_to_root . "/sales/includes/ui/sales_order_ui.inc");
include_once ($path_to_root . "/sales/includes/sales_db.inc");
include_once ($path_to_root . "/sales/includes/db/sales_types_db.inc");
include_once ($path_to_root . "/purchasing/includes/po_class.inc");
include_once ($path_to_root . "/purchasing/includes/purchasing_ui.inc");
include_once ($path_to_root . "/purchasing/includes/db/suppliers_db.inc");

$module = "";
$method = '';
$debugMode = '0';
$param1 = '';
$param2 = '';
$param3 = '';
$pgIndex = '0';
$recordPerPage = '20';
$login = "";
$company = "";
$location = "DEF";
$username = "";
$password = "";
$stime = "";

if (isset ($_GET['module'])) {
	$module = trim($_GET['module']);
} else if (isset ($_POST['module'])) {
	$module = trim($_POST['module']);
}

if (isset ($_GET['method'])) {
	$method = trim($_GET['method']);
} else if (isset ($_POST['method'])) {
	$method = trim($_POST['method']);
}

if (isset ($_GET['debugMode'])) {
	$debugMode = trim($_GET['debugMode']);
} else if (isset ($_POST['debugMode'])) {
	$debugMode = trim($_POST['debugMode']);
}

if (isset ($_GET['param1'])) {
	$param1 = trim($_GET['param1']);
} else if (isset ($_POST['param1'])) {
	$param1 = trim($_POST['param1']);
}

if (isset ($_GET['param2'])) {
	$param2 = trim($_GET['param2']);
} else if (isset ($_POST['param2'])) {
	$param2 = trim($_POST['param2']);
}

if (isset ($_GET['param3'])) {
	$param3 = trim($_GET['param3']);
} else if (isset ($_POST['param3'])) {
	$param3 = trim($_POST['param3']);
}

if (isset ($_GET['pgIndex'])) {
	$pgIndex = trim($_GET['pgIndex']);
} else if (isset ($_POST['pgIndex'])) {
	$pgIndex = trim($_POST['pgIndex']);
}

if (isset ($_GET['recordPerPage'])) {
	$recordPerPage = trim($_GET['recordPerPage']);
} else if (isset ($_POST['recordPerPage'])) {
	$recordPerPage = trim($_POST['recordPerPage']);
}

if (isset ($_GET['company'])) {
	$company = trim($_GET['company']);
} else if (isset ($_POST['company'])) {
	$company = trim($_POST['company']);
}

if (isset ($_GET['location'])) {
	$location = trim($_GET['location']);
} else if (isset ($_POST['location'])) {
	$location = trim($_POST['location']);
}

if (isset ($_GET['username'])) {
	$username = trim($_GET['username']);
} else if (isset ($_POST['username'])) {
	$username = trim($_POST['username']);
}

if (isset ($_GET['password'])) {
	$password = trim($_GET['password']);
} else if (isset ($_POST['password'])) {
	$password = trim($_POST['password']);
}

if (isset ($_GET['stime'])) {
	$stime = $_GET['stime'];
} else if (isset ($_POST['stime'])) {
	$stime = $_POST['stime'];
}

/**
* Configure Log file
* param1 	-	Logfile Name
* param2	-	true - Log
*				false - No log
*/
$log = new Log($path_to_root . "/logs/mobile_" . $company . ".log", $debugMode);

$resp = array ();

//Check if the module is empty
if ($module ==''){ 	
	$error = "Invalid Module Name";
	$resp = array ("success" => false, "message" => $error);
	echo Zend_Json:: encode($resp);
	exit;
}

//Retrieve company database information
$db = set_global_connection($company);

$return = true;

//This method is to perform Login or Logout only
if ($method == 'login' || $method == 'logout') {
	$message = '';
		
		if ($method == 'login') {
			$return = $_SESSION["wa_current_user"]->login($company, $username, $password);
			if (!$return) {
				$message = "Invalid Username or Password!!!";
			} else {
				$message = "User Login Successfully.";
			}
		} else if ($method == 'logout') {
			session_unset();
			session_destroy();
			$return = true;
			$message = "You have been Logout.";
		}
		
	$resp = array ("success" => $return, "message" => $message);
	echo Zend_Json:: encode($resp);
	return;
}

//if (!$return) { //Invalid Username or Password
if (!$_SESSION["wa_current_user"]->logged) { //Invalid Username or Password
	$error = "Invalid Username or Password!!!";
	$resp = array ("success" => false, "message" => $error);
	echo Zend_Json:: encode($resp);
} else {
	
	$arrObj = array();
	
	switch ($method) {	
		case 'get_all_account':
			$resp = get_all_account($param1, $pgIndex, $recordPerPage);
			break;
		case 'get_account_by_id':
			$resp = get_account_by_id($param1);
			break;	
		case 'get_all_contact':
			$resp = get_all_contact($param1, $param2, $param3, $pgIndex, $recordPerPage);					
			break;
		case 'get_contact_by_account':
			$resp = get_contact_by_account($param1, $param2, $param3, $pgIndex, $recordPerPage);
			break;	
		case 'get_contact_by_id':
			$resp = get_contact_by_id($param1);
			break;	
		case 'get_person_by_account':
			$resp = get_person_by_account($param1, $param2, $param3);
			break;	
		case 'get_all_supplier':
			$resp = get_all_suppliers($param1, $pgIndex, $recordPerPage);
			break;
		case 'get_supplier_by_id':
			$resp = get_supplier_by_id($param1);
			break;		
		case 'get_contact_by_supplier_id':
			$resp = get_contact_by_supplier_id($param1, $param2, $param3, $pgIndex, $recordPerPage);
			break;		
		case 'get_supplier_contact_by_id':
			$resp = get_supplier_contact_by_id($param1);
			break;			
		case 'get_all_stock':
			$resp = getAllStockMaster($param1, $pgIndex, $recordPerPage);
			break;
		case 'get_stock_by_stock_id':
			$resp = getStockMasterById($param1);
			break;			
		case 'get_price':
			$arrObj = Zend_Json::decode(htmlspecialchars_decode($param1), Zend_Json::TYPE_ARRAY);
			$resp = getPrice($arrObj, $module);
			break;	
		case 'get_stock_status':
			$arrObj = Zend_Json::decode(htmlspecialchars_decode($param1), Zend_Json::TYPE_ARRAY);
			$resp = getStockStatus($arrObj);
			break;	
		case 'get_all_purchase_order':
			$resp = getAllPurchaseOrder($param1, $pgIndex, $recordPerPage);
			break;
		case 'get_purchase_order_by_order_no':
			$resp = getPurchaseOrderByOrderNo($param1);
			break;
		case 'get_purchase_order_item_details_by_order_no':
			$resp = getPurchaseOrderItemDetailsByOrderNo($param1);
			break;
		case 'get_purchase_order_item_details':
			$arrObj = Zend_Json::decode(htmlspecialchars_decode($param1), Zend_Json::TYPE_ARRAY);
			$resp = getPurchaseOrderItemDetails($arrObj);
			break;
		case 'get_all_sales_order': //order_no, trans_type
			$resp = getAllSalesOrder($param1, $param2, $pgIndex, $recordPerPage);
			break;	
		case 'get_sales_order_by_order_no'://order_no, trans_type
			$resp = getSalesOrderByOrderNo($param1, $param2);
			break;	
		case 'get_sales_order_item_details_by_order_no'://order_no, trans_type
			$resp = getSalesOrderItemDetailsByOrderNo($param1, $param2);
			break;	
		case 'get_sales_order_item_details':
			$arrObj = Zend_Json::decode(htmlspecialchars_decode($param1), Zend_Json::TYPE_ARRAY);			
			$resp = getSalesOrderItemDetails($arrObj);
			break;
		case 'get_all_invoice': //trans_no, trans_type
			$resp = getAllInvoice($param1, $param2, $pgIndex, $recordPerPage);
			break;	
		case 'get_invoice_by_trans_no'://trans_no, trans_type
			$resp = getInvoiceByTransNo($param1, $param2);
			break;	
		case 'get_invoice_item_details_by_trans_no'://order_no, trans_type
			$resp = getInvoiceItemDetailsByTransNo($param1, $param2);
			break;				
		case 'get_invoice_item_details':
			$arrObj = Zend_Json::decode(htmlspecialchars_decode($param1), Zend_Json::TYPE_ARRAY);
			$resp = getInvoiceItemDetails($arrObj);
			break;	
		case 'get_sys_prefs':
			$arrObj = Zend_Json::decode(htmlspecialchars_decode($param1), Zend_Json::TYPE_ARRAY);
			$resp = getSysPrefs($arrObj);
			break;
	}

	echo Zend_Json::encode($resp);
}
?>
