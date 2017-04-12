<div class="panel woocommerce_options_panel" id="shipping_<?php echo $loop; ?>" style="display: none;">

	<div class="options_group hide_if_grouped">
		<h3><a class="button remove_checkout_field" remove-key="shipping_<?php echo $loop; ?>" href="javascript:void(0);">Remove</a></h3>
			<table>
				<tbody>
					<tr>                
						<td class="err_msgs" colspan="2"></td>
					</tr>
					<tr>                
						<td width="40%">Name</td>
						<td>
							<input placeholder='always use shipping_ as prefix' type="text" style="width:250px;" class="remove_key_field_<?php echo $loop; ?>" value="shipping_" name="fieldname[<?php echo $loop; ?>]">
						</td>
					</tr>
					<tr>                   
						<td>Type</td>
						<td>
							<select style="width:250px;" name="fieldtype[<?php echo $loop; ?>]">
								
								<option value="text">text field</option>
								<option value="textarea">textarea</option>
								
							</select>
						</td>
					</tr>                
					<tr>
						<td>Label</td>
						<td><input type="text" style="width:250px;" value="" name="fieldlabel[<?php echo $loop; ?>]"></td>
					</tr>
					<tr>                    
						<td>Placeholder</td>
						<td><input type="text" style="width:250px;" value="" name="fieldplaceholder[<?php echo $loop; ?>]"></td>
					</tr>
								 
					<tr>
						<td>Class</td>
						<td>

						<input type="text" style="width:250px;" value="" placeholder="Seperate classes with comma" name="fieldclass[<?php echo $loop; ?>]"></td>
					</tr>
					<tr>
						<td>Label Class</td>
						<td><input type="text" style="width:250px;" placeholder="Seperate classes with comma" name="fieldlabelclass[<?php echo $loop; ?>]"></td>
					</tr>                                   
					<tr>                    
						<td>Validation</td>
						<td>
							<select style="width:250px;" class="shipping_field_validation" placeholder="Select validations" name="fieldvalidate[<?php echo $loop; ?>]">
								<option value="">No</option>
								<option value="email">Email</option>
								<option value="phone">Phone</option>
							</select>
						</td>
					</tr>  
					<tr>  
						<td>&nbsp;</td>                     
						<td>
							
							<input type="checkbox" value="true" name="fieldrequired[<?php echo $loop; ?>]">
							<label>Required</label><br>
							
							<input type="checkbox" value="true" name="fieldclearRow[<?php echo $loop; ?>]">
							<label>Clear Row</label><br>
							
							<input type="checkbox" value="true" checked="" name="fieldenabled[<?php echo $loop; ?>]">
							<label>Enabled</label>
						</td>                    
					</tr>  
					
				</tbody>
			
			</table>

	</div>
	
</div>