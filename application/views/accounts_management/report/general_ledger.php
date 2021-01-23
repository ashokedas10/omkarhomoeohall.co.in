<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
<style type="text/css">
<!--
.style1 {color: #FF6633}
-->
</style>
<div class="panel panel-primary" >
	
	  <div class="panel-body" align="center" style="background-color:#99CC00">
		<h3><span class="label label-default">Accounting Report</span>
		<span class="label label-default">
		<?php 
		if($this->session->userdata('alert_msg')<>''){
		echo '<br><br><br>'.$this->session->userdata('alert_msg');
		}
		 ?>
		</span></h3>
	  </div>
	  
 <form id="frm" name="frm" method="post" action="<?php echo ADMIN_BASE_URL?>accounts_controller/general_ledgers/save/" >  
  <div class="panel-body">  
  
  <div class="form-row">
   
     <div class="form-group col-md-3">
      <label for="inputState">Select Account</label>
	 <select id="ledger_account_header" class="form-control select2"
			  name="ledger_account_header">
              <option value="">Select A/c</option>
                  <?php
					
					foreach ($ledger_account1 as $row){ 						
					?>
                  <option value="<?php echo $row->id; ?>" 
					<?php if($row->id==$ledger_account_header) 
					{ echo 'selected="selected"'; } ?>> 
					<?php echo $row->acc_name; ?> </option>
                  <?php } ?>
        </select>
	
    </div>
   
   
    <div class="form-group col-md-3">
      <label for="inputState">From Date</label>
	 <input type="text" value="<?php echo $fromdate; ?>" id="fromdate"  
	 name="fromdate" class="form-control"  readonly=""> 
	
    </div>
	
	 <div class="form-group col-md-3">
      <label for="inputState">To date</label>
	 <input type="text" value="<?php echo $todate; ?>" id="todate"  
	 name="todate" class="form-control"  readonly=""> 
	 </div>
	 
	  <div class="form-group col-md-3">
	<button type="submit" class="btn btn-primary" name="Save">Submit</button>
   <?php echo $sql_error; ?>
	 </div>
    
  </div>
  
  </div>
</form>  
 	   	
 	
<div class="panel panel-primary"> 	
<div  style="overflow:scroll">
<table  id="example1" class="table table-bordered table-striped">
		<thead>
		
			<tr>	
			<?php
			 	$whr="id=".$ledger_account_header;
				$account_head=$this->projectmodel->GetSingleVal('acc_name',
				'acc_group_ledgers',$whr);
			?>
			
			<td  align="center" colspan="8"><h4><strong>Accounting Transaction of
			<?php echo '<span class="style1">'.$account_head.'</span> From Date :'.
			$fromdate.' - To date:'.
			$todate;?><strong></h4>
			
			
			 </td>	
			</tr>
		
			<tr>			
				<td  align="left">Date</td>	
				<td  align="left">Particular</td>	
				<td  align="left">Vch Type</td>
				<td  align="left">Vch/Invoice No</td>
				<td  align="left">Debit</td>	
				<td  align="left">Credit</td>				
				<td  align="left">Balance</td> 
			</tr>
			
		<tr>
		<td ><?php echo $fromdate;?></td>
		<td   colspan="3">Opening Balance</td>
		<td ><?php echo $dr_open_balance;?></td>
		<td ><?php echo $cr_open_balance;?></td>
		<td ><?php 
		$debit_cumulative_balance=$credit_cumulative_balance=0;
		if($dr_open_balance>$cr_open_balance)
		{
			echo $dr_open_balance.'(Dr)';
			$debit_cumulative_balance=$dr_open_balance;
		}
		else if($cr_open_balance>$dr_open_balance)
		{echo $cr_open_balance.'(Cr)';$credit_cumulative_balance=$cr_open_balance;}
		else {echo '';}
		
		?></td>
		
		</tr>
		
		</thead>
	   
	   <tbody>
			
	<?php 		
	 	$tot_cr_balance=$cr_open_balance;
		$tot_dr_balance=$dr_open_balance;
		
		foreach ($accounts_transactions as $row)
		{ 	
			$acc_tran_header_id=$row->id;
			$tran_code=$row->tran_code;
			$tran_date=$row->tran_date;
			$amount=$row->amount;
			$cr_ledger_account=$row->cr_ledger_account;
			$dr_ledger_account=$row->dr_ledger_account;
			$credit_balance=$debit_balance=0;
			$particular='';			
			$vch_type=$row->TRAN_TYPE;
			$matching_tran_id=$row->matching_tran_id;
			
			//$truck_id=$row->truck_id;
			
			/*$whr="id=".$truck_id;
			$vehicle_no=$this->projectmodel->GetSingleVal('retail_name','stockist',$whr);*/
			
			if($ledger_account_header==$cr_ledger_account)
			{ 
				$transactions="select * from  acc_tran_details where  acc_tran_header_id =".$acc_tran_header_id." 
				and dr_ledger_account>0  and matching_tran_id=".$matching_tran_id;
				$transactions = $this->projectmodel->get_records_from_sql($transactions);	
			}
			else
			{ 
				$transactions="select * from  acc_tran_details where  acc_tran_header_id =".$acc_tran_header_id." 
				and cr_ledger_account>0 and matching_tran_id=".$matching_tran_id;
				$transactions = $this->projectmodel->get_records_from_sql($transactions);	
			}
			
		foreach ($transactions as $transaction)
		{ 
			
	 	?>
				<tr>
				
				<td align="left"><?php echo $tran_date; ?></td>
				<td align="left"><?php 
				
				$particular='';
				 if($transaction->dr_ledger_account>0)
				 {
					 $whr="id=".$transaction->dr_ledger_account;
					 $particular= $this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr); 
				 }
				 if($transaction->cr_ledger_account>0)
				 {
					 $whr="id=".$transaction->cr_ledger_account;
					 $particular= $this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr); 
				 }
				 
				 $whr=" acc_tran_details_id=".$transaction->id;
				 $acc_tran_details_details=$this->projectmodel->GetMultipleVal('*','acc_tran_details_details',$whr,' id');
				 $group1_cnt=sizeof($acc_tran_details_details);	 
				 for($group1=0;$group1<$group1_cnt;$group1++)
				 {	
				 	$trandtl='';
					if($acc_tran_details_details[$group1]['BILL_INSTRUMENT_NO']<>'')
					{$trandtl=$trandtl.'Bill/Inst No:'.$acc_tran_details_details[$group1]['BILL_INSTRUMENT_NO'];}
					if($acc_tran_details_details[$group1]['CHQDATE']<>'')
					{$trandtl=$trandtl.'| Inst date:'.$acc_tran_details_details[$group1]['CHQDATE'];}
					if($acc_tran_details_details[$group1]['BANKNAME']<>'')
					{$trandtl=$trandtl.'| Bnk Name:'.$acc_tran_details_details[$group1]['BANKNAME'];}
										
					 $particular=$particular.'<br>'.$trandtl;
				 }
				 	echo $particular;			
				 
				
				?></td>
				<td align="left"><?php echo $vch_type; ?></td>
				<td align="left"><?php echo $tran_code; ?></td>
			
				<td align="left"><?php if($transaction->dr_ledger_account>0) {echo $transaction->amount;} ?></td>
				<td align="left"><?php if($transaction->cr_ledger_account>0) {echo $transaction->amount;}  ?></td>
				<td align="left"><?php 
		
				if($transaction->dr_ledger_account<>0) 
				{$debit_cumulative_balance=$debit_cumulative_balance+$transaction->amount; $tot_dr_balance=$tot_dr_balance+$transaction->amount;} 		
		 
			 if($transaction->cr_ledger_account<>0) 
			 {$credit_cumulative_balance=$credit_cumulative_balance+$transaction->amount; $tot_cr_balance=$tot_cr_balance+$transaction->amount;}
						
		if($debit_cumulative_balance>$credit_cumulative_balance)
		{echo $debit_cumulative_balance-$credit_cumulative_balance.'(Dr)';}
		else if($credit_cumulative_balance>$debit_cumulative_balance)
		{echo $credit_cumulative_balance-$debit_cumulative_balance.'(Cr)';}
		else {echo '';}
		
		?></td>
				
			</tr>
			<?php }} ?>	
	</tbody>
	<tfoot>		
			<tr>
	<td style="background: linear-gradient(#C390D4,  #C390D4);"  
	colspan="4">Balance</td>
	
	<td >
	<?php 
	$drbalance=$crbalance=0;
	if($tot_dr_balance>=$tot_cr_balance)
	{ $drbalance=$tot_dr_balance-$tot_cr_balance;}
	else
	{  $crbalance=$tot_cr_balance-$tot_dr_balance; }
	echo $crbalance;
	?>
	</td>
	<td ><?php echo $drbalance;	?></td>	
	<td ><?php //echo $drbalance;	?></td>			
	</tr>
	
	<tr>
	<td   colspan="4">Total</td>
	<td >
	<?php 
	$tot=0;
	if($tot_dr_balance>=$tot_cr_balance)
	{ $tot=$tot_dr_balance;}
	else
	{ $tot=$tot_cr_balance; }
	echo $tot;
	?>
	</td>
	<td ><?php echo $tot;	?></td>	
	<td ><?php // echo $tot;	?></td>			
	</tr>
	</tfoot>				
			
	 
