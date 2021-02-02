<html>
<head>

<script type = "text/javascript">
var myWindow;
/*Final Submit(F8) | New Mixer(F9) | Print Invoice(F10) | Print POS(F11) | New Entry (F1) */
 function shortcut() 
 {
 `alert(event.keyCode);
  document.addEventListener("keydown", function(event) {
		if(event.keyCode==27)
		{
			 myWindow.close();
		}
	});
			
}
</script>  


<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Coda+Caption:wght@800&display=swap" rel="stylesheet">

<style>
	.SivaDiv {
		background-image: url(<?PHP echo BASE_PATH_ADMIN.'uploads/LABEL_EXPERIMENT-1.png'; ?>);
		background-repeat: no-repeat;
		width: 40mm;
		height: 29mm;
		margin-left: 0;
		/*border: 1px solid red;*/
	}
	.header {
		width: 40mm;
		height: 14mm;
		margin-left: 0;
		font-size:9px;
		font-family: Fiona Regular, sans-serif;
		/*border: 1px solid red;*/
	}
	.footer {
		background-image: url(<?PHP echo BASE_PATH_ADMIN.'uploads/footer.png'; ?>);
		background-repeat: no-repeat;
		width: 40mm;
		height: 15mm;
		margin-left: 0;
		/*border: 1px solid red;*/
	}
	
	.style1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight:200;
	
	}
	

	/*@media print {
		.graph-img img {
			display: inline;
		}
		 h1 {page-break-before: always;}
	}*/
</style>


</head>
<body onLoad="window.print();" id="myBody" onchange = "shortcut()">

<?php if($PRINTTYPE=='LABEL'){ ?>
<?php
	
	$COMP_ID=$this->session->userdata('COMP_ID');
	$comp_name=$this->projectmodel->GetSingleVal('NAME','company_details',' id='.$COMP_ID);	
	$comp_address=$this->projectmodel->GetSingleVal('ADDRESS','company_details',' id='.$COMP_ID);
	$DLNO1=$this->projectmodel->GetSingleVal('DLNO1','company_details',' id='.$COMP_ID);
	$PhoneNo=$this->projectmodel->GetSingleVal('PhoneNo','company_details',' id='.$COMP_ID);
		
	$tothieghtmultiplier=3.5;
	//$image_path=BASE_PATH_ADMIN.'uploads/'.'ROYHOMEOlogo.png';
	$image_path=BASE_PATH_ADMIN.'uploads/'.'logo_label.jpg';
	
	$top=0;
	$sql="select * from  invoice_details 	where PRODUCT_TYPE<>'RAW' 
	and invoice_summary_id =".$table_id." and label_print='Y' group by product_id";
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	$label_count=sizeof($rowrecord);
	
	foreach ($rowrecord as $row1)
	{
		
		$saledate=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary',' id='.$table_id);	
		$Synonym=$this->projectmodel->GetSingleVal('Synonym','productmstr',' id='.$row1->product_id);
		$productname=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row1->product_id);	
		$this->projectmodel->create_product_barcode($row1->product_id);
		$barcode_image_path=BASE_PATH_ADMIN.'uploads/'.$row1->product_id.'.png';
		
		if($Synonym=='')
		{$Synonym=$productname;}
		//$printcount=3;
		for($i=1;$i<=2;$i++){
	
?>
	
	<!--<div class="box" >-->		
		<?php /*?><div class="header"><img src="<?php echo $image_path; ?>" width="22" height="22"/><?php echo $comp_name; ?></div>		
		<div class="body" >
		<?php echo $Synonym;?><br />
		<img src="<?php echo $barcode_image_path; ?>" style="width:37mm" />		</div>
		
		<div  class="footer1" >
		DL NO : <?php echo $DLNO1; ?> ,PH:<?php echo $PhoneNo; ?> <br />
		<?php echo $comp_address; ?><br />
		</div>		
		<div class="footer2" ><strong>HOMEOPATHIC MEDICINE</strong></div>
		<?php if($label_count>1){?>			
		<div  style=" page-break-after:always;" >&nbsp;</div>
		<?php } ?>	<?php */?>
		
	<?php /*?><table  class="SivaDiv"  style="width:100%;page-break-after: always;
	background-image:<?PHP echo BASE_PATH_ADMIN.'uploads/LABEL1.png'; ?>" >
	
	<tr><td>test</td></tr>
	
	</table><?php */?>

	
	<div class="header"  style="margin-right:0px;" >
	
	<table border="0" cellpadding="0" cellspacing="0">
	
	<tr>
		<td align="left" style="text-align:left;
		font-family: Arial Black; font-size:9px;"><strong>DILUTION</strong>
	   </td>
		<td align="right" style="text-align:right;
		 font-family:Arial Black; font-size:9px;"><strong>10GM</strong>
		</td>
	</tr>
	
	<tr>
		<td colspan="2" style="text-align:center;
		font-family:Bookman Old Style; font-size:9px;"><strong>HOMOEOPATHIC MEDICINE</strong><br />
		</td>
	</tr>
	
	<tr align="center">
		<td style="font-family:Bookman Old Style; 
		font-size:15px;text-align:center;" ><strong>BRYONIA ALB 200</strong>
		</td>
	</tr>
	
	
	<!--<tr>
		<td style="font-family:Arial Black; font-size:9px;"><strong>BRYONIA ALB 200</strong>
		</td>
		<td style="text-align:center;
			font-family: Fiona Regular;font-size: 8px;"><strong>BRYONIA ALB 200</strong>
		</td>
	</tr>-->
	
	
	<tr>
		<td style="text-align:center;
			font-family: Fiona Regular;font-size: 6px;" colspan="2">
			
		</td>
	
	</tr>
	
	</table>
	
	</div>		
	
	<?php /*?><?php if ($printcount>1){ ?>
	<div class="footer"  style="page-break-after: always;">
	<?php }else{ ?>
	<div class="footer">
	<?php } ?><?php */?>
	
	<div class="footer"  style="page-break-after: always; margin-right:0px;" >
		
	<table border="0" cellpadding="0" cellspacing="0" style="width:100%">
	
	<tr >
		<td align="right" style="font-family:Bookman Old Style;font-size: 7px;">
		<br /><strong>PKD:21/01/21</strong>
		</td>
	</tr>	
	
	
	<tr >
		<td align="right" style="font-family:Bookman Old Style;font-size: 9px;">
		<strong>MRP:40.00</strong>
	   </td>
	</tr>	
	
	
	</table>
	
	</div>
	
    
	
	
<?php $label_count=$label_count-1;}} } ?>

</body>
</html>





