<?php
		$cstpercent=2.00;
		$tot_discount_cash=0;
		$sql="select * from invoice_summary where id=".$table_id." ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1){ 
		$invoice_no=$row1->invoice_no; 
		$invoice_date=$row1->invoice_date; 
		$orderno=$row1->orderno;
		$orderdate=$row1->orderdate;
		$trno=$row1->trno;
		$trdate=$row1->trdate;
		$challan_no=$row1->challan_no;
		$challan_date=$row1->challan_date;
		
		$destination=$row1->destination;
		$transporter=$row1->transporter;
		$lrno=$row1->lrno;
		$lrdate=$row1->lrdate;
		$no_of_case=$row1->no_of_case;
		$due_payment_date=$row1->due_payment_date;
			
		$tbl_party_id=$row1->tbl_party_id; 		
		$total_paid=$row1->total_paid; 	
			
		$total_amt=$row1->total_amt;
		$disc_per=$row1->disc_per;
		$cash_disc=$row1->cash_disc;
		
		$tot_discount=$row1->tot_discount;
		$tot_cash_discount=$row1->tot_cash_discount;
		$totvatamt=$row1->totvatamt;
		$rnd=$row1->rndoff;
		$grandtot=$row1->grandtot;
		$hq_id=$row1->hq_id;
		$comment=$row1->comment;
		
		$credit_note1=$row1->rtn_invoice_summary_id1;
		$credit_note2=$row1->rtn_invoice_summary_id2;
		$credit_note3=$row1->rtn_invoice_summary_id3;
		$credit_note4=$row1->rtn_invoice_summary_id4;
		
		$bank_charge_chqno=$row1->bank_charge_chqno;
		$bank_charge_chqdate=$row1->bank_charge_chqdate;
		$bank_charge_chqamt=$row1->bank_charge_chqamt;
		$bank_charge_fine=$row1->bank_charge_fine;
		$freight_charge=$row1->freight_charge;
		$interest_charge=$row1->interest_charge;
		$BILL_TYPE=$row1->BILL_TYPE;	
		$inventory_charge=$row1->inventory_charge;	
		$inventory_charge_gst_rate=$row1->inventory_charge_gst_rate;
			
		}
		$SNAME_CODE='';
		$sql="select * from stockist where id=".$tbl_party_id." ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1){ 
		$party_name=$row1->retail_name; 
		$address=$row1->retail_address.'<BR>'; 
		$retail_code=$row1->retail_code; 
		$DLNO=$row1->DLNO; 
		$VATNO=$row1->VATNO; 
		$CSTNO=$row1->CSTNO; 
		$PANNO=$row1->PANNO; 
		$BILL_TYPE=$row1->BILL_TYPE; 
		$CREDIT_DAYS=$row1->CREDIT_DAYS;
		$SNAME_CODE=$row1->STATE_NAME.' | CODE:'.$row1->STATE_CODE;
		$GSTNO=$row1->GSTNO;
		
		//$retail_code='DL NO.## & ##'; 
		}
		
		$image_path=BASE_PATH_ADMIN.'uploads/invoice_logo.png';
		
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> 

<style type="text/css">

.style12 {
	font-size: 10px;
	font-family: 'Montserrat', sans-serif;
	/*font-weight: bold;*/
}
.style13 {
	font-size: 12px;
	font-family: 'Montserrat', sans-serif;
	/*font-weight: bold;*/
}
.style14 {font-size: 14px;
	font-family: 'Montserrat', sans-serif;
	font-weight: bold;}
	
.style16 {font-size: 14px;
	font-family: sans-serif;
	}

    </style>
</head>

<body>

<!--HEADER START-->

