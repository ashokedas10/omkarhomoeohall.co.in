<div class="panel panel-primary"  style="background-color:#E67753">
	  <div class="panel-body" align="center">
		<h3><span class="label label-default">
		<?php echo $FormRptName; ?>
	   <?php if($NEWENTRYBUTTON=='YES'){ ?>
  <a href="<?php echo $tran_link;?>list/"><button type="button" class="btn btn-success">New Entry</button></a>
   <?php } ?></span></h3>
	  </div>
</div>
		
<form id="frm" name="frm" method="post" action="<?php echo $tran_link;?>save/" >
<input type="hidden" name="id" id="id" value="<?php echo $id; ?>"  />
<div class="panel panel-primary"  style="background-color:#E67753">
 <div class="panel-footer">
 <div class="form-row"> 
<?php    
	
		$SectionPRE='';
		$rownoPRE=1;
			
		$records="select * from frmrpttemplatedetails 
		where frmrpttemplatehdrID=".$frmrpttemplatehdrID." 
		and SectionType='HEADER' order by Section,FieldOrder ";
		$records = $this->projectmodel->get_records_from_sql($records);	
		foreach ($records as $record)
		{
			//RELATED TO ROW DIV CLASS
			$SectionType=$record->SectionType; //HEADER,...
			$Section=$record->Section;
			$FieldOrder=$record->FieldOrder;
			$DIVClass=$record->DIVClass; // col size
			$LabelName=$record->LabelName;
			
			//RELATED TO FIELD
			$InputName=$record->InputName;
			$InputType=$record->InputType;
			$Inputvalue=$record->Inputvalue;
			$tran_table_name=$record->tran_table_name;
			$RecordSet=$record->RecordSet;
			$MainTable=$record->MainTable;
			$LinkField=$record->LinkField;
			$LogoType=$record->LogoType;
			
			//RELATED TO LINK TABLE OF THE FIELD
			//DYNAMIK RECORD SET ...USEFL FOR DROPDOWN LIST 
			$datafields=$record->datafields;
			$table_name=$record->table_name;
			$where_condition=$record->where_condition;
			$orderby=$record->orderby;
			
			if($datafields<>'')
			{ 
			
			$RecordSet=$this->projectmodel->get_records_from_sql($datafields);}
			
				if($id>0)
				{
				 $data['DataFields']=$InputName;
				 $data['TableName']=$tran_table_name;
				 $data['WhereCondition']=" id=".$id;
				 $data['OrderBy']='';
				 $datavalue=$this->projectmodel->Activequery($data,'LIST');
				 foreach($datavalue as $key=>$bd){
				 foreach($bd as $key1=>$bdr){
				 $Inputvalue=$bdr;
				 
				 }}
			}
									
			$InputName=
			$this->projectmodel->create_field($InputType,$LogoType,
			$LabelName,$InputName,$Inputvalue,$RecordSet);
	  ?>
<?php   if($SectionType=='HEADER'){ ?>	
			
<div class="form-group col-md-<?php echo $DIVClass; ?>">
<?php echo $InputName; ?>
</div>
<?php } ?>
<?php }?>
<div class="panel-body" align="center">
		
  </div>  
  <div class="panel panel-primary"  style="background-color:#4caf50">
  
   <div class="panel-body" align="center">
		<button type="submit" class="btn btn-primary" id="Save" name="Save">Save</button>
  </div>
  </div>
	
