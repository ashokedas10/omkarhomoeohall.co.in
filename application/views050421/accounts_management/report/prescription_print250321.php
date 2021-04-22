<link href="https://fonts.googleapis.com/css?family=PT+Serif&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


<?php

$label_width=768; 
$total_height=550;

?>



<style type="text/css">

.doctorname
{
	width: <?php //echo $label_width; ?>px;
	height: <?php //echo $product_all_height; ?>px;
	font-family:Bookman Old Style; 
	font-size:25px;
	text-align:center;
	font-weight: bold;
}

.patient_id
{
	width: <?php //echo $label_width; ?>px;
	height: <?php //echo $product_all_height; ?>px;
	font-family:Bookman Old Style; 
	font-size:15px;
	text-align:center;
	font-weight: bold;
}

.address
{
	width: <?php //echo $label_width; ?>px;
	height: <?php //echo $product_all_height; ?>px;
	font-family:Bookman Old Style; 
	font-size:10px;
	text-align:center;
	font-weight: bold;
}
  
.page{

	width: <?php echo $label_width; ?>px;		
	height: <?php echo $total_height; ?>px;
	/*padding-left:0px;*/
	/*border: 1px solid red; */
}
.style12{
font-family:Bookman Old Style; 
font-size:10px;
}

@page {
  	/*size: 8in 6in;*/
 	margin: 0mm 0mm 0m 0mm;
}

body  
{ 
    /* this affects the margin on the content before sending to printer */ 
    margin: 2px;  
} 