<table  style="width:100%">

	<tr><td  colspan="2" align="center">
	<span class="style12">
	<?php
			
		$lrno='';
		$sql="select * from invoice_summary where id=".$table_id."  ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row){ $lrno=$row->lrno; }
		
		if($lrno<>'') 
		{ echo 'TAX INVOICE';}
		else { echo 'TAX INVOICE'; }
		
		
		
	?>
	</span>
	
	</td>
	</tr>
	
	<tr>
	<td width="56%"  >
	<table>
	<tr>
	<td><img src="<?php echo $image_path; ?>" /></td>
	<td>
	
	<strong>UNITED LABORATORIES(INDIA) PVT.LTD </strong><BR>
	Office: Aspirations Vintage Building<BR>
	12, Pretoria Street, Unit No- 1C, 1st Floor,Kol-71<BR>
	Factory: 29,Manna para Road Kolkata- 700 090<BR>
	State : West Bengal | Code:019<BR>
	</td>
	</tr>
	</table>
	</td>
	
	<td width="44%"><span class="style16">
	<table width="97%" border="0">
	  <tr>
		<td width="49%" >DL NO</td>
		<td width="51%" >3376-SW/2465 -SBW</td>
	  </tr>
	  <tr>
		<td>GSTIN NO</td>
		
		<td>19AAACU3899C1Z2</td>
	  </tr>
	  <tr>
		<td>Office Phone</td>
		<td>033 2280 0233</td>
	  </tr>
	  <tr>
		<td>Factory Phone</td>
		<td>033 2531 4345/7222</td>
	  </tr>
	</table>
	</span>
	</td>
	
	</tr>
	
   <tr><td colspan="2">
  
  <table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
  <td width="60%" height="22" style="background:#CCCCCC">
  <span class="style13">Bill To</span></td>
  <!--<td width="32%" style="background:#CCCCCC"><span class="style13">Shipped To</span></td>-->
  <td width="40%" style="background:#CCCCCC"><span class="style13">Invoice Details</span></td>
  </tr>
  <tr>
      
<td width="60%">
		<span class="style13">M/s.&nbsp;&nbsp;<?php echo $party_name; ?></strong><BR>
		<?php echo $address; ?><BR>
		<?php if($DLNO<>''){ echo  'DL No :'.$DLNO.'<br>';} ?>
		<?php if($GSTNO<>''){ echo 'GST No :'.$GSTNO.'<br>';} ?>
		<?php if($PANNO<>''){ echo 'PAN No :'.$PANNO.'<br>';} ?>
		<?php if($SNAME_CODE<>''){ echo 'STATE :'.$SNAME_CODE.'<br>';} ?>
		
	</span></td>
 


<td width="40%" >
    <table width="100%"  cellpadding="0" cellspacing="0">
  <tr>
    <td width="29%"><span class="style12">Invoice No</span></td>
    <td width="35%"><span class="style12"><?php echo $invoice_no; ?></span></td>
	<td width="11%"><span class="style12">Date</span></td>
	<td width="25%"><span class="style12"><?php echo $invoice_date; ?></span></td>
  </tr>
   <tr>
    <td width="29%"><span class="style12">Challan No</span></td>
    <td width="35%"><span class="style12"><?php echo $challan_no; ?></span></td>
	<td width="11%"><span class="style12">Date</span></td>
	<td width="25%"><span class="style12"><?php echo $challan_date; ?></span></td>
  </tr>
    <tr>
    <td width="29%"><span class="style12">Order No</span></td>
    <td width="35%"><span class="style12"><?php echo $orderno; ?></span></td>
	<td width="11%"><span class="style12">Date</span></td>
	<td width="25%"><span class="style12"><?php echo $orderdate; ?></span></td>
  </tr>
  
   <tr>
    <td width="29%"><span class="style12">LR No</span></td>
    <td width="35%"><span class="style12"><?php echo $lrno; ?></span></td>
	<td width="11%"><span class="style12">Date</span></td>
	<td width="25%"><span class="style12"><?php echo $lrdate; ?></span></td>
  </tr>
</table></td>
  </tr>
</table>

   </td></tr>

</table>




