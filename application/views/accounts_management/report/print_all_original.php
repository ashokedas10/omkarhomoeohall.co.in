
<?php /*?><?php if($PRINTTYPE=='BARCODE'){ ?>

<style type="text/css">

<!--BARCODE START-->

<!--LABEL PRINT CODE -->
<!--https://www.codeproject.com/Articles/90577/Building-a-Label-Printing-Software-using-HTML-CSS-->

.label_barcode {
    background :white ;
    position :absolute ;
	border-collapse :collapse ; 
    width :4.5cm ; 
    height :1.1cm ;
    margin : 0.00cm ;
    padding : 0 ;
    left : 0.00cm ;
    bottom : 0.00cm ;
    border :1px solid black ;
    border-style :dotted ;
	font-size:x-small;
}
.style1 {font-size: 6px;}

</style>

		<?php
			
			$displayat='LEFT';
			$cnt=0;
			$hieghtmultiplier=1.3;
			$bottom=0;
			$extra=0;
				
			$sql="select * from  invoice_details where  invoice_summary_id =".$table_id;
			$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
			foreach ($rowrecord as $row1)
			{
			$qnty=$row1->qnty;    
			$image_path=BASE_PATH_ADMIN.'uploads/'.$row1->id.'.png';
			
			$val=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row1->product_id);
							
			$imgtext=substr($val,0,15).'|'.$row1->batchno.'|'.$row1->rackno;
			
			//for($lp=1;$lp<=$qnty;$lp++)
			for($lp=1;$lp<=1;$lp++)
			{
				
				$bottom=$cnt*$hieghtmultiplier;
				$extra=$cnt*0.15;
		?>
		
		<?php  if( $displayat=='LEFT'){?>	 
			<div   class="label_barcode" style="left:0.00cm;margin-left:0.156cm;margin-bottom:<?php echo $bottom+$extra; ?>cm" >	    
			<?php echo $imgtext; ?><br><img src="<?php echo $image_path; ?>"/>	
			</div>	
		<?php } ?>
		
		
		<?php  if($displayat=='RIGHT'){	 ?>	
			<div  class="label_barcode" style="left:4.6cm;margin-left:0.156cm;margin-bottom:<?php echo $bottom+$extra; ?>cm">
			<?php echo $imgtext; ?><br><img src="<?php echo $image_path; ?>"/>
			</div>	
		<?php }?>
		
			
	<?php 	if($displayat=='LEFT'){$displayat='RIGHT';}
		else	{$displayat='LEFT';$cnt=$cnt+1;}
	} } ?>	
<?php } ?>
<?php */?>



<?php if($PRINTTYPE=='LABEL'){ ?>

<!-- Latest compiled and minified CSS -->

<link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Permanent+Marker&display=swap" rel="stylesheet">


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<style>   
br
{   content: "A" !important;
    display: block !important;
    margin-bottom: 1px !important;
}

	
.box {
        font-family: 'Quicksand', sans-serif,bold;
		display:block;		
        /*box-sizing: border-box;*/		
        width:38mm;
        height:34mm;	
		/*border: 0.5px solid red;*/
		margin-left: 0px;
		margin-top:0px;
		/*border: 0.5px solid red;*/
		padding-left:0px;	
    }
	
.header {
	font-family: 'Permanent Marker', bold;    
	/*font-family:Arial, Helvetica, sans-serif,bold;  */
	font-size:12px;			
	width:38mm;
	height:8mm;
	padding-top:1px;
	text-align:center; 
 
}	
	
	
.body
{
		font-family:Arial, Helvetica, sans-serif,bold;
		font-size:10px;		
        width:38mm;
        height: auto;
		margin-top:0px;
		text-align: center;
}

.footer1 {
        font-family:Arial, Helvetica, sans-serif,bold;
		font-size:8px;		
		text-align:center;			
		width:38mm;
		position:fixed;
		top:20mm;
    }		
	
.footer2 {

        font-family: 'Quicksand', sans-serif,bold;		
		width:38mm;
		position:fixed;
		background-color:#CCCCCC!important;
		top:29mm;	
		font-size:10px ;
		text-align:center ;
		color:white!important;
		
    }	
	
	/*@media print {
    .footer2 {
      background-color:#0033FF!important;
	  color: #FFFFFF!important;
	  
    }*/
	
/*.style2 {font-family: 'Quicksand', sans-serif;font-size:8px; margin-top:0mm; margin-top:0px;}*/
     
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
	$sql="select * from  invoice_details 	where PRODUCT_TYPE<>'RAW' and invoice_summary_id =".$table_id." and label_print='Y' group by product_id";
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row1)
	{
		
		$saledate=$this->projectmodel->GetSingleVal('invoice_date','invoice_summary',' id='.$table_id);	
		$Synonym=$this->projectmodel->GetSingleVal('Synonym','productmstr',' id='.$row1->product_id);
		$productname=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row1->product_id);	
		$this->projectmodel->create_product_barcode($row1->product_id);
		
		$barcode_image_path=BASE_PATH_ADMIN.'uploads/'.$row1->product_id.'.png';
		
		if($Synonym=='')
		{$Synonym=$productname;}
	
?>
	
	<!--<div class="box" >-->		
		<div class="header"><img src="<?php echo $image_path; ?>" width="22" height="22"/><?php echo $comp_name; ?></div>		
		<div class="body" >
		<?php echo $Synonym;?><br />
		<img src="<?php echo $barcode_image_path; ?>" style="width:37mm" />		</div>
		
		<div  class="footer1" >
		DL NO : <?php echo $DLNO1; ?> ,PH:<?php echo $PhoneNo; ?> <br />
		<?php echo $comp_address; ?><br />
		</div>		
		<div class="footer2" ><strong>HOMEOPATHIC MEDICINE</strong></div>			
		<div  style=" page-break-after:always;" >&nbsp;</div>
		
	<!--</div>		-->
	
<?php }} ?>

