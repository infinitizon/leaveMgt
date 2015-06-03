<form id="updt_pass_form" name="updt_pass_form" method="post" action="<?php echo WEB_ROOT; ?>/home/crt_emp.php?add=1">
 <table width="453" border="0">
   <tr>
      <td colspan="2">
			<?php 
				echo isset($err_msg) ? "<div class='err'>".$err_msg.'</div>' : '';
				echo isset($success_msg) ? "<div class='success'>".$success_msg.'</div>' : '';
			?></td>
   </tr>
   <tr>
      <td class="align_txt_r" colspan="2"><a href="<?php echo WEB_ROOT; ?>/home/crt_emp.php" class="button">Cancel</a></td>
   </tr>
   <tr>
      <td width="150" class="align_txt_r">Oracle Id:</td>
      <td><input type="text" name="oracle_id" class="oracle_id number" value="<?php echo @$oracle_id; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">First Name:</td>
      <td width="152"><input type="text" name="fst_nm" class="fst_nm" value="<?php echo @$fst_nm; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">Middle Name:</td>
      <td width="152"><input type="text" name="mdl_nm" class="mdl_nm" value="<?php echo @$mdl_nm; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">Last Name:</td>
      <td width="152"><input type="text" name="lst_nm" class="lst_nm" value="<?php echo @$lst_nm; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">Phone No.:</td>
      <td width="152"><input type="text" name="phn_no" class="phn_no" value="<?php echo @$phn_no; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">Email Adress:</td>
      <td width="152"><input type="text" name="eml_adr" class="eml_adr" value="<?php echo @$eml_adr; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">Primary Address:</td>
      <td width="152"><input type="text" name="pry_adr_ln1" class="pry_adr_ln1" value="<?php echo @$pry_adr_ln1; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">&nbsp;</td>
      <td width="152"><input type="text" name="pry_adr_ln2" class="pry_adr_ln2" value="<?php echo @$pry_adr_ln2; ?>" /></td>
   </tr>
   <tr>
      <td class="align_txt_r">City:</td>
      <td width="152"><input type="text" name="pry_adr_city" class="pry_adr_city" value="<?php echo @$pry_adr_city; ?>" /></td>
   </tr>
   <tr>
	  <td class="align_txt_r">State:</td>
      <td><input type="text" name="pry_adr_sta" class="pry_adr_sta" value="<?php echo @$pry_adr_sta; ?>" /></td>
   </tr>
   <tr>
	  <td class="align_txt_r">Country:</td>
      <td><?php echo $fxns->_getLOVs('pry_adr_ctr', 'CTC-CTR', 'country'); ?></td>
   </tr>
   <tr>
	  <td class="align_txt_r">Employee Type:</td>
      <td><?php echo $fxns->_getLOVs('emp_tp', 'EMP-TP', 'emp_tp'); ?></td>
   </tr>
   <tr>
   	 <td>&nbsp;</td>
     <td><input name="crt_emp_rec" id="crt_emp_rec" class="button" type="submit" value="Create Employee Record" /></td>
   </tr>
 </table>
</form>
