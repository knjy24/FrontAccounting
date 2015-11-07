<?php 
global $path_to_root;

echo "<b>Tips</b>:<br/>
                     The following must have been previously set up:
                      <li>Fiscal Year <a href='$path_to_root/index.php?application=system'>(Setup)</a></li><br/>
                      Under this tab, configure:<br/>
                      <li><a href='$path_to_root/gl/manage/gl_accounts.php'>GL Accounts</a>, 
                          <a href='$path_to_root/gl/manage/gl_account_types.php'>account groups</a> and 
                          <a href='$path_to_root/gl/manage/gl_account_classes.php'>classes</a></li>
                      <li><a href='$path_to_root/gl/manage/bank_accounts.php'>Bank Accounts</a> and 
                          <a href='$path_to_root/gl/manage/currencies.php'>currencies</a></li>
                      <br/>";


?>