 <table width="100%" class="srstable" border="1">
	
	<tr>
		<td class="srscell-body"  colspan="12" align="center">
		  <?php  echo 'from '.$from_date.' To '.$to_date; ?>		</td>
	</tr>
	
	<tr>
		<td width="5%" class="srscell-body">Date</td>
		<td class="srscell-body"  >Vch No</td>
		<td class="srscell-body" >Vch Type</td>
		<td class="srscell-body" >Particular</td>
		<td width="11%" class="srscell-body" align="right">Debit</td>
		<td width="13%" class="srscell-body" align="right">Credit</td>
		<!--<td width="13%" class="srscell-body" align="right">Balance</td>-->
	</tr>
		
	
	<?PHP 	if($acctype=='GROUP')	{	
			//$acc_id=0;
			echo $acctype;
			$dr_open_balance=$cr_open_balance=$cr_tran_total=$dr_tran_total=0;
			$tot_grp_cr=$tot_grp_dr=0;
			
			$sqlinv="select * from acc_group_ledgers 
			where  parent_id=".$acc_id." AND status='ACTIVE' order by acc_name";
			$ledgers =$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($ledgers as $ledger)
			{ 
				$acc_id_ledger=$ledger->id;
			    $lname=$ledger->acc_name;
				$cr_closing_balance=$dr_closing_balance=0;
				
				if($acc_id_ledger<>'')
				{	
					$cr_closing_balance=$cr_open_balance=$this->accounts_model->ledger_opening_balance($acc_id_ledger,$from_date,'CR');
					$dr_closing_balance=$dr_open_balance=$this->accounts_model->ledger_opening_balance($acc_id_ledger,$from_date,'DR');
				}		
		
	?>
	<tr  style="background-color:#CC6633">
	<td class="srscell-body"><?php echo $from_date; ?></td>
	<td class="srscell-body"></td>
	<td class="srscell-body"></td>
	<td class="srscell-body"  ><?php echo $lname.' Opening Balance'; ?></td>
	<td class="srscell-body"  ><?php echo $dr_open_balance; ?></td>
	<td class="srscell-body"  ><?php echo $cr_open_balance; ?></td>
	<?php /*?><td class="srscell-body"  >
	<?php 
	if($dr_open_balance>$cr_open_balance)
	{echo ($dr_open_balance-$cr_open_balance).'(DR)';}
	else if($dr_open_balance<$cr_open_balance)
	{echo ($cr_open_balance-$dr_open_balance).'(CR)';}
	else
	{ echo ''; }	
	?></td><?php */?>
	
	</tr>
	<!--transactions-->
	<?PHP 	
				
			$sqlinv="select a.id,a.tran_date,a.comment,b.cr_ledger_account,
			b.dr_ledger_account,b.amount,a.tran_code,a.TRAN_TYPE,b.matching_tran_id
			from acc_tran_header a,acc_tran_details b 
			where a.id=b.acc_tran_header_id 
			and a.tran_date between  '".$from_date."' and '".$to_date."'
			 and (b.cr_ledger_account=".$acc_id_ledger."  or b.dr_ledger_account=".$acc_id_ledger.") order by a.tran_date,a.id;";
			$ledger_transactions =$this->projectmodel->get_records_from_sql($sqlinv);
			
			foreach ($ledger_transactions as $ledger_transaction)
			{	
				$amount_dr=$amount_cr=0;
				$trandate=$ledger_transaction->tran_date;
				$cr_ledger_account=$ledger_transaction->cr_ledger_account;
				$dr_ledger_account=$ledger_transaction->dr_ledger_account 	;
				$matching_tran_id=$ledger_transaction->matching_tran_id;
				
			
			if($acc_id_ledger==$cr_ledger_account)
			{ 
				$transactions="select * from  acc_tran_details where  acc_tran_header_id =".$ledger_transaction->id." 
				and dr_ledger_account>0  and matching_tran_id=".$matching_tran_id;
				$transactions = $this->projectmodel->get_records_from_sql($transactions);	
				$cr_closing_balance=$cr_closing_balance+$ledger_transaction->amount;
				
			}
			else
			{ 
				$transactions="select * from  acc_tran_details where  acc_tran_header_id =".$ledger_transaction->id."
				 and cr_ledger_account>0 and matching_tran_id=".$matching_tran_id;
				$transactions = $this->projectmodel->get_records_from_sql($transactions);	
				$dr_closing_balance=$dr_closing_balance+$ledger_transaction->amount;
			}	
			
			foreach ($transactions as $transaction)
			{
				//echo $transaction->dr_ledger_account;
	?>
	
	<tr>
	<td class="srscell-body"><?php echo $trandate; ?></td>
	<td class="srscell-body"><?php echo $ledger_transaction->tran_code; ?></td>
	<td class="srscell-body"><?php echo $ledger_transaction->TRAN_TYPE; ?></td>
	<td class="srscell-body" >
	<?php 
		if($transaction->dr_ledger_account>0)
		 {
			 $whr="id=".$transaction->dr_ledger_account;
			 echo $this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr); 
		 }
		 else
		 {
			$whr="id=".$transaction->cr_ledger_account;
			echo $this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr); 
		 
		 }
	 ?>
	</td>
	<td class="srscell-body"  ><?php if($transaction->dr_ledger_account<>0) {echo $transaction->amount;} ?></td>
	<td class="srscell-body"  ><?php if($transaction->cr_ledger_account<>0) {echo $transaction->amount;} ?></td>
	
	</tr>
		
	<?php }} ?>
	
	<tr  style="background-color:#009966">
	<td class="srscell-body"><?php echo $to_date; ?></td>
	<td class="srscell-body"  colspan="3"><?php echo $lname.' Closing Balance'; ?></td>
	<td class="srscell-body"  ><?php 
	if($dr_closing_balance>$cr_closing_balance)
	{echo round(($dr_closing_balance-$cr_closing_balance),2);}
	 ?></td>
	<td class="srscell-body"  ><?php 
	if($dr_closing_balance<$cr_closing_balance)
	{echo round(($cr_closing_balance-$dr_closing_balance),2);}
	?></td>
	<!--<td class="srscell-body"  ></td>-->
	</tr>	
	<?php }} ?>
	
	
	<?PHP 	if($acctype=='LEDGER')	{	
			//$acc_id=0;
			$dr_open_balance=$cr_open_balance=$cr_tran_total=$dr_tran_total=0;
			$tot_grp_cr=$tot_grp_dr=0;
			
			
			$sqlinv="select * from acc_group_ledgers 
			where  id=".$acc_id." AND status='ACTIVE' order by acc_name";
			$ledgers =$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($ledgers as $ledger)
			{ 
				$acc_id_ledger=$ledger->id;
			    $lname=$ledger->acc_name;
				
				if($acc_id_ledger<>'')
				{	
					$cr_open_balance=$this->accounts_model->
					ledger_opening_balance($acc_id_ledger,$from_date,'CR');
					
					$dr_open_balance=$this->accounts_model->
					ledger_opening_balance($acc_id_ledger,$from_date,'DR');
				}		
		
	?>
	<tr  style="background-color:#CC6633">
	<td class="srscell-body"><?php echo $from_date; ?></td>
	<td class="srscell-body"  colspan="3"><?php echo $lname.' Opening Balance'; ?></td>
	<td class="srscell-body"  ><?php echo $dr_open_balance; ?></td>
	<td class="srscell-body"  ><?php echo $cr_open_balance; ?></td>
	</tr>
	<!--transactions-->
	<?PHP 	
				
			$sqlinv="select a.id,a.tran_date,a.comment,b.cr_ledger_account,
			b.dr_ledger_account,b.amount,a.tran_code,a.comment transaction_details,b.matching_tran_id,a.TRAN_TYPE
			from acc_tran_header a,acc_tran_details b 
			where a.id=b.acc_tran_header_id 
			and a.tran_date between  '".$from_date."' and '".$to_date."'
			 and (b.cr_ledger_account=".$acc_id_ledger." 
			 or b.dr_ledger_account=".$acc_id_ledger.") order by a.tran_date,a.id;";
			$ledger_transactions =$this->projectmodel->get_records_from_sql($sqlinv);
			foreach ($ledger_transactions as $ledger_transaction)
			{	
		    $amount_dr=$amount_cr='';
			$trandate=$ledger_transaction->tran_date;
			$cr_ledger_account=$ledger_transaction->cr_ledger_account;
			$dr_ledger_account=$ledger_transaction->dr_ledger_account 	;
			$matching_tran_id=$ledger_transaction->matching_tran_id;
			
			
			if($acc_id_ledger==$cr_ledger_account)
			{ 
				$transactions="select * from  acc_tran_details where  acc_tran_header_id =".$ledger_transaction->id." 
				and dr_ledger_account>0  and matching_tran_id=".$matching_tran_id;
				$transactions = $this->projectmodel->get_records_from_sql($transactions);	
				//$cr_closing_balance=$cr_closing_balance+$ledger_transaction->amount;
				
			}
			else
			{ 
				$transactions="select * from  acc_tran_details where  acc_tran_header_id =".$ledger_transaction->id."
				 and cr_ledger_account>0 and matching_tran_id=".$matching_tran_id;
				$transactions = $this->projectmodel->get_records_from_sql($transactions);	
				//$dr_closing_balance=$dr_closing_balance+$ledger_transaction->amount;
			}	
			
			foreach ($transactions as $transaction)
			{
		
	?>
	
	<tr>
	<td class="srscell-body"><?php echo $trandate; ?></td>
	<td class="srscell-body"><?php echo $ledger_transaction->tran_code; ?></td>
	<td class="srscell-body"><?php echo $ledger_transaction->TRAN_TYPE; ?></td>
	<td class="srscell-body" >
	<?php 
		if($transaction->dr_ledger_account>0)
		 {
			 $whr="id=".$transaction->dr_ledger_account;
			 echo $this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr); 
		 }
		 else
		 {
			$whr="id=".$transaction->cr_ledger_account;
			echo $this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr); 
		 
		 }
	 ?>
	</td>
	<td class="srscell-body"  ><?php if($transaction->dr_ledger_account<>0) {echo $transaction->amount;} ?></td>
	<td class="srscell-body"  ><?php if($transaction->cr_ledger_account<>0) {echo $transaction->amount;} ?></td>
	
	</tr>
	
	
	<?php }}}} ?>
	
	</table>
	
	
	