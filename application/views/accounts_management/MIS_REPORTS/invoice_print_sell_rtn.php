<?php

		
		$sql="select * from invoice_summary where id=".$table_id." ";
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1){ 
		$invoice_no=$row1->invoice_no; 
		$invoice_date=$row1->invoice_date; 
		$tbl_party_id=$row1->tbl_party_id; 		
		$total_paid=$row1->total_paid; 	
		$TAXTYPE=$row1->TAXTYPE; 		
		$TAXTYPE=substr($TAXTYPE,0,3);
		$total_amt=$row1->total_amt;
		$disc_per=$row1->disc_per;
		$tot_discount=$row1->tot_discount;
		$totvatamt=$row1->totvatamt;
		$grandtot=$row1->grandtot;
				
		$orderno=$row1->orderno;
		$orderdate=$row1->orderdate;
		$trno=$row1->trno;
		$trdate=$row1->trdate;
		$hq_id=$row1->hq_id;
		
		 		
		}
		
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
		
		//$retail_code='DL NO.## & ##'; 
		}
		
		
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sample Invoice</title>
	<?php /*?><link rel="stylesheet" href="<?php echo BASE_PATH_ADMIN;?>theme_files/invoice_css/bootstrap.css"><?php */?>
    
	 <style type="text/css">
<!--
.style11 {font-size: 10px}
.style12 {
	font-size: 12px;
	font-weight: bold;
}
.style15 {font-size: 12px}
.style16 {font-size: 14px}
.style17 {font-size: 14px; font-weight: bold; }
-->
     </style>
</head>
<body>

<table  style="width:100%">

<tr><td  colspan="2" align="center">
<!--<p>ORIGINAL BUYER COPY/SELLER'S COPY/TRANSPORTER'S COPY </p>-->
  </td></tr>

<tr>
  <td  colspan="2" align="center"><strong>Credit Note</strong></td>
</tr>

<tr><td  colspan="2" align="center">= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =</td>
</tr>

<tr>
<td width="51%"  >
<?php 
$sql="select * from company_details where id=1" ; 
$rowrecords = $this->projectmodel->get_records_from_sql($sql);	
foreach ($rowrecords as $rowrecord)
{
$NAME=$rowrecord->NAME;
$ADDRESS=$rowrecord->ADDRESS;
} 
?>
				
<strong><?php echo $NAME; ?></strong><BR>
<span class="style16"><?php echo $ADDRESS; ?>
</span>
</td>

<td width="49%"  ><span class="style16">
DL NO : 9186SW/9016SBW<BR>
Office Phone : 033-2241 7067 <BR>
Fax : 033 22196297 <BR>
<?php /*?>Your Order No :<?php echo $orderno.' / '.$orderdate; ?> <BR><?php */?>
M.R NAME : <?php $sql="select a.hierarchy_name,b.name
				from tbl_hierarchy_org a,tbl_employee_mstr b  where 
				a.employee_id=b.id and a.id=".$hq_id ; 
				$rowrecords = $this->projectmodel->get_records_from_sql($sql);	
				foreach ($rowrecords as $rowrecord)
				{echo $name=$rowrecord->hierarchy_name.
				'('.$rowrecord->name.')';  } ?>
<!--A.S.M NAME : a DAS<BR>
R.S.M NAME : a DAS<BR>-->
</span>
</td>
</tr>


<tr><td  colspan="2" align="center">= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =</td>
</tr>



<tr>
<td  >
<strong>M/s.&nbsp;&nbsp;<?php echo $party_name; ?></strong><BR>
<?php echo $address; ?><BR>
<?php if($DLNO<>''){ echo  'DL No :'.$DLNO.'<br>';} ?>
<?php if($VATNO<>''){ echo 'Vat No :'.$VATNO.'<br>';} ?>
<?php if($CSTNO<>''){ echo 'CST No :'.$CSTNO.'<br>';} ?>
<?php if($PANNO<>''){ echo 'Pan No :'.$PANNO.'<br>';} ?>
</td>

<td width="42%"  >
Return No : <?php echo $invoice_no; ?> &nbsp;&nbsp;
Date : <?php echo $invoice_date; ?><BR>
Ref No  :<?php echo $orderno; ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
dt:<?php echo $orderdate; ?><BR>
<?php /*?>T/R No  :<?php echo $trno; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dt:
<?php echo $trdate; ?><BR><?php */?>

</td>
</tr>


<tr><td  colspan="2" align="center">= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =</td>
</tr>

</table>


<table style="width:100%">
  
  <tr>
    <td colspan="2"><span class="style11">Product</span></td>
	<!--<td width="17%" ><span class="style11">MANUFACTURE</span></td>-->
	<td width="9%" ><span class="style11">Batch</span></td>
	<td width="14%" ><span class="style11">Exp.dt</span></td>
	<td width="10%" ><span class="style11">Mfg.dt</span></td>
	<td width="4%" ><span class="style11">TP</span></td>
	<td width="5%" ><span class="style11">MRP</span></td>
	<td width="6%"><span class="style11">Disc(%)</span></td>
	<td width="6%"><span class="style11">Disc Amt</span></td>
	<td width="5%"><span class="style11">Add(%)</span></td>
	<td width="6%"><span class="style11">Add Amt</span></td>
    <td width="7%" ><span class="style11">QTY</span></td>
    <td width="9%" align="right"><span class="style11"><strong>Total</strong></span></td>
  </tr>
  <tr>
    <td  colspan="13" align="center">= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	</td>
  </tr>
