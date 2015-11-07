<?php 
global $path_to_root;

echo "<table width='90%'><tr><td width='45%'>
                      <b>Tips</b>:<br/>
                      The following must have been previously set up to use this module:
                      <li>Dimension Type <a href='$path_to_root/index.php?application=proj'>(Tracking)</a></li>
                      <li>Item Tax Type <a href='$path_to_root/index.php?application=system'>(Setup)</a></li></td>
                      <td width='45%'><br/><br/><br/>Under this tab, configure:
                      <li><a href='$path_to_root/inventory/manage/locations.php'>Inventory Locations</a></li>
                      <li><a href='$path_to_root/inventory/manage/item_categories.php'>Item Categories</a></li>
                      <li><a href='$path_to_root/inventory/manage/items.php'>Items</a></li>
                      <li><a href='$path_to_root/inventory/manage/item_units.php'>Unit of Measure</a></li>
                      </td></tr></table>";

?>