<link href="https://fonts.googleapis.com/css?family=PT+Serif&display=swap" rel="stylesheet">
<style type="text/css">

#invoice-POS{
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:1mm;
  margin: 5px auto;
  width: 4in;
  background: #FFF;
  /*font-family: 'PT Serif', serif;*/
  font-size:12px;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
	position:absolute;
	top:10px;
	left:0px;
  //float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
	background-size: 60px 60px;
}
.clientlogo{
  float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
	background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  /*//padding: 5px 0 5px 15px;
  //border: 1px solid #EEE*/
}
.tabletitle{
  //padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

  
  
}
</style>


<style type="text/css">
.stamp {
	position:absolute;
	top:150px;
	right:150px;
  transform: rotate(12deg);
	color: #555;
	font-size: 3rem;
	font-weight: 300;
	border: 0.25rem solid #555;
	display: inline-block;
	padding: 0.25rem 1rem;
	text-transform: uppercase;
	border-radius: 1rem;
	font-family: 'Courier';
	-webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
  -webkit-mask-size: 944px 604px;
  mix-blend-mode: multiply;
}

.is-nope {
  color: #D23;
  border: 0.5rem double #D23;
  transform: rotate(45deg);
	-webkit-mask-position: 2rem 3rem;
  font-size: 2rem;  
}

.is-approved {
	color: #0A9928;
	border: 0.5rem solid #0A9928;
	-webkit-mask-position: 13rem 6rem;
	transform: rotate(-14deg);
  border-radius: 0;
} 

.is-draft {
	color: #C4C4C4;
	border: 1rem double #C4C4C4;
	transform: rotate(-5deg);
  font-size: 6rem;
  font-family: "Open sans", Helvetica, Arial, sans-serif;
  border-radius: 0;
  padding: 0.5rem;
} 
</style>

<?php
		
		$image_path=BASE_PATH_ADMIN.'uploads/'.'logo_label.jpg';
		$billimg='BILL-'.$table_id;
		$billtype='UNPAID';
		$billimg=BASE_PATH_ADMIN.'uploads/'.$billimg.'.png'; 		
		$challan_no=$challan_date='';	
		$grand_total=0;	
		
		if($table_id<>0)
		{
			$sql2="select * from invoice_summary where id=".$table_id." ";
			$invoice_summary = $this->projectmodel->get_records_from_sql($sql2);	
		}
		
		if($invoice_summary[0]->status=='SALE')
		{
			if($invoice_summary[0]->BILL_TYPE=='CASH_BILL'){$billtype='CASH BILL';}
			else
			{$billtype='CREDIT BILL';}
		}
		
		if($invoice_summary[0]->status=='SALE_RTN')
		{
			if($invoice_summary[0]->BILL_TYPE=='CASH_BILL'){$billtype='SALE RETURN IN CASH';}
			else
			{$billtype='SALE RETURN CREDITED';}
		}
		
		
		$company_id=$this->session->userdata('COMP_ID');
		$company_records="select * from company_details where id=".$company_id;					
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	
		
		if($invoice_summary[0]->tbl_party_id<>124)
		{
			$tbl_party_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',' id='.$invoice_summary[0]->tbl_party_id);			
			$party_records="select * from tbl_party where id=".$tbl_party_id." ";
			$party_records = $this->projectmodel->get_records_from_sql($party_records);	
		}
		
		
		
?>


