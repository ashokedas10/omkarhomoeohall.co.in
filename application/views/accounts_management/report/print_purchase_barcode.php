<style type="text/css">

<!--BARCODE START-->

<!--LABEL PRINT CODE -->
<!--https://www.codeproject.com/Articles/90577/Building-a-Label-Printing-Software-using-HTML-CSS-->

.body
{
	font-family:Arial, Helvetica, sans-serif,bold;
	font-size:10px;		
	width:10mm;
	height: auto;
	margin-top:0px;
	text-align: center;
}


.label_barcode {
  
    width :4.5cm ; 
    height :1.1cm ;
    margin : 0.00cm ;   
    border :1px solid black ;
    border-style :dotted ;
	font-size:x-small;
}
.style1 {font-size: 6px;}

</style>

<style> 
	<?php /*?>h1 { 
		text-align:center; 
		color:green; 
	} 
	body { 
		width:70%; 
	} <?php */?>
	
	.container .box { 
		font-size:10px;		
		width:10mm;
		height: auto;
		display:table; 
	} 
	.container .box .box-row { 
		display:table-row; 
	} 
	.container .box .box-cell { 
		display:table-cell; 
		width:50%; 
		padding:5px;
		border :1px solid black ;
   		border-style :dotted ;
		text-align:center; 
		 
	} 
	.container .box .box-cell.box1 { 
		 width :4.5cm ; 
   		 height :1.1cm ;
	 } 
	.container .box .box-cell.box2 { 
		width :4.5cm ; 
   		 height :1.1cm ;
	} 
</style> 



<?php	
		$sql="select * from  invoice_details where  invoice_summary_id =".$table_id;
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{
			$image_path=BASE_PATH_ADMIN.'uploads/purchase_barcode/'.$row1->id.'.png';			
			$productname=$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$row1->product_id);	
			$Synonym=$this->projectmodel->GetSingleVal('Synonym','productmstr',' id='.$row1->product_id);	
			if($Synonym<>'')
			{$imgtext=substr($Synonym,0,15).'|'.$row1->batchno.'|'.$row1->rackno;}
			else
			{$imgtext=substr($productname,0,15).'|'.$row1->batchno.'|'.$row1->rackno;}	
?>
	
	<div class="container"> 
            <div class="box"> 
                <div class="box-row"> 
                    <div class="box-cell box1"> 
					<img src="<?php echo $image_path; ?>"   /><br />
					<?php echo $imgtext;?>
					</div>
					
					<div class="box-cell box2"> 
                   <img src="<?php echo $image_path; ?>"  /><br />
					<?php echo $imgtext;?>
                    </div> 
		</div>
		</div>
		</div>
		<div  style=" page-break-after:always;" >&nbsp;</div>	
			
		<?php /*?><div class="body" >		
		
			<div class="label_barcode" align="center" >
			<img src="<?php echo $image_path; ?>"   /><br />
			<?php echo $imgtext;?>
			</div>
			
			<div class="label_barcode"  align="center" >
			<img src="<?php echo $image_path; ?>"  /><br />
			<?php echo $imgtext;?>
			</div>		
		
		</div>
		<div  style=" page-break-after:always;" >&nbsp;</div><?php */?>
		
	
<?php } ?>