<!--BODY START-->
<table  style="width:100%">
<tr><td  colspan="2" align="center">= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =</td>
</tr>

<?php
	$totalmrp=0;
	$totalamt=0;
	$totalvat=0;
	$totalqnty=0;
	$totaledprice=0;	
	$excise_duty_percentage=0;
	$totalcgst=0;
	$totalsgst=0;
	$totaligst=0;	
	
	$tot_cnt=0;
	$sql="select count(*) cnt from invoice_details where invoice_summary_id=".$table_id."   ";
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row){$tot_cnt=$row->cnt;}		
	$limit_start=0;
	$limit_end=12;	
	$loop_max=ceil($tot_cnt/12);
	for($lp=1;$lp<=$loop_max;$lp++)
	{
	
?>

<?php if($lp>1){ ?>
<tr><td  colspan="2" >

<table  style="width:100%">

	<tr><td  colspan="2" align="center">
	<span class="style12">
	<?php
			
		$lrno='';
		$sql="select * from invoice_summary where id=".$table_id."  ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row){ $lrno=$row->lrno; }
		
		if($lrno<>'') 
		{ echo 'TAX INVOICE';}
		else { echo 'TAX INVOICE'; }
		
		
		
	?>
	</span>
	
	</td>
	</tr>
	
	<tr>
	<td width="56%"  >
	<table>
	<tr>
	<td><img src="<?php echo $image_path; ?>" /></td>
	<td>
	
	<strong>UNITED LABORATORIES(INDIA) PVT.LTD </strong><BR>
	Office: Aspirations Vintage Building<BR>
	12, Pretoria Street, Unit No- 1C, 1st Floor,Kol-71<BR>
	Factory: 29,Manna para Road Kolkata- 700 090<BR>
	State : West Bengal | Code:019<BR>
	</td>
	</tr>
	</table>
	</td>
	
	<td width="44%"><span class="style16">
	<table width="97%" border="0">
	  <tr>
		<td width="49%" >DL NO</td>
		<td width="51%" >3376-SW/2465 -SBW</td>
	  </tr>
	  <tr>
		<td>GSTIN NO</td>
		
		<td>19AAACU3899C1Z2</td>
	  </tr>
	  <tr>
		<td>Office Phone</td>
		<td>033 2280 0233</td>
	  </tr>
	  <tr>
		<td>Factory Phone</td>
		<td>033 2531 4345/7222</td>
	  </tr>
	</table>
	</span>
	</td>
	
	</tr>
	
    <tr><td colspan="2">
  
 	 <table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
  <td width="60%" height="22" style="background:#CCCCCC">
  <span class="style13">Bill To</span></td>
  <!--<td width="32%" style="background:#CCCCCC"><span class="style13">Shipped To</span></td>-->
  <td width="40%" style="background:#CCCCCC"><span class="style13">Invoice Details</span></td>
  </tr>
  <tr>
      
<td width="60%">
		<span class="style13">M/s.&nbsp;&nbsp;<?php echo $party_name; ?></strong><BR>
		<?php echo $address; ?><BR>
		<?php if($DLNO<>''){ echo  'DL No :'.$DLNO.'<br>';} ?>
		<?php if($GSTNO<>''){ echo 'GST No :'.$GSTNO.'<br>';} ?>
		<?php if($PANNO<>''){ echo 'PAN No :'.$PANNO.'<br>';} ?>
		<?php if($SNAME_CODE<>''){ echo 'STATE :'.$SNAME_CODE.'<br>';} ?>
		
	</span></td>
 


<td width="40%" >
    <table width="100%"  cellpadding="0" cellspacing="0">
  <tr>
    <td width="29%"><span class="style12">Invoice No</span></td>
    <td width="35%"><span class="style12"><?php echo $invoice_no; ?></span></td>
	<td width="11%"><span class="style12">Date</span></td>
	<td width="25%"><span class="style12"><?php echo $invoice_date; ?></span></td>
  </tr>
   <tr>
    <td width="29%"><span class="style12">Challan No</span></td>
    <td width="35%"><span class="style12"><?php echo $challan_no; ?></span></td>
	<td width="11%"><span class="style12">Date</span></td>
	<td width="25%"><span class="style12"><?php echo $challan_date; ?></span></td>
  </tr>
    <tr>
    <td width="29%"><span class="style12">Order No</span></td>
    <td width="35%"><span class="style12"><?php echo $orderno; ?></span></td>
	<td width="11%"><span class="style12">Date</span></td>
	<td width="25%"><span class="style12"><?php echo $orderdate; ?></span></td>
  </tr>
  
   <tr>
    <td width="29%"><span class="style12">LR No</span></td>
    <td width="35%"><span class="style12"><?php echo $lrno; ?></span></td>
	<td width="11%"><span class="style12">Date</span></td>
	<td width="25%"><span class="style12"><?php echo $lrdate; ?></span></td>
  </tr>
</table></td>
  </tr>
</table>

   </td></tr>

</table>

</td></tr>
<?php } ?>

<tr>
<td  colspan="2" >
<table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr style="background:#CCCCCC">
    <td  colspan="7"><span class="style12">Product <?php //echo $loop_max.'s:'.$limit_start.' e'.$limit_end; ?></span></td>
	<td width="10%"  ><span class="style12">HSN Code</span></td>
	<td width="4%" ><span class="style12">Batch</span></td>
	<td width="4%"  ><span class="style12">MFG</span></td>
	<td width="4%" ><span class="style12">EXP</span></td>
	<td width="3%" ><span class="style12">Qty </span></td>
	<td width="4%" ><span class="style12">Free</span></td>
	<!--<td width="5%" align="right"><span class="style12">Repl Qty</span></td>-->
	<td width="4%" ><span class="style12">Tot Qnty</span></td>
	<td width="5%" ><span class="style12">MRP</span></td>
	<td width="4%" align="right"><span class="style12">PTR</span></td>
	
	<td width="3%" ><span class="style12">PTS</span></td>
	<!--<td width="3%" ><span class="style12">Disc%</span></td>-->
	<td width="3%" ><span class="style12">Taxable Rate</span></td>
	<td width="7%" ><span class="style12">Taxable Amt</span></td>
    <td width="22%" align="right">
	<span class="style12">
	<table  border="0" style="width:100%">
	
  <tr>
  <?PHP if($BILL_TYPE=='VATBILL'){ ?>
    <td colspan="2">CGST</td>
    <td colspan="2">SGST</td>
	<?php }else{ ?>
    <td colspan="2">IGST</td>
	<?php } ?>
  </tr>
  
  <tr>
  <?PHP if($BILL_TYPE=='VATBILL'){ ?>
    <td>Rate</td>
    <td>Amt</td>
   <td>Rate</td>
    <td>Amt</td>
	<?php }else{ ?>
    <td>Rate</td>
    <td>Amt</td>
	<?php } ?>
  </tr>
</table></span>	</td>
  </tr>

  
  <?php
	
	// LIMIT 13 , 16	
	
	
	 $sql="select * from invoice_details where invoice_summary_id=".$table_id."  LIMIT ".$limit_start." , ".$limit_end." ";
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	$i = 1;
	if(count($rowrecord) > 0){
	foreach ($rowrecord as $row){ 
	$alt = ($i%2==0)?'alt1':'alt2';
	$stotal=0;		
	$gst_per=$row->vat_per;		
?>
			
  <tr>
   		<td colspan="7"><span class="style12">
		<?php
		$mrp_incl_tax=0;
		$sqlprd="select * from brands where	id=".$row->product_id." ";
		$rowsqlprd = $this->projectmodel->get_records_from_sql($sqlprd);	
		foreach ($rowsqlprd as $rowprd){ 
		$product_name=$rowprd->brand_name;	
		$excise_percentage=$rowprd->excise_percentage;	
		$mrp_incl_tax=$rowprd->mrp_incl_tax;	
		$hsncode=$rowprd->hsncode;
		}
		echo $product_name; ?></span>		</td>
		<td><span class="style12"><?php echo $hsncode; ?></span></td>
		<td><span class="style12"><?php echo $row->batchno; ?></span></td>
		<td ><span class="style12"><?php echo $row->mfg_monyr;?>
		</span></td>
		<td><span class="style12"><?php echo $row->exp_monyr;?></span></td>
		
		<td>
		<span class="style12">
		<?php 
		$totalqnty=$row->qnty+$row->freeqty;  
		$totaledprice=$totaledprice+ ($row->ed*$row->qnty);
		//$totalmrp=$totalmrp + ($row->mrp*($row->qnty+$row->freeqty));
		echo $row->qnty; 		
		?></span></td>
	    <td><span class="style12"><?php echo $row->freeqty; ?></span></td>
		<?php /*?><td><span class="style12"><?php echo $row->replace_qnty; ?>
		</span></td><?php */?>
		<td><span class="style12"><?php echo $totalqnty; ?></span></td>
		<td align="right"><span class="style12"><?php echo $row->mrp; ?></span></td>
		<td align="right"><span class="style12"><?php echo $row->ptr; ?></span></td>
		<td><span class="style12"><?php echo $row->srate; ?></span></td>
		<?php /*?><td><span class="style12"><?php echo $row->disc_per; ?></span></td><?php */?>
		<td><span class="style12"><?php echo $row->rate; ?></span></td>
		<td><span class="style12"><?php	echo $row->taxable_amt; ?></span></td>
				    
		<td  align="right"><span class="style12" >
		<table  border="0" style="width:100%">
		  <tr>
			
			
			<?PHP if($BILL_TYPE=='VATBILL'){ ?>
			<td><?PHP echo $row->cgst_rate; ?></td>
			<td><?PHP 
			$totalcgst=$totalcgst+$row->cgst_amt;
			echo $row->cgst_amt; ?></td>
		    <td><?PHP echo $row->sgst_rate; ?></td>
			<td><?PHP 
			$totalsgst=$totalsgst+$row->sgst_amt;
			echo $row->sgst_amt; ?></td>
			
			<?PHP }else{ ?>
			<td><?PHP echo $row->igst_rate; ?></td>
			<td><?PHP 
			$totaligst=$totaligst+$row->igst_amt;
			echo $row->igst_amt; ?></td>
			<?PHP }?>
		  </tr>
		</table>		</td>
  </tr>
  
  <?php }}?>
  
  
</table>
</td>
</tr>

<?php if($loop_max>$lp){ ?>
<tr> <td  colspan="2" ><?php $gageno=$lp+1; echo 'Continue page No :'.$gageno; ?></td></tr>
<?php } ?>

<?php
	$tot_cnt=$tot_cnt-12;
  	$limit_start=$limit_end;  
  
  	if($tot_cnt>12)
	{$limit_end=$limit_start+12;}
	else
	{$limit_end=$limit_start+$tot_cnt-1;}
	
  } ?>