<div id="invoice-POS">

	
    
    <?php /*?><center id="top">
      <div class="logo"></div>
      <div class="info">
	  <span style="font-size:16px; position:absolute; top:10px; left:400px;"><?php echo '<strong>'.$company_records[0]->NAME.'</strong>';?></span>
	  <img src="<?php echo $image_path; ?>" width="50" height="50"/>
	  </div><!--End Info-->
    </center><!--End InvoiceTop--><?php */?>
	
	<div id="mid">
      <div class="info" >                 
          <img src="<?php echo $image_path; ?>" width="50" height="50"/>
		  <span style="font-size:22px; position:absolute; top:20px; left:90px;"><?php echo '<strong>'.$company_records[0]->NAME.'</strong>';?></span>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="mid">
      <div class="info" >                 
           Add : <?php echo $company_records[0]->ADDRESS;?></br>
           Email : <?php echo $company_records[0]->company_mailid;?></br>
           Ph : <?php echo $company_records[0]->PhoneNo;?></br>
		   DL NO:<?php echo $company_records[0]->DLNO1;?><br>
		   GST NO:<?php echo $company_records[0]->GSTNo;?><br>
      </div>
    </div><!--End Invoice Mid-->
	
	<div id="mid" style="background:#999999" align="center"><strong>Bill Details</strong></div>
	<div id="mid" >
	<!--stamp section-->
	<?php /*?><span class="stamp is-nope"><?php echo $billtype;?></span><?php */?>
      <div class="info" > 
           Bill No   :<?php echo $invoice_summary[0]->invoice_no; ?></br>
		   Bill Type   :<?php echo '<strong>'.$billtype.'</strong>'; ?></br>
		   Date/Time :<?php echo $invoice_summary[0]->invoice_date.'/'.$invoice_summary[0]->invoice_time; ?></br>
           Bill By   :<?php echo $invoice_summary[0]->emp_name; ?></br> 
      </div>
    </div><!--End Invoice Mid-->
	
	<div id="mid" style="background:#999999" align="center"><strong>Party Details</strong></div>
	<!--End Invoice Mid-->
	
	<?php if($tbl_party_id<>124){ ?>
	<div id="mid" >
      <div class="info" > 
           Name :<?php echo $party_records[0]->party_name; ?></br>
           <?php echo $party_records[0]->address.'|'.$party_records[0]->city.'|'.$party_records[0]->pin;?></br>
           GST No : <?php echo $party_records[0]->GSTNo; ?></br>
      </div>
    </div><!--End Invoice Mid-->
	<?php }else{ ?>	
	<div id="mid" >
      <div class="info" > 
           Party :Cash
      </div>
    </div><!--End Invoice Mid-->	
	<?php }?>
	
	
    
    <div id="bot">

		<div id="table">					
			<table style="width:100%" align="center">
				<tr  >
					<td class="item" style="background-color:#999999"> Srl </td>
					<td class="item" colspan="5" style="background-color:#999999"> Item </td>
					<td class="Rate" align="right"> &nbsp;&nbsp;&nbsp; </td>
				</tr>
				<tr  >
					<td class="item" style="background-color:#999999"> Qty </td>
					<td class="Hours" align="right" style="background-color:#999999"> MRP </td>
					<td class="Hours" align="right" style="background-color:#999999"> Rate </td>
					<td class="Rate" align="right" style="background-color:#999999"> Dis% </td>
					<td class="Rate" align="right" style="background-color:#999999"> Tax% </td>
					<td class="Rate" align="right" style="background-color:#999999"> Total </td>
					<td class="Rate" align="right"> &nbsp;&nbsp;&nbsp; </td>
				</tr>
							
			<?php
				$total_amt=0;
				$total_disc_amt=0;
				$total_taxable_amt=0;
				$total_cgst_amt=0;
				$total_sgst_amt=0;
				$total_qnty=0;
				//$grand_total=0;	
													
				$sql="select * from invoice_details where 
				 PRODUCT_TYPE in ('FINISH','MIXTURE') and invoice_summary_id=".$table_id." 	order by  id ";
				$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
				$i =0;
				if(count($rowrecord) > 0){
				foreach ($rowrecord as $row){ 
				$alt = ($i%2==0)?'alt1':'alt2';
				$i =$i +1;
				$stotal=0;		
				$tax_per=$row->tax_per;		
				
				/*$product_name=$this->projectmodel->GetSingleVal('productname','productmstr',
								' id='.$row->product_id);*/
								
				$product_name=$row->product_name;
				$total_amt=$total_amt+$row->subtotal;
				$total_disc_amt=$total_disc_amt+$row->disc_amt;
				$total_taxable_amt=+$total_taxable_amt+$row->taxable_amt;
				$total_cgst_amt=$total_cgst_amt+$row->cgst_amt;
				$total_sgst_amt=$total_sgst_amt+$row->sgst_amt;
				$total_qnty=$total_qnty+$row->qnty;
				//$grand_total=$grand_total+$row->taxable_amt+$row->cgst_amt+$row->sgst_amt;	
			?>		
					<tr class="service">
					<td class="tableitem"><?PHP echo $i; ?></td>
					<td class="tableitem" colspan="6"><?PHP echo $product_name; ?></td>
					</tr>							
					<tr class="service">
						<td class="tableitem"><?PHP echo $row->qnty; ?></td>
						<td class="tableitem" align="right"><?PHP echo $row->mrp; ?></td>
						<td class="tableitem" align="right"><?PHP echo $row->srate; ?></td>
						<td class="tableitem" align="right"><?PHP echo $row->disc_per; ?></td>
						<td class="tableitem" align="right"><?PHP echo $row->tax_per; ?></td>
						<td class="tableitem" align="right"><?PHP echo $row->subtotal; ?></td>
						<td class="Rate" align="right"> &nbsp;&nbsp;&nbsp; </td>
					</tr>
			<?PHP }} ?>											
							
							<tr style="background-color:#999999"><td class="tableitem" colspan="6">
							<?PHP echo 'Total No of Products :'.$total_qnty; ?>
							</td></tr>
							
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2">Total</td>
								<td class="tableitem" colspan="2" align="right"><?PHP echo $total_amt; ?></td>
								<td class="Rate" align="right">&nbsp;&nbsp; </td>
							</tr>
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2">Discount</td>
								<td class="tableitem" colspan="2" align="right"><?PHP echo $total_disc_amt; ?></td>
								<td class="Rate" align="right">&nbsp;&nbsp; </td>
							</tr>
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2">Taxable Amt</td>
								<td class="tableitem" colspan="2" align="right"><?PHP echo $total_taxable_amt; ?></td>
								<td class="Rate" align="right">&nbsp;&nbsp; </td>
							</tr>
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2">CGST Amt</td>
								<td class="tableitem" colspan="2" align="right"><?PHP echo $total_cgst_amt; ?></td>
								<td class="Rate" align="right">&nbsp;&nbsp; </td>
							</tr>
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2">SGST Amt</td>
								<td class="tableitem" colspan="2" align="right"><?PHP echo $total_sgst_amt; ?></td>
								<td class="Rate" align="right">&nbsp;&nbsp; </td>
							</tr>
							
							<tr class="service">
								<td class="tableitem" colspan="2">&nbsp;</td>
								<td class="tableitem" colspan="2">Grand Total</td>
								<td class="tableitem" colspan="2" align="right"><?PHP echo $invoice_summary[0]->grandtot; ?></td>
								<td class="Rate" align="right">&nbsp;&nbsp; </td>
							</tr>
							
							
							
							<tr class="service">
								<td class="tableitem" colspan="6">
								[ Rs in Words :<?php echo 
								'<strong>'.strtoupper($this->numbertowords->convert_digit_to_words(round($invoice_summary[0]->grandtot)).' Only').'</strong>';?> ]
								</td>
								<td class="tableitem">&nbsp;</td>
							</tr>
							
							<?php if($invoice_summary[0]->status=='SALE'){?>						
							<tr class="service">
								<td class="tableitem" colspan="6">
								[ Your Savings on this invoice Rs.<?php echo '<strong>'.
								strtoupper($this->numbertowords->convert_digit_to_words(round($total_disc_amt))).'</strong>';?> ]
								</td>
								<td >&nbsp;</td>
							</tr>
							<?php }?>
							
							<tr class="service">
								<td class="tableitem" colspan="6" align="center">
								<img src="<?php echo $billimg; ?>"/>
								</td>
								<td class="tableitem">&nbsp;&nbsp;</td>
							</tr>
							

						</table>
					</div><!--End Table-->
					
								
				<?php if($invoice_summary[0]->status=='SALE'){?>
					<div id="legalcopy" style="margin-right:10px;">
						<p align="justify" class="legal"><?php echo '<strong>Thank you for your business!</strong>'; ?>
						 Payment is expected within 31 days; please process this invoice within that time. There will be a 5% 
						 interest charge per month on late invoices.</p>
					</div>
				<?php }?>	

  </div><!--End InvoiceBot-->
</div><!--End Invoice-->
