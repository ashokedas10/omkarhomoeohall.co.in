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


<?php
$total_height=109;
//12+10+30+22
$label_width=151;
$header_height=12;


$header_footer_height=10;

$product_all_height=30;

$address_dlno_height=18;
$contact_height=9;
$footer_height=30;


$footer_pack_height=7;
$footer_mrp_height=9;

?>



<style>
	.SivaDiv {
		background-image: url(<?PHP echo BASE_PATH_ADMIN.'uploads/LABEL_EXPERIMENT-1.png'; ?>);
		background-repeat: no-repeat;
		width: 40mm;
		height: 29mm;
		margin-left: 0;
		/*border: 1px solid red;*/
	}
	
	.page{
	
		width: <?php echo $label_width; ?>px;		
		height: <?php echo $total_height; ?>px;
		border: 1px solid red; 
	}
	
	
	.header {
		
		padding-left: 0px;
		width: <?php echo $label_width; ?>px;		
		height: <?php echo $header_height; ?>px;
	/*	margin-left: 0;
		font-size:9px;
		font-family: Fiona Regular, sans-serif;*/
		/*border: 1px solid red;*/
	}
	.header_left
	{
		float: left;
		/*width: <?php //echo $header_left_width; ?>px;	*/
		height: <?php echo $header_height; ?>px;
		font-family: Arial Black; font-size:9px;
		text-align:left;
		
	}
	.header_right
	{
		float: left;
		/*width: <?php// echo $header_right_width; ?>px;	*/
		height: <?php echo $header_height; ?>px;
		font-family: Arial Black; font-size:9px;
		text-align:right;
		
	}
	.header_footer
	{
		width: <?php echo $label_width; ?>px;
		height: <?php echo $header_footer_height; ?>px;
		text-align:center;
		font-family:Bookman Old Style; 
		font-size:9px;
	}
	
	.address_dlno
	{
		width: <?php echo $label_width; ?>px;
		height: <?php echo $address_dlno_height; ?>px;
		font-family:Bookman Old Style;
		font-size: 8px; 
		font-weight: bold;
		/*margin-top:1px;*/
	}
	
	.contact {
	 width: <?php echo $label_width; ?>px;
	 height: <?php echo $contact_height; ?>px;
	/* margin-top: 2px;*/
  	 color: white;
	 font-family:Bookman Old Style;
	 text-align:center;
	 font-size: 8px;
	 font-weight: bold;
	 background-color:#000000;
	 page-break-after: always; 
	}
	
	
	.footer {
		background-image: url(<?PHP echo BASE_PATH_ADMIN.'uploads/footer.png'; ?>);
		background-repeat: no-repeat;
		width: <?php echo $label_width; ?>px;
		height: <?php echo $footer_height; ?>px;
	}
	
	.footer_pack {
		
		height: <?php echo $footer_pack_height; ?>px;
		font-family:Bookman Old Style;font-size: 7px;
		text-align:right;
		padding-top:6px;
		font-weight: bold;		
	}
	.footer_mrp {
		
		height: <?php echo $footer_mrp_height; ?>px;
		font-family:Bookman Old Style;font-size: 9px;
		text-align:right;
		font-weight: bold;
		
	}
	
	.product_all
	{
		width: <?php echo $label_width; ?>px;
		height: <?php echo $product_all_height; ?>px;
		font-family:Bookman Old Style; 
		font-size:15px;
		text-align:center;
		font-weight: bold;
	}
	
	.product_mm
	{
		width: <?php echo $label_width; ?>px;
		height: <?php echo $product_all_height; ?>px;
		font-family:Bookman Old Style;font-size:9px;text-align:center;font-weight: bold;
	}	
	.product_mm_left
	{
		float: left;
		width: 50%;
		font-family:Bookman Old Style; 
		font-size:6px;
		text-align:left;
		/*font-weight: bold;*/
	}
	.product_mm_right
	{
		float:right;
		width: 50%;
		font-family:Bookman Old Style; 
		font-size:6px;
		text-align:right;
		/*font-weight: bold;*/
	}
	

	/*@media print {
		.graph-img img {
			display: inline;
		}
		 h1 {page-break-before: always;}
	}*/
</style>


</head>
<?php /*?><body onLoad="window.print();" id="myBody" onchange = "shortcut()"><?php */?>

<body id="myBody" onchange = "shortcut()">