</table>






<!--FOOTER START-->
<table  style="width:100%">
<tr>

<td width="56%">

 <table  width="100%">
    	<?php /*?>  <tr>
       <td width="36%" ><span class="style12">MR/HQ</span></td>
       <td width="64%"   align="left"><span class="style12"> 
	   	<?php $sql="select a.hierarchy_name,b.name
				from tbl_hierarchy_org a,tbl_employee_mstr b  where 
				a.employee_id=b.id and a.id=".$hq_id ; 
				$rowrecords = $this->projectmodel->get_records_from_sql($sql);	
				foreach ($rowrecords as $rowrecord)
				{echo $name=$rowrecord->hierarchy_name.
				'('.$rowrecord->name.')';  } ?></span></td>
     </tr><?php */?>
	 
	   <tr>
       <td width="18%" ><span class="style12">PAYMENT DUE DATE</span></td>
       <td width="82%"  align="left"><span class="style12">
	   <?php 
if($CREDIT_DAYS<>0)
{ 
if($tot_cash_discount>0){
echo ''.$this->general_library->get_date($invoice_date,10,0,0);
}
else
{
echo ''.$this->general_library->get_date($invoice_date,$CREDIT_DAYS,0,0);
}

} 
?></span></td>
     </tr>
	 <?php /*?> <tr>
       <td ><span class="style12">TRANSPORTER</span></td>
       <td   align="left"><span class="style12">
	   <?php echo $transporter; ?></span></td>
     </tr>
	 
	 <tr>
       <td ><span class="style12">NO OF CASE</span></td>
       <td  align="left"><span class="style12"><?php echo $no_of_case; ?></span></td>
     </tr><?php */?>
	 
	 <tr>
       <td  colspan="2" style="background:#CCCCCC"><span class="style12">TERMS AND CONDITION OF SALE</span></td>
     </tr>
	 <tr>
    <td align="left"  colspan="2">
	    <div align="justify">
	      <p class="style12">
	        1. Goods once sold will not be taken or exchange.<br>
	        2.All disputs subject to kolkata jurisdiction only. <br>
	        3.Bank charges will be charge extra in case of any cheque bounce.  <br>
	        Certified that the particulars given above are true and correct<br>
	        and the amount indicated represents the price actually charged. <br>	        
	      Interest will be charged @10% if the payment is not made within due date 	        
	     <br>
	        <strong>Chapter: 30 | HSN Code: 3004</strong>	
	      
	    </div></td>
	 </tr>
	 
   </table>
   
