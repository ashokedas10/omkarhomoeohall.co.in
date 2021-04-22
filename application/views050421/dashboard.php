<link href='http://fonts.googleapis.com/css?family=Pacifico' 
rel='stylesheet' type='text/css'>

<style type="text/css">
.style2 {font-size: 16px}
.style3 {color: #FF6600}
.style5 {color: #FF6600; font-size: 18px; }
</style>	
<script language="javascript" type="text/javascript">
		
		function print_result() {
		
		//var visitdate =document.getElementById('trandate').value;
		//var hqid =document.getElementById('hq_id').value;
		//alert('tt');
		var base_url = '<?php echo ADMIN_BASE_URL.'project_controller/daily_call_reports/';  ?>';
			url=base_url;
			newwindow=window.open(url,'name','height=600,width=800');
			if (window.focus) {newwindow.focus()}
			return false;
		}
</script>
<h2 align="center">Dash Board</h2>


<?php 

$login_status=$this->session->userdata('login_status');
$COMP_ID=$this->session->userdata('COMP_ID');
if ($COMP_ID=='UNITEDLAB' and $login_status=='USER' ) 
{ ?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$("#myModal").modal('show');
	});
</script>

<?php 
$org_id=0;
$fields='-1';	
$emp_id=$this->session->userdata('login_emp_id');
$sql="select * from tbl_hierarchy_org where  employee_id=".$emp_id;
$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
foreach ($rowrecord as $row1)
{$org_id=$row1->id;}

$sql="select * from tbl_organisation_chain where  child_desig_srl=6 and parentuid=".$org_id;
$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
foreach ($rowrecord as $row1)
{$fields=$fields.','.$row1->childuid;}
		

?>


<div id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Recent DOB And DOM of Doctors 
				</h4>
            </div>
            <div class="modal-body">
			
			<table width="100%" border="1">
			  <tr style="background-color:#CC0033">
				<td>Name</td>
				<td>DOB(MM-DD)</td>
			  </tr>
		  <?php 
			
			$sql="select * from mr_manager_doctor where  headq in (".$fields.") 
			order by dob desc";
			$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
			foreach ($rowrecord as $row1)
			{
			$name=$row1->name.'('.$row1->code.')';
			$dob=date('Y').'-'.substr($row1->dob,5,5);
			
			$date1=date_create(date('Y-m-d'));
			$date2=date_create($dob);
			$dateDifference=date_diff($date1,$date2);
			$dobdiff=$dateDifference->format("%R%a");
			if($dobdiff>-5 && $dobdiff<=15){
		?>
			  <tr>
				<td><?php echo $name; ?></td>
				<td><?php echo substr($row1->dob,5,5); ?></td>
			  </tr>
		<?php }} ?>	 
			</table>
			
			<table width="100%" border="1">
			  <tr  style="background-color:#00CC66">
				<td>Name</td>
				<td>DOM(MM-DD)</td>
			  </tr>
			  <?php 
			
			$sql="select * from mr_manager_doctor where  headq in (".$fields.") 
			order by dob desc";
			$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
			foreach ($rowrecord as $row1)
			{
			$name=$row1->name.'('.$row1->code.')';
			$dom=date('Y').'-'.substr($row1->dom,5,5);
			
			$date1=date_create(date('Y-m-d'));
			$date2=date_create($dom);
			$dateDifference=date_diff($date1,$date2);
			$dobdiff=$dateDifference->format("%R%a");
			if($dobdiff>-5 && $dobdiff<=15){
		?>
			  <tr>
				<td><?php echo $name; ?></td>
				<td><?php echo substr($row1->dom,5,5); ?></td>
			  </tr>
		<?php }} ?>	 
			</table>
				
            </div>
        </div>
    </div>
</div>

<table width="100%">

<tr><td align="center" colspan="3"><p>&nbsp;</p></td></tr>
<tr><td height="14" colspan="2"></td></tr>
<?php 
//echo $startingdate;
//$emp_id=$this->session->userdata('login_emp_id');
$emp_id='758,714,718';
$msg='';
$display_result='YES';
$emp_records="select * from tbl_employee_mstr where	id in (".$emp_id.") ";
$emp_records = $this->projectmodel->get_records_from_sql($emp_records);	
foreach ($emp_records as $emp_record)
{
	$empname=$emp_record->name;
	$empid=$emp_record->id;
	$result=0;
	$sql="select count(*) cnt from question_answer_result where	
	result=emp_selected and emp_id=".$empid;
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row1)
	{$result=$row1->cnt;}

	$msg=$msg.$empname.' Score:'.$result.'<br>';

}

