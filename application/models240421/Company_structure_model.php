<?php
class Company_structure_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
		$this->load->library('session');
    }
function create_finnancial_year_setting($prefix='ULI',$finyear='')
{	
	$sql2="select count(*) cnt from auto_invoice_mstr where finyr='".$finyear."' ";
	$rowrecord2 = $this->projectmodel->get_records_from_sql($sql2);	
	foreach ($rowrecord2 as $row2)
	{ 
		if($row2->cnt==0)
		{	
			$save_data['finyr']=$finyear; 
			$save_data['srl']=1; 
			$save_data['state']='West_Bengal'; 
			
			$save_data['statecode']=$prefix; 
			$save_data['TRANTYPE']='SELL'; 
			$this->projectmodel->save_records_model('','auto_invoice_mstr',$save_data);
			
			$save_data['statecode']=$prefix.'/CN'; 
			$save_data['TRANTYPE']='SELL_RTN'; 
			$this->projectmodel->save_records_model('','auto_invoice_mstr',$save_data);
			
			$save_data['statecode']=$prefix.'/SR'; 
			$save_data['TRANTYPE']='SAMPLE_RCV'; 
			$this->projectmodel->save_records_model('','auto_invoice_mstr',$save_data);
			
			$save_data['statecode']=$prefix.'/SI'; 
			$save_data['TRANTYPE']='SAMPLE_RCV'; 
			$this->projectmodel->save_records_model('','auto_invoice_mstr',$save_data);
			
			$save_data['statecode']=$prefix.'/TR'; 
			$save_data['TRANTYPE']='TOUR_EXP'; 
			$this->projectmodel->save_records_model('','auto_invoice_mstr',$save_data);
			
		}
		
	}
			
}
function update_teritory()
{
		$recordsHDR="select * from tbl_hierarchy_org ";
		$recordsHDR = $this->projectmodel->get_records_from_sql($recordsHDR);	
		foreach ($recordsHDR as $recordHDR)
		{
			$id=$recordHDR->id;
			$teritory='';
			$recordsDTL="select * from tbl_hierarchy_org 
			where under_tbl_hierarchy_org=".$id;
			$recordsDTL = $this->projectmodel->get_records_from_sql($recordsDTL);	
			foreach ($recordsDTL as $recordDTL)
			{$teritory=$recordDTL->id.','.$teritory;}
			$save_updte['teritory_list']=rtrim($teritory,',');
						
			$this->projectmodel->save_records_model($id,'tbl_hierarchy_org',$save_updte);
		}	
}