</table>   
</div>
</div>	    	
	
 
</div>
  
 
<script src="<?php echo BASE_PATH_ADMIN;?>theme_files/auto_complete/auto-complete.js">
</script>
<script>
		var demo1 = new autoComplete({
            selector: '#employee_id',
            minChars: 1,
            source: function(term, suggest){
                term = term.toLowerCase();
             	 var choices = [
				 <?php $ItemName='';
				if(count($employee_list) > 0){
				foreach ($employee_list as $row){ 
				$ac=$row->name;
				$ItemName=$ItemName."'".$ac."',";
				}}	
			     echo rtrim($ItemName,',');					
				?>
				 ];
                var suggestions = [];
                for (i=0;i<choices.length;i++)
                    if (~choices[i].toLowerCase().indexOf(term)) 
					suggestions.push(choices[i]);
                suggest(suggestions);
            }
        });
				
					 
</script>
	
 <script>
  
     $("#tran_date").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#tran_date").change(function() {
	 var  trandate = $('#tran_date').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#tran_date").val(trandate);
	});
	
	
	 $("#chqdate").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#chqdate").change(function() {
	 var  trandate = $('#chqdate').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#chqdate").val(trandate);
	});
	
     $("#fromdate").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#fromdate").change(function() {
	 var  trandate = $('#fromdate').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#fromdate").val(trandate);
	});
	
	 $("#todate").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#todate").change(function() {
	 var  trandate = $('#todate').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#todate").val(trandate);
	});
	
  </script>
  
	<script 
	src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js" 
	async defer></script>
	
	