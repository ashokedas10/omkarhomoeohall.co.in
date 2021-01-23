<div class="box-header" style="background-color:#3c8dbc" >	   
	   
	  <div class="col-md-12">
			<span class="info-box-number style1">JOURNAL  BOOK</span>
 	 </div>
</div>
<div class="box-header with-border">
	<?php if($this->session->userdata('alert_msg')<>''){ ?>
	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" 
		aria-hidden="true">&times;</button>
		<h4><i class="icon fa fa-check"></i>
		<?php echo $this->session->userdata('alert_msg'); ?></h4>
		</div>
	<?php } ?>
</div>

<div class="box box-info">

<form action="<?php echo ADMIN_BASE_URL?>accounts_controller/journal_book/save/" 
	name="frmreport" id="frmreport" method="post">
	<table  border="0" cellpadding="0" cellspacing="0" class="srstable"> 
		
	<tr>
	<td width="75" class="srscell-head-lft">From Date</td>
	<td class="srscell-body"  colspan="2">
	
	<input type="text" id="from_date" class="srs-txt"
			  value="<?php  if($from_date=='') 
			  { echo date('Y-m-d');} else { echo $from_date; } ?>" 
			  name="from_date"/>
<img src="<?php echo BASE_PATH_ADMIN;?>theme_files/calender_final/images2/cal.gif" 
onclick="javascript:NewCssCal ('from_date','yyyyMMdd')" 
style="cursor:pointer"/>
	  </td>
	
	
	<td width="84" class="srscell-head-lft">To Date</td>
	<td class="srscell-body"  colspan="2">
	
	<input type="text" id="to_date" class="srs-txt"
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

</div>

<div id="printablediv">

 <table width="100%" class="srstable">
	
	<tr>
		<td style="background: linear-gradient(red, yellow);" 
		 colspan="12" align="center">
		Journal Book  <?php  echo 'from '.$from_date.' To '.$to_date; ?>
		</td>
	</tr>
	
	<tr>
		<td style="background: linear-gradient(#A1D490,  #A1D490);">Date</td>		
		<td style="background: linear-gradient(#A1D490,  #A1D490);">No</td>
		<td style="background: linear-gradient(#A1D490,  #A1D490);"  colspan="3">Credit</td>
		<td style="background: linear-gradient(#A1D490,  #A1D490);">Debit</td>
		<td style="background: linear-gradient(#A1D490,  #A1D490);">Amount</td>
	</tr>
	
	 <?php 		
	 	$tot=0;
		
		foreach ($accounts_transactions as $row)
		{ 	
			$tran_code=$row->tran_code;
			$tran_date=$row->tran_date;
			$amount=$row->amount;
			$cr_ledger_account=$row->cr_ledger_account;
			$dr_ledger_account=$row->dr_ledger_account;
			$cr_ledger_account_name=$dr_ledger_account_name='';
			
					
			
			$sqlinv="select * from acc_group_ledgers 	
			where id=".$cr_ledger_account." ";
			$ledger_names=$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($ledger_names as $ledger_name)
			{$cr_ledger_account_name=$ledger_name->acc_name;}
			
			$sqlinv="select * from acc_group_ledgers 	
			where id=".$dr_ledger_account." " ;
			$ledger_names=$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($ledger_names as $ledger_name)
			{$dr_ledger_account_name=$ledger_name->acc_name;}
			$tot=$tot+$amount;
	?>
	
	
	<tr>
	
	<td class="srscell-body" ><?php echo $tran_date; ?></td>	
	<td class="srscell-body"><?php echo $tran_code; ?></td>
	<td class="srscell-body"  colspan="3"><?php echo $cr_ledger_account_name; ?></td>
	<td class="srscell-body"><?php echo $dr_ledger_account_name;?></td>
	<td class="srscell-body" align="right"><?php echo $amount;?></td>			
	</tr>
	
	<?php } ?>
	
	
	
	
	<tr>
	<td style="background: linear-gradient(#C390D4,  #C390D4);"   colspan="6">Total</td>
	
	<td class="srscell-body" align="right"><?php echo $tot;	?></td>			
	</tr>
		
  </table>
    

  
</div>