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
.tableitem_number{
   
  font-family: Arial Black; 
  font-size:11px;;
  font-weight:bold;
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
.style1 {font-family: 'Courier'; font-weight:bold; font-size: 16px}
.style2 {font-family:'Bookman Old Style'; font-weight:500;  font-size: 18px}
.product_list {font-family:'Bookman Old Style'; font-weight:500;  font-size: 12px}

.product_list_product {font-family:'Bookman Old Style'; font-weight:500;  font-size: 15px}


.header_section {font-family:'Bookman Old Style'; font-weight:500;  font-size: 14px}
   
 /*font-weight:bold;*/
 </style>



<html>
<head></head>
<!--onafterprint="self.close()"-->
<?php /*?>onLoad="window.print();" onafterprint="self.close()"<?php */?>
<body onLoad="window.print();" onafterprint="self.close()" >


<div id="invoice-POS"  >


<?php

		$image_path=BASE_PATH_ADMIN.'uploads/'.'logo.png';
		$company_id=$this->session->userdata('COMP_ID');
		$company_records="select * from company_details where id=".$company_id;					
		$company_records = $this->projectmodel->get_records_from_sql($company_records);	

?>
	
	<div id="mid">
      <div class="info" style="border:" >                 
          <img src="<?php echo $image_path; ?>"  height="60"/>
		  <span style="font-size:22px; position:absolute; top:20px; left:90px;">
		  <?php /*?><?php echo '<strong>'.$company_records[0]->NAME.'</strong>';?><?php */?></span>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="mid">
      <div class="info header_section" >                 
           Add : <?php echo $company_records[0]->ADDRESS;?></br>
           Email : <?php echo $company_records[0]->company_mailid;?></br>
           Ph : <?php echo $company_records[0]->PhoneNo;?></br>
		   DL NO:<?php echo $company_records[0]->DLNO1;?><br>
		   <?php if($company_records[0]->GSTNo<>''){?>
		   GST NO:<?php echo $company_records[0]->GSTNo;?><br>
		   <?php }?>
      </div>
    </div><!--End Invoice Mid-->
	<br><br>
	
	<div id="mid"  align="center" style="border:dotted;font-family:'Bookman Old Style'; font-weight:500;  font-size: 14px"></div>
  <div id="mid" ></div>
	<!--End Invoice Mid-->
		
    
    <div id="bot">
		<div id="table">					
			<table style="width:100%;border:dotted" align="center"  >					
				
					
					<?php /*?><?php 
												
						
											
						$groups="select brand_id from productmstr  
						a,product_balance_companywise b
						where 
						a.id>0 and a.id=b.product_id and a.group_id=289 
						and b.order_qnty >0 and b.company_id=".$company_id." group by a.brand_id";
												
						$groups = $this->projectmodel->get_records_from_sql($groups);
						foreach ($groups as $group)
						{
						$whr=" id=".$group->brand_id;
						$brand_name=$this->projectmodel->GetSingleVal('name','misc_mstr',$whr);
						$brand_name_short=$this->projectmodel->GetSingleVal('name_value','misc_mstr',$whr);
							
					?>	<?php */?>
					<tr><td  colspan="11"><div style="border-bottom:solid;">&nbsp;</div></td></tr>	
					<tr>	
							
						<td  class="item product_list_product">Product Name</td>	
						<td  class="item product_list_product" align="right">Order Qnty</td>	
					</tr>
					<tr><td  colspan="11"><div style="border-top:solid;">&nbsp;</div></td></tr>
					
					<?php /*?><tr><td  colspan="11"><div style="border-bottom:solid;">&nbsp;</div></td></tr>		
					<tr>			
						<td  class="item product_list" colspan="2"><?php 
						echo '('.$brand_name_short.') '.$brand_name; ?></td>	
					</tr>
					<tr>	
						<td  class="item product_list_product">Company</td>		
						<td  class="item product_list_product">Product Name</td>	
						<td  class="item product_list_product">Order Qnty</td>	
					</tr>
					<tr><td  colspan="11"><div style="border-top:solid;">&nbsp;</div></td></tr><?php */?>
					
					<?php 
						
						$company_id=$this->session->userdata('COMP_ID');					
																	
						 $products="select a.*,b.order_qnty from productmstr  
						a,product_balance_companywise b
						where 
						a.id>0 and a.id=b.product_id and a.group_id=289 and 						
						 b.order_qnty >0 and b.company_id=".$company_id." 
						ORDER BY a.brand_id,a.productname";
												
						$products = $this->projectmodel->get_records_from_sql($products);
						foreach ($products as $product)
						{
						
						$whr=" id=".$product->brand_id;
						$brand_name=$this->projectmodel->GetSingleVal('name','misc_mstr',$whr);
						$brand_name_short=$this->projectmodel->GetSingleVal('name_value','misc_mstr',$whr);
						
						
					?>			
					
					
					<tr>	
					   	
						<td  class="item product_list_product"><?php echo '('.$brand_name_short.')'.$product->productname; ?></td>	
						<td  class="item product_list_product" align="center"><?php echo $product->order_qnty; ?></td>	
					</tr>	
					
					<?php }//} ?>			
					
							
		  </table>
	  </div><!--End Table-->
					
								
			
  </div><!--End InvoiceBot-->
</div><!--End Invoice-->



</div>

</body>

</html>
