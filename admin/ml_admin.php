<?php
/**********************************************************************
    Copyright (C) FrontAccounting, LLC.
	Released under the terms of the GNU General Public License, GPL, 
	as published by the Free Software Foundation, either version 3 
	of the License, or (at your option) any later version.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the License here <http://www.gnu.org/licenses/gpl-3.0.html>.
***********************************************************************/
$page_security = 'SA_CREATECOMPANY';
$path_to_root="..";
include_once($path_to_root . "/includes/session.inc");
include_once($path_to_root . "/includes/ui.inc");

page(_($help_context = "For admin only."));

echo "<div align='center'>";
echo "<a href='$path_to_root/admin/create_coy.php'>Create/Update Companies</a><br/>";
echo "<a href='$path_to_root/admin/backups.php'>Backup and Restore</a><br/>";
echo "<a href='$path_to_root/admin/inst_lang.php'>Install/Update Languages</a><br/>";
echo "<a href='$path_to_root/admin/inst_module.php'>Install/Activate Extensions</a><br/>";
echo "<a href='$path_to_root/admin/inst_theme.php'>Install/Activate Themes</a><br/>";
echo "<a href='$path_to_root/admin/inst_chart.php'>Install/Activate &Chart of Accounts</a><br/>";
echo "<a href='$path_to_root/admin/inst_upgrade.php'>Software &Upgrade</a><br/>";
echo "<a href='$path_to_root/admin/security_roles.php'>Access Setup</a><br/>";
echo "</div>";

end_page();

?>