</div></div></div>
</form>
<!--LIST VIEW-->
<?php if($DisplayGrid=='YES'){ ?>
<div  style="overflow:scroll">
<table  id="example1" class="table table-bordered table-striped">
	    <thead>
	        <tr>
			<?php 
			//print_r($header);
			//echo $header[1];
			foreach($GridHeader as $key=>$hdr){
			 $cn_values =explode("-", trim($hdr));			
			 ?>
	            <td  align="<?php echo $cn_values[1]; ?>"><?php echo $cn_values[0]; ?></td>
	        <?php } ?>  
			<td  align="left">Action</td> 
            </tr>
        </thead>
       
	   <tbody>
			<?php foreach($body as $key=>$bd){$column=0;?>
			<tr>
				<?php foreach($bd as $key1=>$bdr)
				{ 
					$align=$GridHeader[$column];
					$align =explode("-", trim($align));	
					$column=$column+1;
					if($key1=='id'){$id=$bdr;}
					
					//FOR EMPLOYEE MASTER
					if($frmrpttemplatehdrID==10 && $key1=='tbl_designation_id')
					{
						$sql="select * from tbl_designation 
						where  	srlno=".$bdr;
						$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
						foreach ($rowrecord as $row1)
						{$bdr=$row1->designation_name;}
					}
					
					//FOR STOCKIST MASTER
					if($frmrpttemplatehdrID==13 && $key1=='retail_field')
					{
						$location='';
						$sql="select * from tbl_hierarchy_org 	where  	id in (".$bdr.") ";
						$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
						foreach ($rowrecord as $row1)
						{
						$marketname=$row1->hierarchy_name;
						$parentid=$row1->under_tbl_hierarchy_org;
						$Whr=' id='.$parentid;
						$parenthq=$this->projectmodel->GetSingleVal(
						'hierarchy_name','tbl_hierarchy_org',$Whr);
						
						$locHq=$marketname.'('.$parenthq.')';						
						$location=$location.','.$locHq;
						}
						$bdr=substr($location,1);
					}
					
						//FOR RETAIL MASTER
					if($frmrpttemplatehdrID==12 && $key1=='retail_field')
					{
						$location='';
						$sql="select * from tbl_hierarchy_org 	where  	id in (".$bdr.") ";
						$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
						foreach ($rowrecord as $row1)
						{
						$marketname=$row1->hierarchy_name;
						$parentid=$row1->under_tbl_hierarchy_org;
						
						$Whr=' id='.$parentid;
						$parenthq=$this->projectmodel->GetSingleVal(
						'hierarchy_name','tbl_hierarchy_org',$Whr);
						
						$locHq=$marketname.'('.$parenthq.')';							
						$location=$location.','.$locHq;
						}
						$bdr=substr($location,1);
					}
					
					
					if($frmrpttemplatehdrID==22 && $key1=='hq_id')
					{
						$location='';
						$sql="select * from tbl_hierarchy_org 	where  	id in (".$bdr.") ";
						$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
						foreach ($rowrecord as $row1)
						{$bdr=$row1->hierarchy_name;}						
					}
					
					if(($frmrpttemplatehdrID==21 or $frmrpttemplatehdrID==22 or $frmrpttemplatehdrID==26) && $key1=='parent_id')
					{
						if($bdr>0)
						{
							$Whr=' id='.$bdr;
							$bdr=$this->projectmodel->GetSingleVal(
							'acc_name','acc_group_ledgers',$Whr);
						}
					}
					
					
					if(($frmrpttemplatehdrID==38 or $frmrpttemplatehdrID==39 or 
					$frmrpttemplatehdrID==40) && $key1=='parent_id')
					{
						if($bdr>0)
						{
							$Whr=' id='.$bdr;
							$bdr=$this->projectmodel->GetSingleVal('FieldVal','frmrptgeneralmaster',$Whr);
						}
					}
					
					if($frmrpttemplatehdrID==44  && $key1=='company_id')
					{
						$location='';
						$sql="select * from company_details 	where  	id=".$bdr;
						$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
						foreach ($rowrecord as $row1)
						{$bdr=$row1->NAME;}						
					}
					
					if($frmrpttemplatehdrID==54  && $key1=='company_id')
					{
						$location='';
						$sql="select * from company_details 	where  	id=".$bdr;
						$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
						foreach ($rowrecord as $row1)
						{$bdr=$row1->NAME;}						
					}
					
					if($frmrpttemplatehdrID==31  && $key1=='printer_computer_id')
					{
						$location='';
						$sql="select * from printer_setup 	where  	id=".$bdr;
						$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
						foreach ($rowrecord as $row1)
						{$bdr=$row1->computer_name;}						
					}
					
					
				?>
				<td align="<?php echo $align[1]; ?>"><?php echo $bdr; ?></td>
				<?php } ?>
				
				<td  align="left">
				<a href="<?php 	echo $tran_link.'addeditview/'.$id; ?>">
				<button class="btn-block btn-info">Edit</button></a>
				</td> 
			</tr>
			<?php } ?>	
	 </tbody>
</table>   
</div>
<?php } ?>  
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  
     $("#month_year_date").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#month_year_date").change(function() {
	 var  trandate = $('#month_year_date').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#month_year_date").val(trandate);
	});
	
     $("#fromdate").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#fromdate").change(function() {
	 var  trandate = $('#fromdate').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#fromdate").val(trandate);
	});
	
	 $("#todate").datepicker({
      changeMonth: true,
      changeYear: true
    });
 	
	$("#todate").change(function() {
	 var  trandate = $('#todate').val();
	 trandate=
	 trandate.substring(6, 10)+'-'+
	 trandate.substring(0, 2)+'-'+
	 trandate.substring(3, 5);
	 $("#todate").val(trandate);
	});
	
  </script>