if($display_result=='YES')
{
?>
<tr>
<td colspan="2" align="center">
<p><span class="style5">Winners are <br />
  <?php echo $msg;?></span></p>
<p>&nbsp;</p>
</td>
</tr>	
<tr>
	<td colspan="2" align="center">
	<?php 
	$image1='congratulation.gif';
	$image2='trofee.gif';
	$image_path=BASE_PATH_ADMIN.'uploads/notice/';
	?>
	<a href="" target="_blank"><img src="<?php echo $image_path.$image1; ?>"  
	 width="50%"/></a>    </p>
	  <p><br />
		  <a href="" target="_blank"><img src="<?php echo $image_path.$image2; ?>"  
	  width="50%"/></a>
	</td>
</tr>
<?PHP } ?>

</table>

<?php } ?>

<table width="100%">
<tr><td align="center" colspan="3"><p>&nbsp;</p></td></tr>
<tr><td colspan="2"  align="center" class="srscell-body"><strong>Notice Board</strong></td>
</tr>
<tr><td height="14" colspan="2"></td></tr>
<?php 
$sql="select * from tbl_holiday where	
exp_date>='".date('Y-m-d')."' and status='NOTICE' order by holiday desc";
$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
foreach ($rowrecord as $row1)
{
?>
<tr>
<td colspan="2"><strong>DATE:&nbsp;<?php echo $row1->holiday;?></strong></td>
</tr>	
	
<tr>
<td  colspan="2"><p class="style2"><?php echo $row1->comment;?></p>
  <p class="style2">&nbsp;</p></td>
</tr>

<TR>

<td colspan="2" align="center">
<?php 
//$image_path='uploads/notice/';
$image_path=BASE_PATH_ADMIN.'uploads/notice/';
if($row1->image1<>''){
?>
<a href="<?php echo $image_path.$row1->image1; ?>" target="_blank"><img src="<?php echo $image_path.$row1->image1; ?>"   width="100%"/></a>
<?php } ?>
</td>

</TR>
<tr><td class="srscell-head-lft" colspan="3"></td>
</tr>
<?php }?>
<tr>
<td  class="srscell-body" align="center" colspan="3">
  <p>** <strong>End of Notice</strong> **</p>
 </td>
</tr>


<tr>
<td   width="21%" height="2"></td>
<td   width="79%"></td>
</tr>
</table>

<?php if ($COMP_ID=='UNITEDLAB' and $login_status=='USER' ) { ?>

<form action="<?php echo ADMIN_BASE_URL?>project_controller/dashboard/" 
name="frmreport" id="frmreport" method="post">
<table class="srstable">
<tr><td colspan="5"  align="center" class="srscell-body"><strong>Activity Report</strong></td>
</tr>

 <tr>
          <td class="srscell-head-lft">From Date</td>
          <td class="srscell-head-lft">
		  <input type="text" id="startingdate" class="srs-txt-small"
			  value="<?php  if($startingdate=='') 
			  { echo date('Y-m-d');} else { echo $startingdate; } ?>" 
			  name="startingdate"/>
<img src="<?php echo BASE_PATH_ADMIN;?>theme_files/calender_final/images2/cal.gif" 
onclick="javascript:NewCssCal ('startingdate','yyyyMMdd')" 
style="cursor:pointer"/>
	</td>
	   <td class="srscell-head-lft">End Date</td>
          <td class="srscell-head-lft">
		  <input type="text" id="closingdate" class="srs-txt-small"
			  value="<?php  if($closingdate=='') 
			  { echo date('Y-m-d');} else { echo $closingdate; } ?>" 
			  name="closingdate"/>
<img src="<?php echo BASE_PATH_ADMIN;?>theme_files/calender_final/images2/cal.gif" 
onclick="javascript:NewCssCal ('closingdate','yyyyMMdd')" 
style="cursor:pointer"/>
		
		</td>
		 <td class="srscell-head-lft">
		<input name="submit" type="submit" value="Submit" 
		class="btn srs-btn-reset"/>
       </td>
	   
        </tr>



<?php 

$date1=date_create($startingdate);
$date2=date_create($closingdate);
$dateDifference=date_diff($date1,$date2);
$dateDifference=$dateDifference->format("%a");
if($dateDifference==0){$dateDifference=1;}

$desig='';
$org_structure_ids='-1';	
$emp_id=$this->session->userdata('login_emp_id');
$sql="select * from tbl_employee_mstr where	id=".$emp_id;
$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
foreach ($rowrecord as $row1)
{
	$tbl_designation_id=$row1->tbl_designation_id;
	$emp_name=$row1->name;
	$org_id=0;
	$sql="select * from tbl_hierarchy_org where  employee_id=".$emp_id;
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row1)
	{$org_id=$row1->id;}
	
	
	if($tbl_designation_id==5){$desig='MR';$org_structure_ids=$org_id;}
	
	if($tbl_designation_id==4)
	{ 
		$desig='ASM';
		$sql="select * from tbl_organisation_chain where  child_desig_srl=5 and 
		  parentuid=".$org_id;
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{$org_structure_ids=$org_structure_ids.','.$row1->childuid;}
	}
		
}

