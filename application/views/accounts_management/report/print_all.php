
<?php if($PRINTTYPE=='LABEL'){ ?>

<style>
	.SivaDiv {
		background-image: url(<?PHP echo BASE_PATH_ADMIN.'uploads/LABEL1.png'; ?>);
		background-repeat: no-repeat;
		width: 40mm;
		height: 29mm;
		/*border: 1px solid red;*/
	}

	/*@media print {
		.graph-img img {
			display: inline;
		}
		 h1 {page-break-before: always;}
	}*/
</style>



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
		
		for($i=1;$i<10;$i++){
	
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
		
	<div id="printdiv<?php echo $i; ?>" class="SivaDiv" style="width:100%;page-break-after: always;">
       	
		<!--ABROMA AUG Q 30ML <br />
		ABROMA AUG Q 30ML <br />
		ABROMA AUG Q 30ML <br />
		ABROMA AUG Q 30ML <br />-->
		<?php /*?>	<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<div style="margin-left:100px; "><h2>ABROMA AUG Q 30ML </h2></div>
		<div style="margin-left:100px; "><h2>ABROMA AUG Q 30ML </h2></div>
		<div style="margin-left:100px; "><h2>ABROMA AUG Q 30ML </h2></div>
		<div style="margin-left:100px; "><h2>ABROMA AUG Q 30ML </h2></div>
		<div style="margin-left:100px; "><h2>ABROMA AUG Q 30ML </h2></div><?php */?>
		
		
    </div>
	<!--<h1>&nbsp;</h1>-->
    
	
	
<?php $label_count=$label_count-1;}}  } ?>


