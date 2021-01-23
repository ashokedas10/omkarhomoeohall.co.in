<?php
		
		$billimg='BILL-'.$table_id;
		$billimg=BASE_PATH_ADMIN.'uploads/'.$billimg.'.png'; 
		
		$challan_no=$challan_date='';	
		$grand_total=0;	
		if($table_id<>0)
		{
			$sql2="select * from invoice_summary where id=".$table_id." ";
			$rowrecord2 = $this->projectmodel->get_records_from_sql($sql2);	
			foreach ($rowrecord2 as $row2)
			{ 
				$invoice_no=$row2->invoice_no; 
				$invoice_date=$row2->invoice_date; 	
				$tbl_party_id=$row2->tbl_party_id;	
				$comment=$row2->comment;
				$invoice_time=$row2->invoice_time;
				$emp_name=$row2->emp_name;
				$tot_cash_discount=$row2->tot_cash_discount;
				$grandtot=$row2->grandtot;
				$rndoff=$row2->rndoff;
			}
		}
				
		$company_records="select * from company_details where id=1";
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	
		$gsttype='sgst_cgst';
		
		if($tbl_party_id<>124)
		{
			$tbl_party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',' id='.$tbl_party_id);
			
			$party_records="select * from tbl_party where id=".$tbl_party_id." ";
			$party_records = $this->projectmodel->get_records_from_sql($party_records);	
			
			$gst2nos=substr($party_records[0]->GSTNo,0,2);
			if($gst2nos=='' || $gst2nos==19)
			{$gsttype='sgst_cgst';}
			else
			{$gsttype='igst';}
			
		}
	?>
	<!--HEADER SECTION-->
	<table  style="width:100%" >
	<tr ><td align="center" colspan="2">Tax Invoice</td></tr>
	
	<tr >
	<td align="left" style="background:#CCCCCC;">Bill From</td>
	<td align="left" style="background:#CCCCCC;">Bill To</td>	
	</tr>
	<tr>
	<td  class="style13" style="height:10px;"><strong><?php echo $company_records[0]->NAME;?></strong></td>
	<td  class="style13" style="height:10px;"><strong><?php echo $party_records[0]->party_name;?></strong></td>	
	</tr>
	<tr>
		<td width="60%">
				<span class="style13">
				Address:<?php echo $company_records[0]->ADDRESS;?><BR>
				Contact No:<?php echo $company_records[0]->PhoneNo;?><BR>
				<?php /*?>Email:<?php echo $company_records[0]->EMAIL_IDS;?><BR><?php */?>
				DL NO:<?php echo $company_records[0]->DLNO1;?><BR>
				GSTIN NO:<?php echo $company_records[0]->GSTNo;?>
				</span>
		</td>
		<td >
				<span class="style13"> 
				Address: <?php echo $party_records[0]->address.'|'.$party_records[0]->city.'|'.$party_records[0]->pin;?><BR>	
			   <?php if($party_records[0]->GSTNo<>''){ echo '<strong>GST No :</strong>'.$party_records[0]->GSTNo.'<br>';} ?>
			   <strong>Invoice No:</strong><?php echo $invoice_no;?>;
			   <BR><strong>Date/Time:</strong><?php echo $invoice_date.' / '.$invoice_time; ?><BR>
			   <?php if($challan_no<>''){?>
			   <strong>Challan No:</strong><?php echo $challan_no.' <br><strong>Date:</strong>'.$challan_date; ?><BR>
			   <?php }?>
				
		</td>
	</tr>	
	
	</table>	