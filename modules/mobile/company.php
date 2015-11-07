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
include_once ("../../config_db.php");

echo("<option value='0'> </option>");
 for ($counter=0;$counter<count($db_connections);$counter++) {
            echo("<option value='{$counter}'>{$db_connections[$counter]['account']}</option>");
}
  
?>