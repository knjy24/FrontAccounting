<?php 

global  $path_to_root;

echo "To use this module, the following must have been previously set up:
                      <li>Item <a href='$path_to_root/index.php?application=stock'>(Items and Inventory)</a></li><br/>
                      Under this tab, configure:
                      <li><a href='$path_to_root/sales/manage/customers.php'>Customer</a> and <a href='$path_to_root/sales/manage/customer_branches.php'>Customer Branches</a></li>
                      <li><a href='$path_to_root/sales/manage/sales_groups.php'>Sales Groups</a>, 
                          <a href='$path_to_root/sales/manage/sales_types.php'>Types</a>, 
                          <a href='$path_to_root/sales/manage/sales_people.php'>Persons</a> and 
                          <a href='$path_to_root/sales/manage/sales_areas.php'>Areas</a></li>
                      <li><a href='$path_to_root/sales/manage/credit_status.php'>Credit Status</a></li>
                      <br/>";
?>