</td>

<td>
 <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="84%" ><span class="style12">Total Trade Price </span></td>
    <td width="16%" align="right"><span class="style12"><?php echo $total_amt; ?></span></td>
  </tr>
 <?php /*?> <tr>
    <td><span class="style12">Less Trade Discount </span></td>
    <td align="right">
	<span class="style12"><?php echo $tot_discount; ?></span></td>
  </tr><?php */?>
  <?php if($tot_cash_discount>0){ ?>
  <tr>
    <td>
	<span class="style12">
	Less Cash Discount
	</span></td>
    <td align="right"><span class="style12">
	  <?php echo sprintf('%0.2f', $tot_cash_discount); ?>
	  </span></td>
  </tr>
  
   <tr>
    <td><span class="style12">Total After Discount</span></td>
    <td align="right"><span class="style12">
	  <?php echo sprintf('%0.2f', $total_amt-$tot_discount-$tot_cash_discount); ?>
	  </span></td>
  </tr>
  
   <?php } ?>
   
 

<?php 
		    $vat_msg='';
			$total_gst=0;
			$sql_vatper="select distinct(vat_per) vat_per
			from invoice_details where invoice_summary_id=".$table_id."  ";
			$rowsql_vatper = $this->projectmodel->get_records_from_sql($sql_vatper);	
			foreach ($rowsql_vatper as $rows_vatper)
			{ 
				$vat_per=$rows_vatper->vat_per;	
				
				$mrp_free=$mrp_qty=$vatamt=0;	
				$sql_vatamt="select sum(vatamt) vatamt,sum(srate*freeqty) mrp_free,
				sum(srate*qnty) mrp_qty
				from invoice_details where invoice_summary_id=".$table_id." 
				and  vat_per=".$vat_per;
				$rowsql_vatamt = $this->projectmodel->get_records_from_sql($sql_vatamt);	
				foreach ($rowsql_vatamt as $rows_vatamt)
				{
				$vatamt=$rows_vatamt->vatamt;
				$mrp_qty=$rows_vatamt->mrp_qty;
				$mrp_free=$rows_vatamt->mrp_free;
				//$vatamt=($mrp_qty+$mrp_free)*$vat_per/100;
				}
				$total_gst=$total_gst+$vatamt;
				
				$vat_msg=$vat_msg.'GST @'.$vat_per.'% on '.
				$mrp_qty.'+'.$mrp_free.'='.round($vatamt,2).'<br>';
				
			}
		
			//echo $vat_msg;
		
		
