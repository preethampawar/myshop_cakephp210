<div>
	<h1>Create New Quotation</h1> 
	<?php echo '&nbsp;'.$this->Html->link('Cancel &nbsp;&nbsp;&nbsp; x', '/quotations/', array('class'=>'btn btn-xs btn-danger floatRight', 'escape'=>false));	?>
	<br/><br/><br/><br/>
	<?php echo $this->Form->create();?>		
		
	<div style="margin:auto; width:800px; border:1px solid #efefef; background-color:#fff; padding:5px;">
		<!-- Quotation header -->
		<div class="corner setBackground">			
			<table style="width:100%" class="noBorderTable">
				<tr>					
					<td style="text-align:center; font-size:20px;"><b>Quotation</b></td>
				</tr>
			</table>
		</div>
		
		<!-- From Address -->
		<div style="margin:0px; padding:0px;">
			<table style="width:100%" class="">				
				<tr>
					<td style="width:400px;">
						<b>From Company/Individual:</b>
						<?php 
						$default = $this->Session->read('Company.display_name');							
						echo $this->Form->input('from_name', array('label'=>'Name', 'type'=>'text', 'placeholder'=>'From Name', 'required'=>true, 'default'=>$default, 'label'=>'Name', 'class'=>'form-control input-sm'));
							
						echo $this->Form->input('from_address', array('label'=>'Address', 'type'=>'textarea', 'rows'=>'2', 'cols'=>'10', 'default'=>$this->Session->read('Company.address'), 'required'=>true, 'placeholder'=>'From Address', 'class'=>'form-control input-sm'));
						?>
					</td>
					<td>&nbsp;</td>
					<td style="width:300px;">
						<?php
						$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
						echo $this->Form->input('date', array('label'=>'Date (Y-m-d)*', 'id'=>'datepicker', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%', 'class'=>'input-sm'));	
						?>
					</td>
				</tr>
			</table>
		</div>
		
		<!-- Quotation for info-->
		<div style="margin:0px; padding:0px;">	
			<table style="width:100%" class="">				
				<tr>
					<td colspan='100%'>
						<b>Quotation For:</b>					
					</td>
				</tr>
				<tr>
					<td style="width:400px;">
						<?php
						echo $this->Form->input('to_name', array('label'=>'Name', 'type'=>'text', 'placeholder'=>'To Name', 'required'=>true, 'class'=>'form-control input-sm'));
						echo $this->Form->input('to_address', array('label'=>'Address', 'type'=>'textarea', 'rows'=>'2', 'cols'=>'10', 'placeholder'=>'To Address', 'required'=>true, 'class'=>'form-control input-sm'));
						?>
					</td>
					<td>&nbsp;</td>
					<td style="width:300px;">
						<?php
						$img = $this->Html->image('calendar.gif', array('onclick'=>"$('#datepicker').focus()"));
						echo $this->Form->input('validity', array('label'=>'Validity (Y-m-d)*', 'id'=>'datepicker2', 'type'=>'text', 'required'=>true, 'after'=>'&nbsp;'.$img.'<input type="text" id="alternate2" style="border:0px solid #fff; color:#ff0000; background:transparent;">', 'readonly'=>true, 'placeholder'=>'Click to open calendar', 'style'=>'width:85%', 'class'=>'input-sm'));	
						?>
					</td>
				</tr>
			</table>
		</div>
		
		
		
		<!-- Items list -->
		<div style="margin:0px; padding:0px;">
			<table style="width:100%" class="table">				
				<tbody id='itemsTbody'>
					<tr>						
						<th class="text-center">Goods Description / Service</th>
						<th style="width:150px;">HSN/ACS</th>
						<th style="width:80px;">Quantity/Hrs.</th>
						<th style="width:80px;">Unit Price</th>
						<th style="width:130px;">Amount</th>
					</tr>
					<tr>							
						<td><?php echo $this->Form->input('EntityItem.item.0', array('type'=>'text', 'label'=>false, 'div'=>false, 'lineno'=>0, 'required'=>true, 'class'=>'form-control input-sm'));?></td>
						<td><?php echo $this->Form->input('EntityItem.description.0', array('label'=>false, 'type'=>'text', 'div'=>false, 'lineno'=>0, 'required'=>true, 'class'=>'form-control input-sm'));?></td>
						<td><?php echo $this->Form->input('EntityItem.quantity.0', array('type'=>'text', 'label'=>false, 'div'=>false, 'placeholder'=>'0', 'lineno'=>0, 'onchange'=>'calculateQuotationTotal()', 'required'=>true, 'class'=>'form-control input-sm'));?></td>
						<td><?php echo $this->Form->input('EntityItem.unitrate.0', array('type'=>'text', 'label'=>false, 'div'=>false, 'placeholder'=>'0.00', 'lineno'=>0, 'onchange'=>'calculateQuotationTotal()', 'required'=>true, 'class'=>'form-control input-sm'));?></td>
						<td><?php echo $this->Form->input('EntityItem.amount.0', array('type'=>'text', 'readonly'=>true, 'label'=>false, 'class'=>'readonly lineamount', 'div'=>false, 'style'=>'width:100px;', 'placeholder'=>'0.00', 'lineno'=>0, 'class'=>'form-control input-sm'));?></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan='5' style="text-align:left">
							<br>
							<span class="btn btn-xs btn-default" onclick="addNewLine()"> + Add New Item </span>
						</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<!-- Subtotal -->	
		<div style="margin:0px; padding:0px;">	
			<table class="" style="width:100%">
				<tr>
					<td style="width:70%">
						<!-- Quotation for info-->
						<div style="margin:0px; padding:0px;">
							<table style="width:100%" class="noBorderTable">				
								<tr>
									<td>							
										<?php
										echo $this->Form->input('comments', array('label'=>'<b>Terms & Conditions / Instructions</b>', 'type'=>'textarea', 'rows'=>'6', 'cols'=>'10', 'placeholder'=>'Comments', 'class'=>'form-control input-sm'));
										?>						
									</td>
								</tr>
							</table>
						</div>	
					</td>
					<td>
						<table style="width:100%" class="noBorderTable">				
							<tbody>
								<tr>	
									<td style="text-align:right; font-weight:bold;">Subtotal: </td>
									<td style="width:130px; font-weight:bold;">
										<?php 
											echo $this->Form->input('Quotation.subtotal', array('label'=>false, 'div'=>false, 'placeholder'=>'0.00', 'readonly'=>true, 'class'=>'readonly form-control input-sm', 'style'=>'width:100px;'));
											echo ' '.$this->Session->read('Company.currency');							
										?>
									</td>
								</tr>
								<tr>	
									<td style="text-align:right; font-weight:bold;">Discount (%): </td>
									<td style="font-weight:bold;">
										<?php echo $this->Form->input('Quotation.discount', array('label'=>false, 'div'=>false, 'placeholder'=>'0.00', 'style'=>'width:100px;', 'onchange'=>'calculateQuotationTotal()', 'class'=>'form-control input-sm'));?>
									</td>
								</tr>
								<tr>	
									<td style="text-align:right; font-weight:bold;">CGST (%): </td>
									<td style="font-weight:bold;">
										<?php echo $this->Form->input('Quotation.cgst', array('label'=>false, 'div'=>false, 'placeholder'=>'0.00', 'style'=>'width:100px;', 'onchange'=>'calculateQuotationTotal()', 'class'=>'form-control input-sm'));?>
									</td>
								</tr>
								<tr>	
									<td style="text-align:right; font-weight:bold;">SGST (%): </td>
									<td style="font-weight:bold;">
										<?php echo $this->Form->input('Quotation.sgst', array('label'=>false, 'div'=>false, 'placeholder'=>'0.00', 'style'=>'width:100px;', 'onchange'=>'calculateQuotationTotal()', 'class'=>'form-control input-sm'));?>
									</td>
								</tr>					
								<tr>	
									<td style="text-align:right; font-weight:bold;">Total: </td>
									<td style="font-weight:bold;">
										<?php 
											echo $this->Form->input('Quotation.total_amount', array('label'=>false, 'div'=>false, 'placeholder'=>'0.00', 'class'=>'readonly', 'style'=>'width:100px;', 'class'=>'form-control input-sm'));
											echo ' '.$this->Session->read('Company.currency');
										?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
			
			
			<br /><br />
			
			<div style="text-align:center; font-weight:bold;"><?php echo $this->Form->submit('Save Quotation', array('class'=>'btn btn-md btn-default'));?></div>
		</div>

		
		
		<div class='clear'></div>
	</div>	
	
	<?php echo $this->Form->end();?>
</div>

<script type="text/javascript">
// showCategoryPrice('category');	
var lineNo=0;
function addNewLine() {
	lineNo=lineNo+1;
	var lineRowStart='<tr id="lineNo'+lineNo+'">';
	var lineItem="<td><input type='text' name='data[EntityItem][item]["+lineNo+"]' id='EntityItemItem"+lineNo+"' class='form-control input-sm' lineno='"+lineNo+"' required='1' /></td>";
	var lineDescription="<td><input type='text' name='data[EntityItem][description]["+lineNo+"]' id='EntityItemDescription"+lineNo+"' class='form-control input-sm' lineno='"+lineNo+"' required='1' /></td>";
	var lineQuantity="<td><input type='text' name='data[EntityItem][quantity]["+lineNo+"]' id='EntityItemQuantity"+lineNo+"' placeholder='0' class='form-control input-sm' lineno='"+lineNo+"' onchange='calculateQuotationTotal()' required='1' /></td>";
	var lineUnitrate="<td><input type='text' name='data[EntityItem][unitrate]["+lineNo+"]' id='EntityItemUnitrate"+lineNo+"' placeholder='0.00' class='form-control input-sm' lineno='"+lineNo+"' onchange='calculateQuotationTotal()' required='1' /></td>";	
	var lineAmount="<td><input type='text' name='data[EntityItem][amount]["+lineNo+"]' id='EntityItemAmount"+lineNo+"' readonly='readonly' class='readonly lineamount form-control input-sm' style='width:100px; float: left;' placeholder='0.00'/> <i class='glyphicon glyphicon-remove-circle' onclick='removeLine("+lineNo+")' title='Remove this row' lineno='"+lineNo+"' style='float: left'></i></td>";	
	var lineRowEnd='</tr>';
	var row = lineRowStart+lineItem+lineDescription+lineQuantity+lineUnitrate+lineAmount+lineRowEnd;
	$('#itemsTbody').append(row);	
}

function removeLine(lineno) {
	$('#lineNo'+lineno).fadeOut();
	setTimeout("deleteLine("+lineno+")", 1000);
}
function deleteLine(lineno) {
	$('#lineNo'+lineno).remove();
	calculateQuotationTotal();
}

function calculateQuotationTotal() {    
    var subtotal=parseInt(0);
    var cgst = ($('#QuotationCgst').val()) ? $('#QuotationCgst').val() : 0;
    var sgst = ($('#QuotationSgst').val()) ? $('#QuotationSgst').val() : 0;
    var discount = ($('#QuotationDiscount').val()) ? $('#QuotationDiscount').val() : 0;
    for(i=0; i<=lineNo; i++) {
        var qty = ($('#EntityItemQuantity'+i).val()) ? $('#EntityItemQuantity'+i).val() : 0;
        var unitrate = ($('#EntityItemUnitrate'+i).val()) ? $('#EntityItemUnitrate'+i).val() : 0;

        var amount = qty*unitrate;
        $('#EntityItemAmount'+i).val(amount.toFixed(2));

        var lineAmount = ($('#EntityItemAmount'+i).val()) ? $('#EntityItemAmount'+i).val() : 0;
        if(lineAmount > 0) {
            subtotal = subtotal+parseInt(lineAmount);
        }
    }
	
    total = 0;	
	// calculate subtotal with discount.
    if(discount > 0) {
        total = subtotal-((discount*subtotal)/100);
    }
	else {
		total = subtotal;
	}
	
    var tax = parseFloat(cgst)+parseFloat(sgst);
	// calculate total including tax.
    if(tax > 0) {
        total = total+((tax*total)/100);
    }
    
    $('#QuotationSubtotal').val(subtotal.toFixed(2));
    $('#QuotationTotalAmount').val(total.toFixed(2));
}

$(function() {
	$( "#datepicker" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker" ).datepicker( "option", "altField", "#alternate");
	$( "#datepicker" ).datepicker( "option", "altFormat", "DD, d MM, yy");	
	$( "#datepicker" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Quotation']['date'])) {
	?>
	$( "#datepicker" ).attr( "value", "<?php echo $this->data['Quotation']['date'];?>" );
	<?php
	}
	else{
	?>
	$( "#datepicker" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>

	$( "#datepicker2" ).datepicker({ altFormat: "yy-mm-dd" });
	$( "#datepicker2" ).datepicker( "option", "dateFormat", "yy-mm-dd");
	$( "#datepicker2" ).datepicker( "option", "altField", "#alternate2");
	$( "#datepicker2" ).datepicker( "option", "altFormat", "DD, d MM, yy");	
	$( "#datepicker2" ).datepicker( "option", "defaultDate", '' );
	<?php
	if(isset($this->data['Quotation']['date'])) {
	?>
	$( "#datepicker2" ).attr( "value", "<?php echo $this->data['Quotation']['date'];?>" );
	<?php
	}
	else{
	?>
	$( "#datepicker2" ).attr( "value", "<?php echo date('Y-m-d');?>" );	
	<?php
	}	
	?>
});

</script>