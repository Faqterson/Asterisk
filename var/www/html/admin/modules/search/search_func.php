<?php
function search() {
?>
    <form name="searchform" method="get" action="index.php">
       <input name="mod" type="hidden" value="master">
       <table width="100%" border="0" cellpadding="2" cellspacing="2" class="border">
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td width="20%" align="left"><b>Start Date</b></td>
            <td width="80%" align="left"><input name="startdate" id="startdate" type="text" size="15" maxlength="10" value="<?php echo $startdate;?>">
            </td>
          </tr>
          <tr>
            <td align="left"><b>End Date</b></td>
            <td align="left"><input name="enddate" id="enddate" type="text" size="15" maxlength="10">
            </td>
          </tr>
          <tr>
          	<td align="left">&nbsp;</td>
            <td align="left"><input name="submit" type="submit" class="button" value="Search"></td>
          </tr>
         </table>
         </form>
<?php

}
?>