?>
  <?PHP if($BILL_TYPE=='VATBILL'){ ?>
  <tr>
    <td ><span class="style12">Add CGST </span></td>
    <td align="right"><span class="style12"><?php echo $totalcgst; ?></span></td>
  </tr>
  <tr>
    <td ><span class="style12">Add SGST </span></td>
    <td align="right"><span class="style12"><?php echo $totalsgst; ?></span></td>
  </tr>
   <?PHP }else{ ?>
  <tr>
    <td ><span class="style12">Add IGST</span></td>
    <td align="right"><span class="style12"><?php echo $totaligst;?></span></td>
  </tr>
  <?PHP } ?>
  
  <?php 
  if($bank_charge_fine>0)
  {
  
  ?>
  <tr>
    <td ><span class="style12">
	<?php echo 'Bank Charges for Cheque No :'.$bank_charge_chqno.
	' Dated :'.$bank_charge_chqdate.' Amt:'.$bank_charge_chqamt;?>
	</span></td>
    <td align="right" >
	<span class="style12"><?php echo $bank_charge_fine;?></span>	</td>
  </tr>
  
 <?php } ?>
 
 <?php  if($inventory_charge>0){?>
 <tr>
    <td><span class="style12">Add : Inventory Charge</span></td>
    <td align="right"><span class="style12"><?php echo $inventory_charge;?></span></td>
  </tr>
  <?PHP if($BILL_TYPE=='VATBILL'){ ?>
  <tr>
    <td><span class="style12">Add : GST Rate on Inventory Charge:<?php echo $inventory_charge_gst_rate; ?></span></td>
    <td align="right"><span class="style12"><?php echo sprintf('%0.2f', ($inventory_charge*$inventory_charge_gst_rate)/100);?></span></td>
  </tr>
  <?php }else { ?>
   <tr>
    <td><span class="style12">Add : IGST Rate on Inventory Charge:<?php echo $inventory_charge_gst_rate; ?></span></td>
    <td align="right"><span class="style12"><?php echo sprintf('%0.2f', ($inventory_charge*$inventory_charge_gst_rate)/100);?></span></td>
  </tr>  
  <?php } ?>
  
 <?php } ?>
  
   
  
  <tr>
    <td><span class="style12">Less : Freight Charge</span></td>
    <td align="right"><span class="style12"><?php echo $freight_charge;?></span></td>
  </tr>
  <tr>
    <td><span class="style12">Add : Interest Charge</span></td>
    <td align="right"><span class="style12"><?php echo $interest_charge;?></span></td>
  </tr>
  <tr>
    <td><span class="style12">Add/Less : Round Off</span></td>
    <td align="right"><span class="style12"><?php echo $rnd ?></span></td>
  </tr>
  
   <tr>
    <td><span class="style12">Net Payable Amount</span></td>
    <td align="right"><span class="style12"><?php echo sprintf("%01.2f",$grandtot);?></span></td>
  </tr>
</table>
</td>

</tr>
<tr><td colspan="2">
<span class="style14">[ Rs in Words :<?php echo $this->numbertowords->convert_digit_to_words($grandtot).' Only';?> ]</span>
</td>
</tr>


<tr>
<td colspan="2">

<table width="100%" border="0">
 <tr>
   <td align="left">Checked By</td>
	<td align="left">Packed By</td>
	 <td align="center" >
	   <p class="style12">For&nbsp;&nbsp;<strong>UNITED LABORATORIES(INDIA) PVT.LTD<br>
              
	     <br>
	     Authorised Signatory</strong></p></td>
  </tr>
  <!--<tr>  <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>-->
</table>

</td>
</tr>



</table>



   
</body>
</html>