<?php if($PRINTTYPE=='LABEL'){ 

	
$COMP_ID=$this->session->userdata('COMP_ID');
$comp_name=$this->projectmodel->GetSingleVal('NAME','company_details',' id='.$COMP_ID);	
$comp_address=$this->projectmodel->GetSingleVal('ADDRESS','company_details',' id='.$COMP_ID);
$DLNO1=$this->projectmodel->GetSingleVal('DLNO1','company_details',' id='.$COMP_ID);
$PhoneNo=$this->projectmodel->GetSingleVal('PhoneNo','company_details',' id='.$COMP_ID);
	
$tothieghtmultiplier=3.5;
//$image_path=BASE_PATH_ADMIN.'uploads/'.'ROYHOMEOlogo.png';
$image_path=BASE_PATH_ADMIN.'uploads/'.'logo_label.jpg';

?>

<?php
	//and product_group_id not in (102,191,192,276,277,278,279,280)
	$top=0;
	$sql="select * from  invoice_details 
	where invoice_summary_id =".$table_id." and label_print='Y' and  main_group_id not in (57,58,59,60,61,62)  ";
	
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	$label_count=sizeof($rowrecord);
	
	foreach ($rowrecord as $row1)
	{
		
		$saledate=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary',' id='.$table_id);	
		$saledate=substr($saledate,8,2).'.'.substr($saledate,5,2).'.'.substr($saledate,2,2);
		$Synonym=$this->projectmodel->GetSingleVal('Synonym','productmstr',' id='.$row1->product_id);
		
		$productname=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row1->product_id);	
		$this->projectmodel->create_product_barcode($row1->product_id);
		$barcode_image_path=BASE_PATH_ADMIN.'uploads/'.$row1->product_id.'.png';
		
		//
		$GROUPNAME='';
		$product_name=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row1->product_id);
								
		if($row1->product_Synonym<>'')
		{$product_name=$row->product_Synonym;}
		
		//potency
		if($row1->potency_id>0)
		{$potency_name=$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row1->potency_id);}
		else
		{ $potency_name='';}
		
		if($row1->Synonym<>'')
		{$potency_name=$row1->Synonym;}
	
		//pack			
		if($row1->pack_id>0)
		{$pack_name=$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row1->pack_id);}
		else
		{ $pack_name='';}
		
		if($row1->pack_synonym<>'')
		{$pack_name=$row1->pack_synonym;}
		
		$no_of_dose=$row1->no_of_dose;
		$dose_formula=$row1->dose_Synonym;
		
		$header_left_width=100;
		$header_right_width=$label_width-$header_left_width;
				
		if($row1->main_group_id==50) //MOTHER
		{$product_name=$product_name.' '.$potency_name;$GROUPNAME='MOTHER TINCTURE';}
		
		if($row1->main_group_id==52) //TRITURATION
		{$product_name=$product_name; $GROUPNAME='TRITURATION';}
		
		if($row1->main_group_id==53) //DILUTION
		{$product_name=$product_name.' '.$potency_name;  $GROUPNAME='DILUTION';}
		
		if($row1->main_group_id==54) //BIOCHEMIC
		{$product_name=$product_name.' '.$potency_name; $GROUPNAME='BIOCHEMIC';}
		
		if($row1->main_group_id==55) //WATER
		{$product_name=$product_name.' '.$potency_name; $GROUPNAME='WATER';
		$pack_name=$no_of_dose.' Dose '.$pack_name;
		$header_left_width=50;
		$header_right_width=$label_width-$header_left_width;
		
		}
		
		if($row1->main_group_id==56) //SUGAR_OF_MILK
		{
		$product_name=$product_name.' '.$potency_name; $GROUPNAME='SUGAR';
		$pack_name=$dose_formula.'='.$no_of_dose.' Dose ';
		$header_left_width=50;
		$header_right_width=$label_width-$header_left_width;
		}
			
		
		//$printcount=3;
		for($i=1;$i<=$row1->qnty;$i++){
	
?>

<div class="page">

	<div class="header">
	<div class="header_left" style="width: <?php echo $header_left_width; ?>px; "><strong><?php echo $GROUPNAME; ?></strong></div>
	<div class="header_right" style="width: <?php echo $header_right_width; ?>px; text-align:right; "><strong><?php echo $pack_name; ?></strong></div>	
	</div>
	
	<div  class="header_footer">HOMOEOPATHIC MEDICINE</div>
	<div class="product_all"><?php echo $product_name; ?></div>	
	
	<div class="footer">
		<div class="footer_pack">PKD:<?php echo $saledate; ?></div>
		<div  class="footer_mrp">MRP:<?php echo intval($row1->mrp); ?></div>	
	</div>	
	<div class="address_dlno" ><?php echo $comp_address.' / DL NO:'.$DLNO1;; ?></div>
	<div class="contact" ><?php echo $PhoneNo; ?></div>
	
</div>	
	
<?php $label_count=$label_count-1;}} ?>



