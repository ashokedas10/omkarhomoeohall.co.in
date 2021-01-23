<script language="javascript" type="text/javascript">		
		function trial_balance_detail(accid,fromdate,todate,actype) {
			//	alert(fromdate);
		var base_url = '<?php echo ADMIN_BASE_URL.'accounts_controller/trial_balance_detail/';  ?>';
			url=base_url+accid+'/'+fromdate+'/'+todate+'/'+actype+'/';
			newwindow=window.open(url,'name','height=600,width=800');
			if (window.focus) {newwindow.focus()}
			return false;
		}
</script>

<!--data table start-->
<style type="text/css">
<!--
.style1 {
	color: #CC3300;
	font-weight: bold;
	font-size: 14px;
}
-->
</style>
<h2><span class="tcat">TRIAL </span> BALANCE </h2>
<p align="center"><span class="style1">

<?php echo $this->session->userdata('alert_msg'); ?></span></p>

<form action="<?php echo ADMIN_BASE_URL?>accounts_controller/trial_balance/save/" 
	name="frmreport" id="frmreport" method="post">
	<table  border="0" cellpadding="0" cellspacing="0" class="srstable"> 
		
	<tr>
	
	
	
	
	<td width="75" class="srscell-head-lft">From Date</td>
	<td class="srscell-body"  colspan="2">
	
	<input type="text" id="from_date" class="srs-txt-mini"
			  value="<?php  if($from_date=='') 
			  { echo date('Y-m-d');} else { echo $from_date; } ?>" 
			  name="from_date"/>
<img src="<?php echo BASE_PATH_ADMIN;?>theme_files/calender_final/images2/cal.gif" 
onclick="javascript:NewCssCal ('from_date','yyyyMMdd')" 
style="cursor:pointer"/>
	  </td>
	
	
	<td width="84" class="srscell-head-lft">To Date</td>
	<td class="srscell-body"  colspan="2">
	
	<input type="text" id="to_date" class="srs-txt-mini"
			  value="<?php  if($to_date=='') 
			  { echo date('Y-m-d');} else { echo $to_date; } ?>" 
			  name="to_date"/>
<img src="<?php echo BASE_PATH_ADMIN;?>theme_files/calender_final/images2/cal.gif" 
onclick="javascript:NewCssCal ('to_date','yyyyMMdd')" 
style="cursor:pointer"/>
	
		
	  </td>	
		
		
		<td width="105" class="srscell-body">
		<input name="submit" type="submit" value="Submit" class="btn srs-btn-reset"/>
		<input type="button" onClick="myExportToExcel();"
		 value="Excel" class="btn srs-btn-reset" />
		
		
		
		
	<?php /*?>	<a href="<?php echo $tran_link.'delete/'; ?>">
		<input type="button" class="btn btn-red" value="Delete" id="Save" name="Save" 
		onClick="return confirm('Do you want to Delete ALL?');" >
		</a>
		<?php */?>
		
	  </td>
	</tr>
          
  </table>
</form>