</style>
	
	<html>
	<head></head>
	<body>
	<?php
		
	//	echo $table_id;
	
	$patient_prescription="select * from patient_prescription where id=".$table_id;		
	$patient_prescription = $this->projectmodel->get_records_from_sql($patient_prescription);			
								
	//print_r($patient_prescription);
	
	$prescription_id=$patient_prescription[0]->id;
	$prescription_date=$patient_prescription[0]->prescription_date;
	$doctor_mstr_id=$patient_prescription[0]->doctor_mstr_id;
	$patient_registration_id=$patient_prescription[0]->patient_registration_id;
	$ACTUAL_VISIT_AMT=$patient_prescription[0]->ACTUAL_VISIT_AMT;	
	$token_no=$patient_prescription[0]->token_no;
	
	$whr="id=".$doctor_mstr_id;
	$doc_name=$this->projectmodel->GetSingleVal('acc_name','acc_group_ledgers',$whr);
	$ref_table_id=$this->projectmodel->GetSingleVal('ref_table_id','acc_group_ledgers',$whr);
	
	
	$whr="id=".$ref_table_id;
	$speciality=$this->projectmodel->GetSingleVal('speciality','doctor_mstr',$whr);
	$qualification=$this->projectmodel->GetSingleVal('qualification','doctor_mstr',$whr);
	$doctor_timing=$this->projectmodel->GetSingleVal('doctor_timing','doctor_mstr',$whr);	
	$contactno_doctor=$this->projectmodel->GetSingleVal('contactno','doctor_mstr',$whr);		
	
	
	//PATIENT PORTION
	$whr="id=".$patient_registration_id;
	$party_name=$this->projectmodel->GetSingleVal('party_name','patient_registration',$whr);
	$SEX=$this->projectmodel->GetSingleVal('SEX','patient_registration',$whr);
	$DOB=$this->projectmodel->GetSingleVal('DOB','patient_registration',$whr);
	
	//AGE CALCULATION
	$from = new DateTime($DOB);
	$to   = new DateTime($prescription_date);
	$age_yr=$from->diff($to)->y;
	$age_mn=$from->diff($to)->m;
	$age_dt=$from->diff($to)->d;
	
		
	$company_id=$this->session->userdata('COMP_ID');
	$whr="id=".$company_id;
	$comp_NAME=$this->projectmodel->GetSingleVal('NAME','company_details',$whr);	
	$ADDRESS=$this->projectmodel->GetSingleVal('ADDRESS','company_details',$whr);
	$MOB_NOS=$this->projectmodel->GetSingleVal('PhoneNo','company_details',$whr);	
	$DLNO1=$this->projectmodel->GetSingleVal('DLNO1','company_details',$whr);			
	$WORKING_HOUR=$this->projectmodel->GetSingleVal('working_hours','company_details',$whr);	
	
	
	
	//lGET PREVIOUS BILL DETAILS
	
	
	
	$records = "select max(id) maxid
	from invoice_summary where patient_id=".$patient_registration_id." 
	and doctor_ledger_id=".$doctor_mstr_id ;
	$records = $this->projectmodel->get_records_from_sql($records);	
	$invoice_summary_id=$records[0]->maxid;
						
	$image_path=BASE_PATH_ADMIN.'uploads/'.'logo.png';
	
	?>
	
	
	<div  class="page"  >
							
		
		<table  style="width:100%" border="0" cellpadding="0" cellspacing="0" >		
			
				<tr >
				<td width="25%" align="left" class="patient_id">
				Patient Id : <?PHP echo $patient_registration_id; ?></td>
				<td width="50%" align="center" class="doctorname" colspan="2"><?PHP echo $doc_name; ?></td>
				<td width="25%" align="left" class="patient_id">Token No :<?PHP echo $token_no; ?> </td>	
				</tr>		
	
				<tr >
					<td align="left"  ><?PHP echo $doctor_timing; ?></td>
					<td align="center"   colspan="2"><?PHP echo $qualification.'<br>'.$speciality; ?></td>
					<td align="left"  >Date :<?PHP 
					$prescription_date=substr($prescription_date,8,2).'/'.
					substr($prescription_date,5,2).'/'.substr($prescription_date,2,2);
					echo $prescription_date; ?>
					<br>Mob:<?PHP echo $contactno_doctor; ?>
					
					
					</td>	
					
					
				</tr>	
			
			<tr><td  colspan="4"><div style="border-bottom:solid;">&nbsp;</div></td></tr>
			<tr >
				
				<td align="left" colspan="2"  >Patient  Name :<span class="patient_id"><?PHP echo $party_name; ?></span> </td>
				<td align="center"  >Age:
				<?PHP echo $age_yr.'Yrs'.' '.$age_mn.' Mnth '.$age_dt.' Days'; ?>    </td>
				<td align="right"  >Sex: <?PHP echo $SEX; ?></td>	
			</tr>	
			<tr><td  colspan="4"><div style="border-top:solid;">&nbsp;</div></td></tr>	
			
			<tr >
			<td align="left"    >&nbsp;</td>
			<td align="left"  colspan="3"   >&nbsp;&nbsp;Rx</td>
			</tr>	
			
			
		<?php  
		$product_print='Dt:'.$prescription_date.' Visit:'.$ACTUAL_VISIT_AMT;
		if($invoice_summary_id>0){
		$product_print='';
		$records = "select * from invoice_details 
		where invoice_summary_id=".$invoice_summary_id." order by id";
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $record)
		{
					
			$product_name=
			$this->projectmodel->GetSingleVal('productname','productmstr',' id='.$record->product_id);
			
			if($record->product_Synonym<>'')
			{$product_name=$record->product_Synonym;}
			$product_name=ucwords($product_name);
			$product_print=$product_print.'<br>'.$product_name;
		}}
		 ?>
		<tr>
			<td  class="style12" >&nbsp;&nbsp;<?php echo $product_print; ?></td>
			<td  class="style12" colspan="3"  >| </td>
	     </tr>
	     
				
		
		<?php   for($rc=1;$rc<=11;$rc++){ ?>
		<tr>
			<td  class="style12" >&nbsp;&nbsp; </td>
			<td  class="style12" colspan="3"  >| </td>
	     </tr>
	     <?php } ?>
		
		
		
		<tr>
		
			
			<td  class="style12"    align="left">
			  <img src="<?php echo $image_path; ?>"  height="60"/><br>
		        <span class="address"><?php echo $ADDRESS. '  D.L.NO:'.$DLNO1; ?></span><br>
		        <span class="address"><?php echo 'Mobile:'.$MOB_NOS; ?></span><br>
		        <span class="address"><?php echo 'Timing:'.$WORKING_HOUR; ?></span><br>
			  </td>
			
	    </tr>
		
		<tr>
			<td   colspan="4" align="right"  class="style12"><strong>
			Next Visit / Reporting &nbsp;&nbsp;</strong></td>
	    </tr>
		
		<tr><td  colspan="4"><div style="border-bottom:solid;">&nbsp;</div></td></tr>
		<tr>
			<td   colspan="4" align="CENTER"  class="style12"><strong>
			IN CASE OF EMERGENCY ,PLEASE CONSULT DOCTOR OR HOSPITAL</strong></td>
	    </tr>
				
		</table>
		
	</div>	
	
   
   </body>
   
   </html>