<!--MOTHER MIXER-->
<?php
	
	$top=0;
	$GROUPNAME='MOTHER MIXURE';
	$saledate=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary',' id='.$table_id);	
	$saledate=substr($saledate,8,2).'.'.substr($saledate,5,2).'.'.substr($saledate,2,2);
	//$this->projectmodel->create_product_barcode($row1->product_id);
	//$barcode_image_path=BASE_PATH_ADMIN.'uploads/'.$row1->product_id.'.png';
			
			
	$groups="select main_group_id,qnty from  invoice_details where 
	invoice_summary_id =".$table_id." and  main_group_id in (57,58,59,60,61,62) group by main_group_id";
	$groups = $this->projectmodel->get_records_from_sql($groups);
	foreach ($groups as $group)
	{
		$mrp=$no_of_product_in_mix=0;
		$product_name1=$product_name2='';
		$sql="select * from  invoice_details 	
		where invoice_summary_id =".$table_id." and label_print='Y' and main_group_id=".$group->main_group_id;
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		$label_count=sizeof($rowrecord);
		
		foreach ($rowrecord as $row1)
		{			
			 $product_name=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row1->product_id);	
			$mrp=$mrp+$row1->mrp;
			if($row1->product_Synonym<>'')
			{$product_name=$row->product_Synonym;}
			
			//potency
			if($row1->potency_id>0)
			{$potency_name=$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row1->potency_id);}
			else
			{ $potency_name='';}
			
			if($row1->Synonym<>'')
			{$potency_name=$row1->Synonym;}
		
			//pack			
			if($row1->pack_id>0)
			{$pack_name=$this->projectmodel->GetSingleVal('name','misc_mstr',' id='.$row1->pack_id);}
			else
			{ $pack_name='';}
			
			if($row1->pack_synonym<>'')
			{$pack_name=$row1->pack_synonym;}
							
			if($no_of_product_in_mix<=2) //MOTHER
			{$product_name1=$product_name1.$product_name.' '.$potency_name.' '.$pack_name.'<br>';}
			
			if($no_of_product_in_mix>2 && $no_of_product_in_mix<=5) //MOTHER
			{$product_name2=$product_name2.$product_name.' '.$potency_name.' '.$pack_name.'<br>';}
			
			$no_of_product_in_mix=$no_of_product_in_mix+1;
		}	
					
	//echo $product_name1;
	//echo $product_name2;
?>


	
<?php 
	if($no_of_product_in_mix>0){?>
	<div class="page">
	
		<div class="header">
			<div class="header_left"><strong><?php echo $GROUPNAME; ?></strong></div>
			<?php /*?><div class="header_right"><strong><?php echo $pack_name; ?></strong></div><?php */?>	
		</div>
		
		<div  class="header_footer">HOMOEOPATHIC MEDICINE</div>
		
		<?php if($no_of_product_in_mix<=3){ ?>
		<div class="product_mm" >
			<?php echo $product_name1; ?>
		</div>	
		<?php }else { ?>
		<div class="product_mm">
			<div class="product_mm_left"><?php echo $product_name1; ?></div>	
			<div class="product_mm_right"><?php echo $product_name2; ?></div>	
		</div>	
		
		<?php } ?>
		
		<div class="footer">
			<div class="footer_pack">PKD:<?php echo $saledate; ?></div>
			<div  class="footer_mrp">MRP:<?php echo intval($mrp); ?></div>	
		</div>	
		<div class="address_dlno" ><?php echo $comp_address.' / DL NO:'.$DLNO1; ?></div>
		<div class="contact" ><?php echo $PhoneNo; ?></div>
		
	</div>	
<?php }} ?>

<?php } ?>

</body>
</html>