?>
<tr>
<td class="srscell-head-lft" colspan="5">
<strong>Name :&nbsp;<?php echo $emp_name.'('.$desig.')    | No of Days :'.$dateDifference;?></strong></td>
</tr>	
</table>	
</form>

<table  class="srstable" style="width:100%" >
					
		<tr>
			<th width="3%" class="srscell-head-lft">Sl</th>
			<th width="9%" class="srscell-head-lft">Name of MR</th>
			<th width="7%" align="left" class="srscell-head-lft">HQ Name</th>
			<th width="10%" class="srscell-head-lft">Total Doctors</th>
			<th width="3%" class="srscell-head-lft">SC</th>
			<th width="3%" align="left" class="srscell-head-lft">C</th>
			<th width="4%" align="left" class="srscell-head-lft">NC</th>
			<th width="10%" align="left" class="srscell-head-lft">Tot Chemist</th>
			<th width="5%" align="left" class="srscell-head-lft">POB</th>
			<th width="10%" align="left" class="srscell-head-lft">No of Activities</th>
			<th width="10%" align="left" class="srscell-head-lft">Doctor Call Average</th>
			<th width="13%" align="left" class="srscell-head-lft">Chemist Call Average</th>
			<th width="13%" align="left" class="srscell-head-lft">Detail</th>
		</tr>
	<?php 
		//$startingdate='2017-09-01';
		//$closingdate='2017-10-31';
	
		
		//echo $dateDifference=date_diff($startingdate,$closingdate);
		/*$date1=date_create("2013-03-15");
		$date2=date_create("2013-12-12");
		$diff=date_diff($date1,$date2);
		echo $diff->format("%R%a days");*/
		$i=1;
		$records="select * from tbl_hierarchy_org where	id in (".$org_structure_ids.") ";
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $record)
		{	
		
		$hq_id=$record->id;
		$employee_id=$record->employee_id;
		
		$employees="select * from tbl_employee_mstr where	id=".$employee_id;
		$employees = $this->projectmodel->get_records_from_sql($employees);	
		foreach ($employees as $employee)
		{$employee_name=$employee->name;}
		
		$TOTSC=$TOTC=$TOTNC=$totretailer=$totdoctors=
		$totvisit=$DoctorCallAvg=$totretailervisit=$RetailerCallAvg=$total_pob_amt=0;
		
		$fields='-1';
		$rowfields="select * from tbl_hierarchy_org where 
		under_tbl_hierarchy_org=".$hq_id;
		$rowfields = $this->projectmodel->get_records_from_sql($rowfields);	
		foreach ($rowfields as $rowfield)
		{ $fields=$fields.','.$rowfield->id;}
		
		
		//TOTAL DOCTORS
		$rowfields="select count(*) totdoctors from mr_manager_doctor where 
		headq in (".$fields.") ";
		$rowfields = $this->projectmodel->get_records_from_sql($rowfields);	
		foreach ($rowfields as $rowfield)
		{ $totdoctors=$rowfield->totdoctors;}
		
		$rowfields="select count(*) TOTSC from mr_manager_doctor where 
		headq in (".$fields.") AND doc_type='SC'";
		$rowfields = $this->projectmodel->get_records_from_sql($rowfields);	
		foreach ($rowfields as $rowfield)
		{ $TOTSC=$rowfield->TOTSC;}
		
		$rowfields="select count(*) TOTC from mr_manager_doctor where 
		headq in (".$fields.") AND doc_type='C'";
		$rowfields = $this->projectmodel->get_records_from_sql($rowfields);	
		foreach ($rowfields as $rowfield)
		{ $TOTC=$rowfield->TOTC;}
		
		$rowfields="select count(*) TOTNC from mr_manager_doctor where 
		headq in (".$fields.") AND doc_type='NC'";
		$rowfields = $this->projectmodel->get_records_from_sql($rowfields);	
		foreach ($rowfields as $rowfield)
		{ $TOTNC=$rowfield->TOTNC;}
		
		//TOTAL CHEMIST
		$rowfields="select count(*) totretailer from retailer where 
		 retail_field in (".$fields.") ";
		$rowfields = $this->projectmodel->get_records_from_sql($rowfields);	
		foreach ($rowfields as $rowfield)
		{ $totretailer=$rowfield->totretailer;}
		
		
		//TOTAL DOCTOR VISIT
		$sql="select count(*) docvisitcnt from visit_details where	
		hq_id=".$hq_id." 
		and trandate between '$startingdate' and '$closingdate' 
		and status='DOCTOR' " ;
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{$totvisit=$row1->docvisitcnt;}	
		
		$DoctorCallAvg=round($totvisit/$dateDifference,2);
		
		
		
		//TOTAL RETAILER VISIT
		$totretailervisit=0;
		$sql="select count(*) docvisitcnt from visit_details where	
		hq_id=".$hq_id." 
		and trandate between '$startingdate' and '$closingdate' 
		and status='RETAILER' " ;
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{$totretailervisit=$row1->docvisitcnt;}	
		
		$RetailerCallAvg=round($totretailervisit/$dateDifference,2);					
		
		//RETAILER POB
		$sql="select sum(a.total_val) tot_pob_val 
		from visit_brand_gift a ,visit_details b 
		where a.visit_details_id=b.id and  b.hq_id=".$hq_id." 
		and b.trandate between '$startingdate' and '$closingdate' 
		and b.status='RETAILER' " ;
										
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);
		foreach ($rowrecord as $row1)
		{$total_pob_amt=$row1->tot_pob_val;}
		
		if($totdoctors>0){
	 ?>
	 
	 <tr>
			<td class="srscell-body"><?php echo $i; ?></td>
			<td class="srscell-body"><?php echo $employee_name; ?></th>
			<td class="srscell-body" align="left"><?php echo $record->hierarchy_name; ?>
			</td>
			<td class="srscell-body"><?php echo $totdoctors; ?></td>
			<td class="srscell-body"><?php echo $TOTSC; ?></td>
			<td class="srscell-body" align="left"><?php echo $TOTC; ?></td>
			<td class="srscell-body" align="left"><?php echo $TOTNC; ?></td>
			<td class="srscell-body" align="left"><?php echo $totretailer; ?></td>
			<td class="srscell-body" align="left"><?php echo $total_pob_amt; ?></td>
			<td class="srscell-body" align="left">
			<?php echo $totvisit+$totretailervisit; ?></td>
			<td class="srscell-body" align="left"><?php echo $DoctorCallAvg.
			'('.$totvisit.'/'.$dateDifference.')'; ?></td>
			<td class="srscell-body" align="left"><?php echo $RetailerCallAvg.
			'('.$totretailervisit.'/'.$dateDifference.')'; ?></td>
	   <td class="srscell-body" align="left">
			
			<input name="Print" type="button" value="Details" 
			class="btn btn-green" onclick="print_result()" /></td>
		</tr>
	 
	 
	 	
					
	<?php $i=$i+1;}} ?>
				
				
   </table>
   
<?php } ?>