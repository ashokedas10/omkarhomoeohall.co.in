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
<h2><span class="tcat">Cash- Bank Book  </span></h2>
<p align="center"><span class="style1">
<?php echo $this->session->userdata('alert_msg'); ?></span></p>

<form action="<?php echo ADMIN_BASE_URL?>accounts_controller/cash_bank_book/save/" 
	name="frmreport" id="frmreport" method="post">
	<table  border="0" cellpadding="0" cellspacing="0" class="srstable"> 
		
	<tr>
	
	<td width="51" class="srscell-head-lft">Account</td>
              <td class="srscell-body" colspan="2">
			  <select id="ledger_account_header" data-rel="chosen" 
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
    </td>
	
	
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
		<input type="button" onClick="myExportToExcel();" value="Excel" class="btn srs-btn-reset" />
	  </td>
	</tr>
          
  </table>
</form>

<div id="printablediv">

 <table width="100%" class="srstable">
	
	<tr>
		<td class="srscell-body"  colspan="12" align="center">
		Cash/ Bank Book <?php 
		
		if($ledger_account_header<>'')
		{
				$sqlinv="select * from acc_group_ledgers 	
				where id=".$ledger_account_header." " ;
				$ledger_names=$this->projectmodel->get_records_from_sql($sqlinv);
				foreach ($ledger_names as $ledger_name)
				{$LED_NAME=$ledger_name->acc_name;}
		
		echo '('.$LED_NAME.') 
		from '.$from_date.' To '.$to_date;
		}
		 ?>
		</td>
	</tr>
	
	<tr>
		<td class="srscell-body">Date</td>
		<td class="srscell-body">No</td>
		<td class="srscell-body"  colspan="3">Particular</td>
		<td class="srscell-body">Debit</td>
		<td class="srscell-body">Credit</td>
	</tr>
	
	<tr>
		<td class="srscell-body"><?php echo $from_date;?></td>
		<td class="srscell-body"  colspan="4">Opening Balance</td>
		<td class="srscell-body"><?php echo $dr_open_balance;?></td>
		<td class="srscell-body"><?php echo $cr_open_balance;?></td>
	</tr>
	
	
	 <?php 		
	 	$tot_cr_balance=$cr_open_balance;
		$tot_dr_balance=$dr_open_balance;
		
		foreach ($accounts_transactions as $row)
		{ 	
			$tran_code=$row->tran_code;
			$tran_date=$row->tran_date;
			$amount=$row->amount;
			$cr_ledger_account=$row->cr_ledger_account;
			$dr_ledger_account=$row->dr_ledger_account;
			$credit_balance=$debit_balance=0;
			$particular='';
			
			if($ledger_account_header==$cr_ledger_account)
			{ 
				$credit_balance=$amount;
				$tot_cr_balance=$tot_cr_balance+$credit_balance;
				$sqlinv="select * from acc_group_ledgers 	
				where id=".$dr_ledger_account." " ;
				$ledger_names=$this->projectmodel->get_records_from_sql($sqlinv);
				foreach ($ledger_names as $ledger_name)
				{$particular=$ledger_name->acc_name;}
					
			}
			else
			{ 
				$debit_balance=$amount;
				$tot_dr_balance=$tot_dr_balance+$debit_balance;
				
				$sqlinv="select * from acc_group_ledgers 	
				where id=".$cr_ledger_account." ";
				$ledger_names=$this->projectmodel->get_records_from_sql($sqlinv);
				foreach ($ledger_names as $ledger_name)
				{$particular=$ledger_name->acc_name;}
			
			}
			
	?>
	
	
	<tr>
	
	<td class="srscell-body" ><?php echo $tran_date; ?></td>
	<td class="srscell-body"><?php echo $tran_code; ?></td>
	<td class="srscell-body"  colspan="3"><?php echo $particular; ?></td>
	<td class="srscell-body"><?php echo $debit_balance;?></td>
	<td class="srscell-body"><?php echo $credit_balance;?></td>			
	</tr>
	
	<?php } ?>
	
	
	<tr>
	<td class="srscell-body"  colspan="5">Balance</td>
	<td class="srscell-body">
	<?php 
	$drbalance=$crbalance=0;
	if($tot_dr_balance>=$tot_cr_balance)
	{ $drbalance=$tot_dr_balance-$tot_cr_balance;}
	else
	{  $crbalance=$tot_cr_balance-$tot_dr_balance; }
	echo $crbalance;
	?>
	</td>
	<td class="srscell-body"><?php echo $drbalance;	?></td>			
	</tr>
	
	<tr>
	<td class="srscell-body"  colspan="5">Total</td>
	<td class="srscell-body">
	<?php 
	$tot=0;
	if($tot_dr_balance>=$tot_cr_balance)
	{ $tot=$tot_dr_balance;}
	else
	{ $tot=$tot_cr_balance; }
	echo $tot;
	?>
	</td>
	<td class="srscell-body"><?php echo $tot;	?></td>			
	</tr>
		
  </table>
    

  
</div>