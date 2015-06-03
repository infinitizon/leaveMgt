<form id="updt_pass_form" name="updt_pass_form" method="post" action="<?php echo WEB_ROOT; ?>/home/chng_pwd.php">
   <table width="453" border="0">
   <tr>
      <td colspan="3">
			<?php 
				echo isset($err_msg) ? "<div class='err'>".$err_msg.'</div>' : '';
				echo isset($success_msg) ? "<div class='success'>".$success_msg.'</div>' : '';
			?></td>
     </tr>
   <tr>
      <td width="150" class="align_txt_r">Current password:</td><td colspan="2"><input type="password" name="curr_pass" class="curr_pass" /></td>
     </tr>
   <tr>
      <td class="align_txt_r">New password:</td><td width="152"><input type="password" name="new_pass" class="new_pass" /></td>
      <td width="137"><div id="passStrength" class="passStrength strength0 float_right"><div id="passDesc" class="passDesc">Password not entered</div></div></td>
   </tr>
   <tr>
   <td class="align_txt_r">Repeat New password:</td><td colspan="2"><input type="password" name="confirm_curr_pass" class="confirm_curr_pass" /></td>
   </tr>
   <tr>
   <td>&nbsp;</td>
   <td colspan="2"><input name="updt_pass" id="updt_pass" class="button" type="submit" value="Update Password" /></td>
   </tr>
   </table>
</form>