<?php
	$totalamt=0;
	$totalvat=0;
	$totalqnty=0;
	$totaledprice=0;										
	$sql="select * from invoice_details 
	where invoice_summary_id=".$table_id." 
	order by  id    ";
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	$i = 1;
	if(count($rowrecord) > 0){
	foreach ($rowrecord as $row){ 
	$alt = ($i%2==0)?'alt1':'alt2';
	$stotal=0;				
?>
					
  <tr>
   		<td colspan="2"><span class="style11">
		<?php
		$sqlprd="select * from brands where	id=".$row->product_id." ";
		$rowsqlprd = $this->projectmodel->get_records_from_sql($sqlprd);	
		foreach ($rowsqlprd as $rowprd){ 
		$product_name=$rowprd->brand_name;			
		}
		echo $product_name; ?></span>
		</td>
		<td><span class="style11"><?php echo $row->batchno; ?></span></td>
		
		<td><span class="style11"><?php echo $row->exp_monyr; ?></span></td>
		<td><span class="style11"><?php echo $row->mfg_monyr; ?></span></td>
		<td><span class="style11"><?php echo $row->srate; ?></span></td>
		<td><span class="style11"><?php echo $row->mrp; ?></span></td>
		<td><span class="style11"><?php echo $row->disc_per; ?></span></td>
		<td><span class="style11"><?php echo $row->disc_amt; ?></span></td>
		<td><span class="style11"><?php echo $row->vat_per; ?></span></td>
		<td><span class="style11"><?php echo $row->vatamt; ?></span></td>		
		<td><span class="style11"><?php echo $row->totqnty; ?></span></td>
		<td  align="right" ><span class="style11"><?php 
		$totalamt=$totalamt+$row->subtotal;
		echo $row->subtotal; ?></span></td>
  </tr>
  
  <?php }} ?>
  
  
 <tr> <td  colspan="13" align="center">= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	</td>
 </tr>
  
  <tr>
    <td colspan="12"><span class="style15">Total</span></td>
  
    <td align="right"><span class="style15"><?php echo $total_amt; ?></span></td>
  </tr>
  
  <tr>
    <td colspan="12"><span class="style15">Less Discount</span> </td>
    <td align="right"><span class="style15"><?php echo $tot_discount; ?></span></td>
  </tr>
  
  <tr>
    <td colspan="12"></td>
    <td align="right">- - - - - - - - </td>
  </tr>
  
  <tr>
    <td colspan="12"><span class="style15">Total After Discount</span></td>
    <td align="right"><span class="style15"><?php echo sprintf('%0.2f', $total_amt-$tot_discount); ?></span>	</td>
  </tr>
  
  <tr>
    <td colspan="12"><span class="style15">Add<?php echo ' '.$TAXTYPE;?></span></td>
    <td align="right"><span class="style15"><?php echo $totvatamt;?></span>	</td>
  </tr>
  
  <tr>
    <td colspan="12"></td>
    <td align="right">- - - - - - - - </td>
  </tr>
  
  <tr>
    <td colspan="12"><span class="style15">Net Payable Amount (Rnd Off)</span></td>
    <td align="right"><span class="style15"><?php echo $grandtot;?></span>	</td>
  </tr>
  	
 
  <tr>
    <td align="left" colspan="13">
	<span class="style15">[ Rs in Words :<?php echo $this->numbertowords->convert_number($grandtot).' Only';?> ]</span>	</td>
  </tr>
  <tr><td  colspan="13" align="center">= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = </td>
  </tr>
  
   <tr>
    <td align="left" colspan="6"></td>
	 <td align="center" colspan="7"><span class="style12">For&nbsp;
	 <?php echo $NAME; ?> <strong></strong></span></td>
  </tr>	
	
	<tr>
    <td align="left" colspan="6">
  	  </td>
	 <td  colspan="7" align="center"><br><br><br>Authorised Signatory</td>
  </tr>		
  
</table>



<p>&nbsp;</p>
<table width="100%" >
 <tr>
    <td  align="center" colspan="2">= = = = = = = = = = = = = = = = = = = = = = <span class="style17">Adjust Invoice </span> = = = = = = = = = = = = = = = = = = = = = = =	</td>
  </tr>
 <tr>
    <td ><span class="style17">Adjust Invoice No</span></td>
    <td width="204" align="right"><span class="style17">Amount</span>	</td>
  </tr>
 <?PHP
	
	$sqlqty="select * from invoice_summary 	where id=".$table_id;
	$productlist = $this->projectmodel->get_records_from_sql($sqlqty);
	foreach ($productlist as $qdata121)
	{$credit_note_total=$qdata121->grandtot;}
	
	$grand_total=0;
	$sell_inv_id='';
	$invoice_no='';
	$grandtot='';
	$total_adjust_amt=0;
	$available_balance_to_adjust=0;
	
	$sqlqty="select * from invoice_summary 	where rtn_invoice_summary_id1=".$table_id;
	$productlist = $this->projectmodel->get_records_from_sql($sqlqty);
	if(count($productlist)>0)
	{
	foreach ($productlist as $qdata121)
	{
	$sell_inv_id=$qdata121->id;
	$invoice_no=$qdata121->invoice_no;
	$grandtot=$qdata121->grandtot;
	$total_adjust_amt=$qdata121->total_adjust_amt;
	
	?>
 	
 
 	<tr>
	<td ><span class="style15"><?php echo $invoice_no; ?></span></td>
	<td align="right">
	<?php 
	echo $total_adjust_amt;
	$grand_total=$grand_total+$total_adjust_amt; 
	?>	</td>
	</tr>
	<?php }} ?>
	
	 <tr>
    <td  align="center" colspan="2">= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = =
	</td>
  </tr>
</table>


  
</body>
</html>