<div id="printablediv">

 <table width="100%" class="srstable">
	
	<tr>
		<td class="srscell-body"  colspan="12" align="center">
		Trail Balance  <?php  echo 'from '.$from_date.' To '.$to_date; ?>		</td>
	</tr>
	
	<tr>
		<td width="5%" class="srscell-body">Code</td>
		<td class="srscell-body"  colspan="3">Ledger Name</td>
		<td width="11%" class="srscell-body" align="right">Debit</td>
		<td width="13%" class="srscell-body" align="right">Credit</td>
		<td width="13%" class="srscell-body" align="right">View Detail</td>
	</tr>
	
	 <?php 		
	 	$debit_total=$credit_total=0;
		foreach ($accounts_transactions as $row)
		{ 	
			$acc_id_group=$row->id;
			$acc_code=$row->acc_code;
			$acc_name=$row->acc_name;
			$SHOW_IN_TRIAL_BALANCE=$row->SHOW_IN_TRIAL_BALANCE;
			
		$dr_open_balance=$cr_open_balance=$cr_tran_total=$dr_tran_total=0;
					
	?>
	
	
	
	<?PHP
		if($SHOW_IN_TRIAL_BALANCE=='GROUP')
		{	
			$acc_id=0;
			$dr_open_balance=$cr_open_balance=$cr_tran_total=$dr_tran_total=0;
			$tot_grp_cr=$tot_grp_dr=0;
			
			$sqlinv="select * from acc_group_ledgers 
			where  parent_id=".$acc_id_group." AND status='ACTIVE' order by acc_name";
			$ledgers =$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($ledgers as $ledger)
			{ 
			
				$acc_id=$ledger->id;
			    $lname=$ledger->acc_name;
				
				if($acc_id<>'')
				{	
					$cr_open_balance=$this->accounts_model->
					ledger_opening_balance($acc_id,$from_date,'CR');
					
					$dr_open_balance=$this->accounts_model->
					ledger_opening_balance($acc_id,$from_date,'DR');
				}		
		
				$sqlinv="select sum(b.amount) amount
				from acc_tran_header a,acc_tran_details b 
				where a.id=b.acc_tran_header_id 
				and a.tran_date between  '".$from_date."' and '".$to_date."'
				 and b.cr_ledger_account=".$acc_id." ";
				$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
				foreach ($cr_ledger_accounts as $cr_ledger_account)
				{$cr_tran_total=$cr_ledger_account->amount;}	
						
				$sqlinv="select sum(b.amount) amount
				from acc_tran_header a,acc_tran_details b 
				where a.id=b.acc_tran_header_id 
				and a.tran_date between  '".$from_date."' and '".$to_date."'
				and b.dr_ledger_account=".$acc_id." ";
				$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
				foreach ($cr_ledger_accounts as $cr_ledger_account)
				{$dr_tran_total=$cr_ledger_account->amount;}		
			
				$cr_tran_total=$cr_tran_total+$cr_open_balance;
				$dr_tran_total=$dr_tran_total+$dr_open_balance;	
					
				$tot_grp_cr=$tot_grp_cr+$cr_tran_total;
				$tot_grp_dr=$tot_grp_dr+$dr_tran_total;
				
		}	
				
				
				
				if($tot_grp_cr>=$tot_grp_dr)
				{$credit_total=$credit_total+$tot_grp_cr-$tot_grp_dr;}
				else
				{$debit_total=$debit_total+$tot_grp_dr-$tot_grp_cr;}
				
		
	?>
	<tr>
	<td class="srscell-body"><?php echo $acc_code; ?></td>
	<td class="srscell-body"  colspan="3"><?php echo $acc_name.'(GROUP)'; ?></td>
	<td class="srscell-body" align="right">
	<?php 
	if($tot_grp_dr>=$tot_grp_cr)
	{echo round(($tot_grp_dr-$tot_grp_cr),2);}
	else
	{ echo '0';  }	
	?></td>
	<td class="srscell-body" align="right"><?php 
	if($tot_grp_cr>$tot_grp_dr)
	{echo round(($tot_grp_cr-$tot_grp_dr),2);}
	else
	{ echo '0';  }	
	?></td>	
	<td class="srscell-body" align="right">
	<input type="button" id="btn" value="Detail" class="btn srs-btn-reset"  onclick="trial_balance_detail(<?php echo $acc_id_group;?>,'<?php echo $from_date; ?>','<?php echo $to_date; ?>','GROUP');"  />
	</td>				
	</tr>
	
	<?php } ?>
	
	
	
	<?PHP
		if($SHOW_IN_TRIAL_BALANCE=='LEDGER')
		{	
			
			$sqlinv="select * from acc_group_ledgers 
			where  parent_id=".$acc_id_group." AND status='ACTIVE' order by acc_name";
			$ledgers =$this->projectmodel->get_records_from_sql($sqlinv);
			
			foreach ($ledgers as $ledger)
			{ 	
				$acc_id=$ledger->id;
				$acc_code=$ledger->acc_code;
				$acc_name=$ledger->acc_name;
				
				$dr_open_balance=$cr_open_balance=$cr_tran_total=$dr_tran_total=0;
			
			if($acc_id<>'')
			{	
			$cr_open_balance=$this->accounts_model->ledger_opening_balance($acc_id,
			$from_date,'CR');
			
			$dr_open_balance=$this->accounts_model->ledger_opening_balance($acc_id,
			$from_date,'DR');
			}		
		
		$sqlinv="select sum(b.amount) amount
		from acc_tran_header a,acc_tran_details b 
		where a.id=b.acc_tran_header_id 
		and a.tran_date between  '".$from_date."' and '".$to_date."'
		 and b.cr_ledger_account in (".$acc_id.") ";
		$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
		foreach ($cr_ledger_accounts as $cr_ledger_account)
		{$cr_tran_total=$cr_ledger_account->amount;}		
		
		$sqlinv="select sum(b.amount) amount
		from acc_tran_header a,acc_tran_details b 
		where a.id=b.acc_tran_header_id 
		and a.tran_date between  '".$from_date."' and '".$to_date."'
		and b.dr_ledger_account in (".$acc_id.") ";
		$cr_ledger_accounts =$this->projectmodel->get_records_from_sql($sqlinv);
		foreach ($cr_ledger_accounts as $cr_ledger_account)
		{$dr_tran_total=$cr_ledger_account->amount;}		
			
		$cr_tran_total=$cr_tran_total+$cr_open_balance;
		$dr_tran_total=$dr_tran_total+$dr_open_balance;		
		
		if($cr_tran_total>=$dr_tran_total)
		{$credit_total=$credit_total+$cr_tran_total-$dr_tran_total;}
		else
		{$debit_total=$debit_total+$dr_tran_total-$cr_tran_total;}
	
	if($cr_tran_total>0 or $dr_tran_total>0)
	{
	?>
	<tr>
	<td class="srscell-body"><?php echo $acc_code; ?></td>
	<td class="srscell-body"  colspan="3"><?php echo $acc_name.'(LEDGER)'; ?></td>
	<td class="srscell-body" align="right">
	<?php 
	if($dr_tran_total>=$cr_tran_total)
	{echo round(($dr_tran_total-$cr_tran_total),2);}
	else
	{ echo '0';  }	
	?></td>
	<td class="srscell-body" align="right">
	<?php 
	if($cr_tran_total>$dr_tran_total)
	{echo round(($cr_tran_total-$dr_tran_total),2);}
	else
	{ echo '0';  }	
	?></td>	
	<td class="srscell-body" align="right">
	<input type="button" onclick="trial_balance_detail(<?php echo $acc_id;?>,
	'<?php echo $from_date; ?>','<?php echo $to_date; ?>','LEDGER');" value="Detail" class="btn srs-btn-reset" />	
	</td>					
	</tr>
	
	<?php }}} ?>
	
	
	<?php } ?>
	
	
	<tr>
	<td class="srscell-body"  colspan="4">Total</td>
	<td class="srscell-body" align="right"><?php echo $debit_total;	?></td>		
	<td class="srscell-body" align="right"><?php echo $credit_total;	?></td>	
	<td class="srscell-body" align="right">&nbsp;</td>		
	</tr>
  </table>
    

  
</div>