function UPDATE_MASTER($TRANTYPE)
{
	if($TRANTYPE=='DOCTOR')
	{		
		$recordsHDR="select * from import_doctor_master ";
		$recordsHDR = $this->projectmodel->get_records_from_sql($recordsHDR);	
		foreach ($recordsHDR as $recordHDR)
		{			
			
			$save_updte['code']=$recordHDR->SVLNO;
			$save_updte['name']=$recordHDR->DOCNAME;
			$save_updte['headq']=$recordHDR->field_id;
			$save_updte['hq_id']=$recordHDR->hq_id;
			$save_updte['doc_type']=$recordHDR->doc_type;
			$save_updte['dob']=$recordHDR->dob;
			$save_updte['dom']=$recordHDR->doa;
			$save_updte['address']='';
			$save_updte['contactno']=$recordHDR->mobno;
			$save_updte['email']=$recordHDR->mail_id;
			
			$save_updte['status']='DOCTOR';
			$save_updte['ACTIVITY_STATUS']='ACTIVE';
			//CHECKING NOT DONE
			$id='';			
			$this->projectmodel->save_records_model($id,'mr_manager_doctor',$save_updte);
		}	
	}
	
	
	if($TRANTYPE=='PRODUCT')
	{		
		$recordsHDR="select * from  import_product_master ";
		$recordsHDR = $this->projectmodel->get_records_from_sql($recordsHDR);	
		foreach ($recordsHDR as $recordHDR)
		{
			$id=$recordHDR->id;		
			$save_updte['orderno']=$recordHDR->Product_No;	
			// $save_updte['productname']=$recordHDR->Name.
			// ' ('.$recordHDR->Packing.' '.$recordHDR->Pack.')';

			$save_updte['productname']=trim($recordHDR->Name).' - '.trim($recordHDR->Potency);
			 		
			$save_updte['product_type']='FINISH';
			
			//$save_updte['Packing']=$recordHDR->Packing;	
			//$save_updte['Pack']=$recordHDR->Pack;	
			
			$save_updte['group_id']=$recordHDR->Group;
			// $Group=$recordHDR->Group;	
			// $save_updte['group_id']=$this->create_or_get_field('PRODUCT_GROUP',$Group);
			
			// $MfgBy=$recordHDR->MfgBy;	
			// $save_updte['brand_id']=$this->create_or_get_field('BRAND',$MfgBy);
			if($save_updte['productname']<>'0-0')
			{
				$this->projectmodel->save_records_model('','productmstr',$save_updte);
			}			
			
			
		}	
	}
}
function create_or_get_field($TRANTYPE='',$field_name)
{
	$field_id='';
	$save_field['name']=trim(strtoupper($field_name));
	$save_field['mstr_type']=trim(strtoupper($TRANTYPE));
	$save_field['status']='ACTIVE';
	
	$sql="SELECT * FROM misc_mstr 
	WHERE UPPER(name)='".$save_field['name']."' and
	UPPER(mstr_type)='".$save_field['mstr_type']."'"; 
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row1)
	{$field_id=$row1->id; }
		
	if($field_id=='')
	{				
	$this->projectmodel->save_records_model('','misc_mstr',$save_field);
	$field_id=$this->db->insert_id();	
	}
	
	return $field_id;	
}
function update_HQID_FIELDID($TRANTYPE)
{
	if($TRANTYPE=='DOCTOR')
	{		
		$recordsHDR="select * from import_doctor_master ";
		$recordsHDR = $this->projectmodel->get_records_from_sql($recordsHDR);	
		foreach ($recordsHDR as $recordHDR)
		{
			$id=$recordHDR->id;
			$hqid=$this->get_HQ_ID($recordHDR->HQ);
			$field_id=$this->create_or_get_field($hqid,$recordHDR->FIELD);		
			$save_updte['hq_id']=$hqid;
			$save_updte['field_id']=$field_id;
			$this->projectmodel->save_records_model($id,'import_doctor_master',$save_updte);
		}	
	}
	
	if($TRANTYPE=='RETAILER')
	{		
		$recordsHDR="select * from import_retailer_master ";
		$recordsHDR = $this->projectmodel->get_records_from_sql($recordsHDR);	
		foreach ($recordsHDR as $recordHDR)
		{
			$id=$recordHDR->id;
			$hqid=$this->get_HQ_ID($recordHDR->HQ);
			$field_id=$this->create_or_get_field($hqid,$recordHDR->FIELD);		
			$save_updte['hq_id']=$hqid;
			$save_updte['field_id']=$field_id;
			$this->projectmodel->save_records_model($id,'import_retailer_master',
			$save_updte);
		}	
	}
	
	if($TRANTYPE=='STOCKIST')
	{		
		$recordsHDR="select * from import_stockist_master ";
		$recordsHDR = $this->projectmodel->get_records_from_sql($recordsHDR);	
		foreach ($recordsHDR as $recordHDR)
		{
			$id=$recordHDR->id;
			$hqid=$this->get_HQ_ID($recordHDR->HQ);
			$field_id=$this->create_or_get_field($hqid,$recordHDR->FIELD);		
			$save_updte['hq_id']=$hqid;
			$save_updte['field_id']=$field_id;
			$this->projectmodel->save_records_model($id,'import_stockist_master',
			$save_updte);
		}	
	}
	
	
	
}
function get_HQ_ID($HQ_name)
{
	$HQ_id=0;
	$sql="SELECT * FROM `tbl_hierarchy_org` 
	WHERE `tbl_designation_id`=5 and 
	UPPER(hierarchy_name)='".trim(strtoupper($HQ_name))."'"; 
	$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
	foreach ($rowrecord as $row1)
	{$HQ_id=$row1->id; }
	return $HQ_id;
	
}
function delete_invalid_location($hqid='')
{		
		if($hqid<>'')
		{
		$sql="SELECT * FROM `tbl_hierarchy_org` 
		WHERE `tbl_designation_id`=6 and 
		under_tbl_hierarchy_org=".$hqid." "; //FOR HQ LIST
		}
		else
		{
		$sql="SELECT * FROM `tbl_hierarchy_org` 
		WHERE `tbl_designation_id`=6 "; //FOR HQ LIST
		}		
		
		$rowrecord = $this->projectmodel->get_records_from_sql($sql);	
		foreach ($rowrecord as $row1)
		{
		$field_id=$row1->id;
		
		$stockist_cnt=0;
		$sql_chk="SELECT count(*) cnt FROM stockist_hq_map where
		tbl_hierarchy_org_id=".$field_id." "; 
		$rowrecord_chk = $this->projectmodel->get_records_from_sql($sql_chk);	
		foreach ($rowrecord_chk as $row_chk)
		{$stockist_cnt=$row_chk->cnt;}		
		
		$doctor_cnt=0;
		$sql_chk="SELECT count(*) cnt FROM mr_manager_doctor where
		headq=".$field_id." "; 
		$rowrecord_chk = $this->projectmodel->get_records_from_sql($sql_chk);	
		foreach ($rowrecord_chk as $row_chk)
		{$doctor_cnt=$row_chk->cnt;}		
		
		$retailer_cnt=0;
		$sql_chk="SELECT count(*) cnt FROM retailer where
		retail_field=".$field_id." "; 
		$rowrecord_chk = $this->projectmodel->get_records_from_sql($sql_chk);	
		foreach ($rowrecord_chk as $row_chk)
		{$retailer_cnt=$row_chk->cnt;}		
		
		if($stockist_cnt==0 and  $doctor_cnt==0 and  $retailer_cnt==0)
		{ 
			 $query="delete from tbl_hierarchy_org where id=".$field_id." ";
			 $this->db->query($query);
		}
		
	}
		
}
	
	
	public function get_all_record($table_name,$where_array)
	{
		$res=$this->db->get_where($table_name,$where_array);
		return $res->result();
	}
	
	public function get_records_from_sql($sql)
	{
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	
	public function get_single_record($table_name,$id)
	{
		$sql = "SELECT * FROM ".$table_name." WHERE id=".$id;
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	public function save_records_model($id,$table_name,$tabale_data)
	{
		if($id>0)
		{$this->db->update($table_name, $tabale_data, array('id' => $id));}
		else
		{$this->db->insert($table_name,$tabale_data);}	
	}
	
	public function delete_record($id=0,$table_name)
	{$this->db->delete($table_name,array('id'=>$id));}
	
	
	
}
?>