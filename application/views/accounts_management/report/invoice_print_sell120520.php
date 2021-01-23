<style type="text/css">

html{
width: 90%;
height: 100%;
padding: 5px;
/*margin: 20px;*/
margin-left:20px;
margin-right:20px;
margin-bottom:10px;
margin-top:10px;
}

.style12 {
	font-size: 10px;
	font-family: 'Bitter', serif;
	/*font-weight: bold;*/
}
.style13 {
	font-size: 12px;
	font-family: 'Bitter', serif;
	font-weight: bold;
}
.style14 {font-size: 10px;
	font-family: 'Bitter', serif;
	/*font-weight: bold;*/
	
	}
	
.style16 {font-size: 14px;
	font-family: 'Bitter', serif;
	}
   </style>

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
				$challan_no=$row2->challan_no;
				$challan_date=$row2->challan_date;
				$invoice_time=$row2->invoice_time;
				$emp_name=$row2->emp_name;
				$tot_cash_discount=$row2->tot_cash_discount;
				$grandtot=$row2->grandtot;
				$rndoff=$row2->rndoff;
			}
		}
				
		
		$company_id=$this->session->userdata('COMP_ID');
		$company_records="select * from company_details where id=".$company_id;				
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	
		$gsttype='sgst_cgst';
		$gst_colspan=27;
		if($tbl_party_id<>124)
		{
			$tbl_party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',' id='.$tbl_party_id);
			$party_records="select * from tbl_party where id=".$tbl_party_id." ";
			$party_records = $this->projectmodel->get_records_from_sql($party_records);	
			$gst2nos=substr($party_records[0]->GSTNo,0,2);
			if($gst2nos=='' || $gst2nos==19)
			{$gsttype='sgst_cgst';$gst_colspan=27;}
			else
			{$gsttype='igst';$gst_colspan=26;}
		}
		//page setting
		// 22 list when remaing count is upto 22 .if count >22 next page 
		$no_of_rows=$total_amt=0;
		$total_disc_amt=0;
		$total_taxable_amt=0;
		$total_cgst_amt=0;
		$total_sgst_amt=0;
		$total_igst_amt=0;
		//$grand_total=0;	
		
		$limit_start=$limit_end=$total_printed=$total_qnty=$tot_cnt=$total_body_row_printed=0;
		$no_of_item_without_footer=30;
		$no_of_item_with_footer=22;	
		$header_print_status='YES';
		$no_of_das_print=0;
		
		$sql="select count(*) cnt from invoice_details where PRODUCT_TYPE in ('FINISH','MIXTURE') and invoice_summary_id=".$table_id."   ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row){$total_printed=$tot_cnt=$row->cnt;}	
		
		$product_srl=0;	
		
	?>

	<!-- loop will continue till all data print -->
	<?php while($total_printed >0) {?>

		<!-- HEADER SECTION -->
		<table   style="width:100%;page-break-after: always" >
		<!-- HEADER SECTION END-->
		<tr >
		<td align="center"  colspan="<?php echo $gst_colspan; ?>">
			<table style="width:100%">
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
				<td width="60%" >
						<span class="style13">
						Address:<?php echo $company_records[0]->ADDRESS;?><BR>
						Contact No:<?php echo $company_records[0]->PhoneNo;?><BR>						
						DL NO:<?php echo $company_records[0]->DLNO1;?><BR>
						GSTIN NO:<?php echo $company_records[0]->GSTNo;?>
						</span>
				</td>
					<td >
						<span class="style13" > 
						Address: <?php echo $party_records[0]->address.'|'.$party_records[0]->city.'|'.$party_records[0]->pin;?><BR>	
					   <?php if($party_records[0]->GSTNo<>''){ echo '<strong>GST No :</strong>'.$party_records[0]->GSTNo.'<br>';} ?>
					   
					   <strong>Invoice No:</strong><?php echo $invoice_no.' |<strong>Dt/time:</strong>'.$invoice_date.' / '.$invoice_time;?><br>
					   
					   <?php if($challan_no<>''){?>
					   <strong>Order No:</strong><?php echo $challan_no.' |<strong>Date:</strong>'.$challan_date; ?><br>
					   <?php }?>
					   </span>
					   
					</td>
			</tr>	
			
		  </table>
		</td>
		</tr>
		
		
		<!-- BODY SECTION -->
		<tr   bgcolor="#CCCCCC">
			<td width="2%" class="style12" > Srl </td>
			<td width="1%"  class="style12" >| </td>
			<td width="2%" class="style12" > HSN Code </td>
			<td width="1%"  class="style12" >| </td>
			<td width="30%" class="style12" >Product </td>
			<td width="1%"  class="style12" >| </td>
			<td width="3%" class="style12" > Batch </td>
			<td width="1%"  class="style12" >| </td>
			<!--<td width="3%" class="style12"  > MFG </td>
			<td width="1%"  class="style12" >| </td>-->
			<td width="2%" class="style12" > EXP </td>
			<td width="1%" class="style12" >| </td>
			<td width="3%" align="right" class="style12"> MRP </td>
			<td width="1%"  class="style12" >| </td>
			<td width="2%" align="right" class="style12"> Qty  </td>
			<td width="1%"  class="style12" >| </td>
			<td width="2%" align="right" class="style12"> Rate </td>
			<td width="2%"  class="style12" >| </td>
			<td width="2%" align="right" class="style12"> Disc % </td>
			<td width="1%"  class="style12" >| </td>
			<td width="3%" align="right" class="style12"> Taxable Amt </td>
			<td width="1%"  class="style12" >| </td>
			
			<?PHP if($gsttype=='sgst_cgst' ){ ?>
			<td width="4%" align="right" class="style12"> CGST% </td>
			<td width="1%"  class="style12" >| </td>	
			<td width="4%" align="right" class="style12"> SGST% </td>
			<td width="1%"  class="style12" >| </td>	
			<?PHP }else{ ?>
			<td width="3%" align="right" class="style12"> IGST% </td>
			<td width="1%"  class="style12" >| </td>	
			<?PHP } ?>
			
			<td width="27%"  align="right" class="style12">Grand Total </td>
			<td width="27%"  align="right" class="style12">|</td>
  	</tr>
		
		<?php
		
		//$total_printed=$tot_cnt

		if($total_printed<=$no_of_item_with_footer)
		{$limit_end=$no_of_item_with_footer;}
		else
		{$limit_end=$no_of_item_without_footer;}
	
											
		$sql="select * from invoice_details where  PRODUCT_TYPE in ('FINISH','MIXTURE') and invoice_summary_id=".$table_id." 
		ORDER BY  id Asc  LIMIT ".$limit_start." , ".$limit_end." ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		$i =0;
		//$rowcounts=count($rowrecord);
		//if(count($rowrecord) > 0){
		foreach ($rowrecord as $row)
		{ 
			$alt = ($i%2==0)?'alt1':'alt2';
			$i =$i +1;
			$stotal=0;		
			$tax_per=$row->tax_per;		
			$hsncode=$this->projectmodel->GetSingleVal('hsncode','productmstr',' id='.$row->product_id);					
			$product_name=$row->product_name;
			$total_amt=$total_amt+$row->subtotal;
			$total_disc_amt=$total_disc_amt+$row->disc_amt;
			$total_taxable_amt=$total_taxable_amt+$row->taxable_amt;
			$total_cgst_amt=$total_cgst_amt+$row->cgst_amt;
			$total_sgst_amt=$total_sgst_amt+$row->sgst_amt;
			$total_igst_amt=$total_igst_amt+$row->igst_amt;
			$no_of_rows=$no_of_rows+1;			
			$total_qnty=$total_qnty+$row->qnty;

		   //$total_body_row_printed=$total_body_row_printed+1;
			//$tot_cnt=$tot_cnt-1;
			$product_srl=$product_srl+1;
			$total_printed=$total_printed-1; //decrease by each print		
		?>
				<tr>
						<td class="style12 col"><?php echo $product_srl; ?></td>
						<td  class="style12" >| </td>
						<td class="style12 col"><?php echo $hsncode; ?></td>
						<td  class="style12" >| </td>
						<td class="style12 col"> <?php echo $product_name; ?> </td>
						<td  class="style12" >| </td>
						<td class="style12"> <?php echo $row->batchno; ?> </td>
						<td  class="style12" >| </td>			
						<td class="style12"> <?php echo $row->exp_monyr;?> </td>
						<td  class="style12" >| </td>
						<td class="style12" align="right"> <?php echo $row->mrp; ?> </td>
						<td  class="style12" >| </td>
						<td align="right" class="style12"> <?php echo $row->qnty; ?> </td>
						<td  class="style12" >| </td>
						<td class="style12" align="right"> <?php echo $row->srate; ?> </td>
						<td  class="style12" >| </td>
						<td class="style12" align="right"><?php	echo $row->disc_per.'+'.$row->disc_per2; ?></td>
						<td  class="style12" >| </td>
						<td class="style12" align="right"> <?php	echo $row->taxable_amt; ?> </td>
						<td  class="style12" >| </td>
						<?PHP if($gsttype=='sgst_cgst' ){ ?>
						<td class="style12" align="right"> <?php echo $row->cgst_rate; ?> </td>
						<td  class="style12" >| </td>		    
						<td class="style12" align="right"> <?php echo $row->sgst_rate; ?> </td>
						<td  class="style12" >| </td>	
						<?PHP }else{ ?>
						<td class="style12" align="right"> <?php echo $row->igst_rate; ?> </td>
						<td  class="style12" >| </td>
						<?PHP } ?>				
						<td class="style12" align="right"> <?php	echo $row->subtotal; ?>  
						<td width="27%"  align="right" class="style12">|</td>
					</tr>
				
				<?php } ?>
		<!-- BLANK PRINT AT BODY -->
		
		<?php   for($rc=1;$rc<=$no_of_das_print;$rc++){ ?>
		
			<tr>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<!--<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>-->
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<?PHP if($gsttype=='sgst_cgst' ){ ?>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>    
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<?PHP }else{ ?>
				<td class="style12 col">&nbsp;</td><td  class="style12" >| </td>
				<?PHP } ?>
				<td class="style12 col">&nbsp;</td>
				<td width="27%"  align="right" class="style12">|</td>
		  </tr>
	 
	 	<?php } ?>
		
		<?PHP if($gsttype=='sgst_cgst' ){ ?>
			 <tr   bgcolor="#CCCCCC">
			 <td height="26" colspan="<?php echo $gst_colspan; ?>" class="style12" >
			 <strong><?PHP echo '1. No of Row(s):'.$no_of_rows.' | '.' Total Qnty '.$total_qnty; ?><strong>	 </td>
			 </tr>   
			 <?PHP }else{ ?>  
			 <tr   bgcolor="#CCCCCC"><td class="style12" colspan="<?php echo $gst_colspan; ?>" >
			 <strong><?PHP echo '1. No of Row(s):'.$no_of_rows.' | '.' Total Qnty '.$total_qnty; 
			 //echo '$total_printed: '.$total_printed;echo ' $limit_start: '.$limit_start;echo ' $limit_end: '.$limit_end;  
			 ?><strong>
			 </td>
			 </tr>	 
	 	<?PHP } ?>
		
	 	<!-- BODY SECTION end -->
		
		<!-- FOOTER SECTION -->
		<?php if($total_printed==0){?>
		
				<!--FOOTER START-->
				<tr>
				<td  colspan="<?php echo $gst_colspan; ?>">				
					<table   style="width:100%">
						<tr>		
						<td width="56%">		
						<table  width="100%" style="margin-top:15px;" >				 
							<tr>
								<td   style="background:#CCCCCC"> TERMS AND CONDITION OF SALE </td>
							</tr>
							<tr>
							<td align="left" >
								<div align="justify">
									<p class="style12">
									1. Goods once sold will not be taken or exchange.<br>
									2.All disputs subject to kolkata jurisdiction only. <br>
									3.Bank charges will be charge extra in case of any cheque bounce.  <br>
									Certified that the particulars given above are true and correct<br>
									and the amount indicated represents the price actually charged. <br>	        
									Interest will be charged @10% if the payment is not made within due date 	        
								<br>
								</div></td>
							</tr>
							
							</table>
							
						</td>
						
						<td>
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
							<td width="55%" class="style14" > Total Trade Price  </td>
							<td width="45%" align="right" class="style14"> <?php echo sprintf('%0.2f',$total_amt); ?> </td>
							</tr>
							<?php if($total_disc_amt>0){ ?>
							<tr>
							<td class="style14"> Total  Discount </td>
							<td align="right" class="style14"> 
								<?php echo sprintf('%0.2f', $total_disc_amt); ?>
								</td>
							</tr>
							<?php } ?>
							
							<tr>
							<td class="style14"> Total  Taxable Amt </td>
							<td align="right" class="style14"> 
								<?php echo sprintf('%0.2f', $total_taxable_amt); ?>
								</td>
							</tr>
							
							<?PHP if($gsttype=='sgst_cgst' ){ ?>
							<tr>
							<td class="style14"> Add CGST  </td>
							<td align="right" class="style14"> <?php echo sprintf('%0.2f',$total_cgst_amt); ?> </td>
							</tr>
							<tr>
							<td class="style14"> Add SGST  </td>
							<td align="right" class="style14"> <?php echo sprintf('%0.2f',$total_sgst_amt); ?> </td>
							</tr>
							<?PHP }else{ ?>
							<tr>
							<td class="style14"> Add IGST </td>
							<td align="right" class="style14"> <?php echo sprintf('%0.2f',$total_igst_amt);?> </td>
							</tr>
							<?PHP } ?>
							
						
							
							<tr>
							<td class="style14"> Add/Less : Round Off </td>
							<td align="right" class="style14"> <?php echo sprintf('%0.2f',$rndoff); ?> </td>
							</tr>
							
							<tr>
							<td class="style14"> Net Payable Amount </td>
							<td align="right" class="style14"> <?php echo sprintf("%01.2f",$grandtot);?> </td>
							</tr>
						</table>
						</td>
						
						</tr>
						<tr><td colspan="2">
						[ Rs in Words :<?php echo $this->numbertowords->convert_digit_to_words($grandtot).' Only';?> ] 
						</td>
						</tr>
						
						<tr><td colspan="2"><?php echo $comment;?> </td></tr>
						
						
						<tr>
						<td colspan="2">
						
						<table width="100%" border="0">
						<tr>
							<td align="left">Checked By</td>
							<td align="left">Packed By</td>
							<td align="center" >
								<p class="style12">For&nbsp;&nbsp;<strong><?php echo $company_records[0]->NAME;?><br>
										
								<br>
								Authorised Signatory</strong></p></td>
							</tr>
							
							<tr>
							<td align="left" class="style14">Remarks</td>
							<td align="left" colspan="3" class="style14"><?php echo $comment;?></td>
							</tr>
							
							<tr>
							<td align="left" class="style14">Bank Details</td>
							<td align="left" colspan="3" class="style14"><?php echo $company_records[0]->BankDetails;?></td>
							</tr>
							
							<tr>
							<td align="center" colspan="3" >
								<p class="style14"><img src="<?php echo $billimg; ?>"/></p></td>
							</tr>
							
						</table>
						
						</td>
						</tr>
					
					</table>					
				</td>
				</tr>
				<!--FOOTER END-->
		
		<?php } ?>
		<!-- FOOTER SECTION -->
		
		</table>
				
	<!-- main Loop (while) -->				
	<?php $limit_start=$limit_start+$limit_end;} ?>


