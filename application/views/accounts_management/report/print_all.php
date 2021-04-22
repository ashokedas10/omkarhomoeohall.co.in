<html>
<head>

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Coda+Caption:wght@800&display=swap" rel="stylesheet">


<?php
$total_height=109;
//12+10+30+22
$label_width=151;
$header_height=12;


$header_footer_height=10;

$product_all_height=30;

$address_dlno_height=19;
$contact_height=10;
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
		/*padding-left:0px;*/
		/*border: 1px solid red; */
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
		padding-left:2px;
		 
		
	}
	.header_right
	{
		float: left;
		/*width: <?php// echo $header_right_width; ?>px;	*/
		height: <?php echo $header_height; ?>px;
		font-family: Arial Black; font-size:9px;
		text-align:right;
		/*padding-right:2px;*/
		/*margin-right:3px;*/
		
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
		font-size: 7px; 
		font-weight: bold;
		text-align:center;
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
	
	 
	}
	
	.pagebrk_div {
	 page-break-after:always;
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
		padding-right:5px;
		font-weight: bold;		
	}
	.footer_mrp {
		
		height: <?php echo $footer_mrp_height; ?>px;
		font-family:Bookman Old Style;font-size: 9px;
		text-align:right;
		padding-right:5px;
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
	
	/*
	$total_height=109;
//12+10+30+22
$label_width=152.4;

width :30mm*/
	
@page {
  size: 40mm 30mm;
 	margin: 0mm 0mm 0m 0mm;
}

/*@media print {
  html, body {
    width: 40mm;
    height: 30mm;
  }*/
  

body  
{ 
    /* this affects the margin on the content before sending to printer */ 
    margin: 0px;  
} 
	
	
</style>


</head>
<?php /*?><body onLoad="window.print();" id="myBody" onchange = "shortcut()"><?php */?>

<body onLoad="window.print();" onafterprint="self.close()">

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
	where invoice_summary_id =".$table_id." and label_print='Y' and  main_group_id not in (57,58,59,60,61,62) and ITEM_DELETE_STATUS='NOT_DELETED' ";
	
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
			
		
		$no_of_dose=$row1->no_of_dose;
		$dose_formula=$row1->dose_Synonym;
		
		$header_left_width=100;
		$header_right_width=$label_width-$header_left_width;
				
		if($row1->main_group_id==50) //MOTHER
		{
			
			if($row1->product_Synonym<>'')
			{$product_name=$row1->product_Synonym;}
			if($row1->pack_synonym<>'')
			{$pack_name=$row1->pack_synonym;}
			
			$product_name=$product_name.'<br>'.$potency_name;$GROUPNAME='MOTHER';
		
		}
		
		if($row1->main_group_id==52) //TRITURATION
		{
			if($row1->product_Synonym<>'')
			{$product_name=$row1->product_Synonym;}
			if($row1->pack_synonym<>'')
			{$pack_name=$row1->pack_synonym;}
			
			$product_name=$product_name; $GROUPNAME='TRITURATION';
		}
		
		if($row1->main_group_id==53) //DILUTION
		{
			if($row1->product_Synonym<>'')
			{$product_name=$row1->product_Synonym;}
			
			$product_name=$product_name.'<br>'.$potency_name;  $GROUPNAME='DILUTION';
			//echo $row1->pack_synonym;
			if($row1->pack_synonym<>'')
			{$pack_name='GL :'.$row1->pack_synonym.'&nbsp;&nbsp;&nbsp;&nbsp;'.$pack_name;}
			
			$header_left_width=40;
			$header_right_width=$label_width-$header_left_width;
			
		}
		
		if($row1->main_group_id==54) //BIOCHEMIC
		{
			if($row1->product_Synonym<>'')
			{$product_name=$row1->product_Synonym;}
			if($row1->pack_synonym<>'')
			{$pack_name=$row1->pack_synonym;}
			$product_name=$product_name.'<br>'.$potency_name; 
			$GROUPNAME='BIOCHEMIC';
		}
		
		if($row1->main_group_id==55) //WATER
		{
			if($row1->product_Synonym<>'')
			{$product_name=$row1->product_Synonym;}
			if($row1->pack_synonym<>'')
			{$pack_name=$row1->pack_synonym;}
			
			$product_name=$product_name.'<br>'.$potency_name; $GROUPNAME='WATER';
			$pack_name=$no_of_dose.' Dose &nbsp;&nbsp;&nbsp;'.$pack_name;
			$header_left_width=50;
			$header_right_width=$label_width-$header_left_width;
		
		}
		
		if($row1->main_group_id==56) //SUGAR_OF_MILK
		{
			if($row1->product_Synonym<>'')
			{$product_name=$row1->product_Synonym;}
			if($row1->pack_synonym<>'')
			{$pack_name=$row1->pack_synonym;}
			
			$product_name=$product_name.'<br>'.$potency_name; $GROUPNAME='SUGAR';
			$pack_name=$dose_formula.'='.$no_of_dose.' Dose ';
			$header_left_width=50;
			$header_right_width=$label_width-$header_left_width;
		}
			
		if($row1->main_group_id==51) //PATENT
		{
			$product_name=$product_name;$GROUPNAME='';
			$pack_name='';
			$header_left_width=50;
			$header_right_width=$label_width-$header_left_width;
		
		}	
			
		
		//$printcount=3;
		//$pagebrk_cnt=1;
		for($i=1;$i<=$row1->qnty;$i++){
	
		//echo $label_count;
?>

<div class="page">

	<div class="header">
	<div class="header_left" style="width: <?php echo $header_left_width-5; ?>px; "><strong><?php echo $GROUPNAME; ?></strong></div>
	<div class="header_right" style="width: <?php echo $header_right_width-5; ?>px; text-align:right; "><strong><?php echo $pack_name; ?></strong></div>	
	</div>
	
	<div  class="header_footer"><strong>HOMOEOPATHIC MEDICINE</strong> </div>
	<div class="product_all"><?php echo $product_name; ?></div>	
	
	<div class="footer">
		<div class="footer_pack">PKD:<?php echo $saledate; ?></div>
		<div  class="footer_mrp">MRP:<?php echo sprintf('%0.2f',$row1->mrp); ?></div>	
	</div>	
	<div class="address_dlno" ><?php echo $comp_address.' / DL NO:'.$DLNO1;; ?></div>
	<div class="contact"  style="margin-top:0px;" ><?php echo $PhoneNo; ?></div>
	
	<div class="pagebrk_div" > &nbsp;</div>
	
	
	
</div>	
	
<?php $label_count=$label_count-1;}} ?>



<!--MOTHER MIXER-->
<?php
	
	$header_left_width=70;
	$header_right_width=$label_width-$header_left_width;
	
	$top=0;
	$GROUPNAME='Mother Mix';
	$saledate=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary',' id='.$table_id);	
	$saledate=substr($saledate,8,2).'.'.substr($saledate,5,2).'.'.substr($saledate,2,2);
	//$this->projectmodel->create_product_barcode($row1->product_id);
	//$barcode_image_path=BASE_PATH_ADMIN.'uploads/'.$row1->product_id.'.png';
			
			
	$groups="select main_group_id,qnty from  invoice_details where 
	invoice_summary_id =".$table_id." and  main_group_id in (57,58,59,60,61,62) and ITEM_DELETE_STATUS='NOT_DELETED' group by main_group_id";
	$groups = $this->projectmodel->get_records_from_sql($groups);
	foreach ($groups as $group)
	{
		$mrp=$no_of_product_in_mix=0;
		$product_name1=$product_name2='';
		$total_pack=0;
		$pack_calc='';
		$sql="select * from  invoice_details 	
		where invoice_summary_id =".$table_id." and label_print='Y' and ITEM_DELETE_STATUS='NOT_DELETED' and main_group_id=".$group->main_group_id;
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		$label_count=sizeof($rowrecord);
		
		foreach ($rowrecord as $row1)
		{			
			 $product_name=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row1->product_id);	
			$mrp=$mrp+$row1->mrp;
			if($row1->product_Synonym<>'')
			{$product_name=$row1->product_Synonym;}
			
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
			
			$pack_calc=$pack_calc.' '.$pack_name;
			
			$no_of_product_in_mix=$no_of_product_in_mix+1;
		}	
		
		preg_match_all('!\d+!', $pack_calc, $matches);
				
		//print_r($matches);
		//$total_pack=$total_pack+$matches[0];
		
		foreach ($matches as $key=>$matche)
		{
			foreach ($matche as $mat)
			{
				$total_pack=$total_pack+$mat;
			}
		}
		$total_pack=$total_pack.' ML';
					
	//echo $product_name1;
	//echo $product_name2;
?>


	
<?php 
	if($no_of_product_in_mix>0){?>
	<div class="page">
	
		<?php /*?><div class="header">
			<div class="header_left"><strong><?php echo $GROUPNAME; ?></strong></div>
			<div class="header_right"><strong><?php echo $total_pack; ?></strong></div>	
		</div><?php */?>
		
		<div class="header">
			<div class="header_left" style="width: <?php echo $header_left_width-5; ?>px; "><strong><?php echo $GROUPNAME; ?></strong></div>
			<div class="header_right" style="width: <?php echo $header_right_width-5; ?>px; text-align:right; "><strong><?php echo $total_pack; ?></strong></div>	
		</div>
		
		<div  class="header_footer"><strong>HOMOEOPATHIC MEDICINE</strong></div>
		
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