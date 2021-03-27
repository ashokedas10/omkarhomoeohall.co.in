<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

 
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://rawgit.com/RobinHerbots/Inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


<script type="text/javascript">

$(".modal-wide").on("show.bs.modal", function() {
  var height = $(window).height() - 200;
  $(this).find(".modal-body").css("max-height", height);
});


</script>
<style>

div.ex1 {
  background-color: lightblue;
  width: 100%;
  height: 200px;
  overflow: scroll;
}



.activeTR {  
	background-color: yellow;
    color:black;	
    font-weight:bold;
}

</style>
<style type="text/css">

.modal.modal-wide .modal-dialog {
  width: 90%;
}
.modal-wide .modal-body {
  overflow-y: auto;
}

/* irrelevant styling */

body p { 
  max-width: 400px; 
  margin: 20px auto; 
  margin-bottom: 400px
}
#tallModal .modal-body p { margin-bottom: 900px }

</style>
<style>
.activeTR {  
	background-color: yellow;
    color:black;	
    font-weight:bold;
}

</style>

<style>
input {
  font-family: monospace;
}
label {
  display: block;
}
div {
  margin: 0 0 1rem 0;
}

.shell {
  position: relative;
  line-height: 1; }
  .shell span {
    position: absolute;
    left: 3px;
    top: 1px;
    color: #ccc;
    pointer-events: none;
    z-index: -1; }
    .shell span i {
      font-style: normal;
      /* any of these 3 will work */
      color: transparent;
      opacity: 0;
      visibility: hidden; }

input.masked,
.shell span {
  font-size: 16px;
  font-family: monospace;
  padding-right: 10px;
  background-color: transparent;
  text-transform: uppercase; }
  
  
 #search_table{ 
  list-style: none; 
  border    : 1px solid silver; 
  max-height:20px;
  overflow  : auto;
}


</style>


<style type="text/css">
    .scroll-div {
      height: 300px;
      overflow: scroll;
      /*margin-top: 50px;*/
    }
    .anchor {
      border: 2px dashed red;
      padding: 10px 10px 200px 10px;
    }
    .my-fixed-header {
      background-color: rgba(0, 0, 0, 0.2);
      height: 50px;
      position: fixed;
      top: 0; left: 0; right: 0;
    }
    .my-fixed-header > a {
      display: inline-block;
      margin: 5px 15px;
    }
  </style>

<script type="text/javascript">

$(".modal-wide").on("show.bs.modal", function() {
  var height = $(window).height() - 200;
  $(this).find(".modal-body").css("max-height", height);
});
</script>
<style>
.activeTR {  
	background-color: yellow;
    color:black;	
    font-weight:bold;
}

</style>
<style type="text/css">

.modal.modal-wide .modal-dialog {
  width: 400px;
  height:200px;
}
.modal-wide .modal-body {
  overflow-y: auto;
}

/* irrelevant styling */

body p { 
  max-width: 400px; 
  margin: 20px auto; 
  margin-bottom: 400px
}
#tallModal .modal-body p { margin-bottom: 900px }
.style3 {font-size: 20px}

.nopadding {
   padding: 0 !important;
   margin: 0 !important;
}	
.input_field_height
{
height:27px;
font-family:Arial, Helvetica, sans-serif bold;
font-size:15px;
color:#000000;
font-weight:200;
}
.style4 {
	color: #FFFFFF;
	font-size: 20px;
}
</style>


<script type = "text/javascript">
/*Final Submit(F8) | New Mixer(F9) | Print Invoice(F10) | Print POS(F11) | New Entry (F1) */
 function shortcut() {		 
		 
		 document.addEventListener("keydown", function(event) {
		 
		 	//alert(event.keyCode);
			if(event.keyCode==27)
			{
			
				//angular.element(document.getElementById('myBody')).scope().bill_process_functions();
			
			/*	var answer1 = window.confirm("Save Bill?");
				if (answer1) 
				{angular.element(document.getElementById('myBody')).scope().final_submit();}
				
				var answer2 = window.confirm("Print Label?");
				if (answer2) 
				{angular.element(document.getElementById('myBody')).scope().print_label();}		*/
					
			}		
		
			
		
			//(F10) -PRINT BILL IN POS
			if(event.keyCode==120)
			{angular.element(document.getElementById('myBody')).scope().print_documents('POS_INVOICE');}
			
			//(F11) -NEW ENTRY
			if(event.keyCode==121) 
			{
			angular.element(document.getElementById('myBody')).scope().new_entry();
			document.getElementById(4).focus();
			}
			
	
			
		  
		});
          
		
			
         }
</script>  


<div ng-app="Accounts"   >

<div ng-controller="main_transaction_controller" class="panel panel-primary" id="myBody" onchange = "shortcut()" >
																
	<table class="table table-bordered table-striped" >
	
			
	<tr>		
		
		<td  align="center" colspan="2"  class="activeTR">
		<span class="style3">
		<button type="button" class="btn btn-danger" ng-click="test()">LOAD PRESCRIPTION</button>
		
		
		<!--{{main_grid_array}}-->
		
		</span>	
		</td> 
		
		<td  align="center" colspan="2" style="background-color:#CC6633">
		<span class="style4"><strong>{{server_msg}}<strong></span>	<br />
		
		<span class="style4"><strong >{{FormInputArray[0]["header"][1]['fields'][0]['product_id']['validation_msg']}}
		<strong></span><br />

		<span class="style4"><strong>Token No :{{token_no }} | Patient  Id :{{id_header }}
		<strong></span>	
		
		</td> 
		
		<td  align="left" colspan="2" class="activeTR"><strong>Shortcut Keys</strong> :
		Bill Print (<strong>F9</strong>) |  New Entry(<strong>F10</strong>)  
		</td> 	
	</tr>	


<tr>
	<td  align="left">{{get_field_name(0,'LabelName','prescription_date')}}</td> 
	<td  align="left">{{get_field_name(0,'LabelName','token_shift')}}</td>
</tr>
<tr>
			
<td>		

<input id="{{FormInputArray[0]['header'][0]['fields'][0]['prescription_date']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['prescription_date']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['prescription_date']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['prescription_date']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['prescription_date']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['prescription_date']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,
FormInputArray[0]['header'][0]['fields'][0]['prescription_date']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"   />

</td> 


<td>		

<input id="{{FormInputArray[0]['header'][0]['fields'][0]['token_shift']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['token_shift']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['token_shift']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['token_shift']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['token_shift']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['token_shift']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['token_shift']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"   />

</td> 

</tr>


<tr>
		<td  align="left">{{get_field_name(0,'LabelName','party_name')}}</td> 
		<td  align="left" >{{get_field_name(0,'LabelName','SEX')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','PATIENT_TYPE')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','mobno')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','emailid')}}</td> 
		
</tr>


<tr>
			
<td>		

<input id="{{FormInputArray[0]['header'][0]['fields'][0]['party_name']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['party_name']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['party_name']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['party_name']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['party_name']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['party_name']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,
FormInputArray[0]['header'][0]['fields'][0]['party_name']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"   />

</td> 
				
<td >		

<input id="{{FormInputArray[0]['header'][0]['fields'][0]['SEX']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['SEX']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['SEX']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['SEX']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['SEX']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['SEX']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['SEX']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />
</td>
	
				
<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['PATIENT_TYPE']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['PATIENT_TYPE']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['PATIENT_TYPE']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['PATIENT_TYPE']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['PATIENT_TYPE']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['PATIENT_TYPE']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['PATIENT_TYPE']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  


<td>				
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['mobno']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['mobno']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['mobno']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['mobno']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['mobno']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['mobno']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,
FormInputArray[0]['header'][0]['fields'][0]['mobno']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  

<td>				
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['emailid']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['emailid']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['emailid']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['emailid']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['emailid']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['emailid']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,
FormInputArray[0]['header'][0]['fields'][0]['emailid']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  
				
</tr>

<tr>
		<td  align="left">{{get_field_name(0,'LabelName','address')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','Address2')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','REGDATE')}}</td> 
		<td  align="left" >{{get_field_name(0,'LabelName','age_yy')}}</td> 
		<td  align="left" >{{get_field_name(0,'LabelName','age_mm')}}</td> 
		<td  align="left" >{{get_field_name(0,'LabelName','DOB')}}</td> 
		
</tr>


<tr>
			
<td >		

<input id="{{FormInputArray[0]['header'][0]['fields'][0]['address']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['address']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['address']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['address']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['address']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['address']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['address']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />
</td>
	
				
<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['Address2']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['Address2']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['Address2']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['Address2']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['Address2']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['Address2']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['Address2']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  

<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['REGDATE']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['REGDATE']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['REGDATE']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['REGDATE']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['REGDATE']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['REGDATE']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['REGDATE']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  


<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['age_yy']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['age_yy']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['age_yy']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['age_yy']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['age_yy']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['age_yy']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['age_yy']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  


<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['age_mm']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['age_mm']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['age_mm']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['age_mm']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['age_mm']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['age_mm']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['age_mm']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  

<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['DOB']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['DOB']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['DOB']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['DOB']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['DOB']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['DOB']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['DOB']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  



</tr>


<tr>
		<td  align="left">{{get_field_name(0,'LabelName','agent_id')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','doctor_mstr_id')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','token_status')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','VISIT_1')}}</td> 
		<td  align="left">{{get_field_name(0,'LabelName','NEXT_VISIT')}}</td> 
		<td  align="left" >{{get_field_name(0,'LabelName','GAP_DAYS')}}</td> 
		<!--<td  align="left">{{get_field_name(0,'LabelName','NEXT_VISIT_DATE')}}</td> -->
</tr>


<tr>
		
<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['agent_id']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['agent_id']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['agent_id']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['agent_id']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['agent_id']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['agent_id']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['agent_id']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  	

			
<td >		

<input id="{{FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />
</td>
	
<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['token_status']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['token_status']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['token_status']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['token_status']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['token_status']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['token_status']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['token_status']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />

</td>  	
	
				
<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['VISIT_1']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['VISIT_1']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['VISIT_1']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['VISIT_1']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['VISIT_1']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['VISIT_1']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['VISIT_1']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  readonly="" />

</td>  

<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  readonly="" />

</td>  


<td>
<input id="{{FormInputArray[0]['header'][0]['fields'][0]['GAP_DAYS']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['GAP_DAYS']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['GAP_DAYS']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['GAP_DAYS']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['GAP_DAYS']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['GAP_DAYS']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['GAP_DAYS']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  readonly="" />

</td>  


<td>

<!--<input id="{{FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT_DATE']['input_id_index']}}" 
autofocus type="text" name=""   autocomplete="off" 
placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT_DATE']['LabelName']}}" 				 
ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT_DATE']['input_id_index'])" 
ng-keyup="checkKeyUp($event)" 
ng-model="FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT_DATE']['Inputvalue']"
ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT_DATE']['InputName'],0,0,0,0,'search')" 			
ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT_DATE']['InputName'],0,0,0,0,'search')" 	
ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['NEXT_VISIT_DATE']['input_id_index'])"
class="form-control" onfocus="this.select();" onmouseup="return false;"  />-->

</td>  	
</tr>


<tr>
		<td  align="left">{{get_field_name(0,'LabelName','ACTUAL_VISIT_AMT')}}</td> 
		<td  align="left">
		<input id="{{FormInputArray[0]['header'][0]['fields'][0]['ACTUAL_VISIT_AMT']['input_id_index']}}" 
		autofocus type="text" name=""   autocomplete="off" 
		placeholder="{{FormInputArray[0]['header'][0]['fields'][0]['ACTUAL_VISIT_AMT']['LabelName']}}" 				 
		ng-keydown="checkKeyDown($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['ACTUAL_VISIT_AMT']['input_id_index'])" 
		ng-keyup="checkKeyUp($event)" 
		ng-model="FormInputArray[0]['header'][0]['fields'][0]['ACTUAL_VISIT_AMT']['Inputvalue']"
		ng-change="search(FormInputArray[0]['header'][0]['fields'][0]['ACTUAL_VISIT_AMT']['InputName'],0,0,0,0,'search')" 			
		ng-focus="search(FormInputArray[0]['header'][0]['fields'][0]['ACTUAL_VISIT_AMT']['InputName'],0,0,0,0,'search')" 	
		ng-keypress="mainOperation($event,0,0,0,0,FormInputArray[0]['header'][0]['fields'][0]['ACTUAL_VISIT_AMT']['input_id_index'])"
		class="form-control" onfocus="this.select();" onmouseup="return false;"  />
		</td> 
		
</tr>
			
</table>	
		
	
	
	
	
	
	<!--SEARCH SECTION-->									
	<div class="scroll-div" id="anchor" style="display:{{search_div_display}}">
		<table class="table table-bordered table-hover table-condensed "  >	
			
			<tr><td  ng-repeat="(key,value) in suggestions[0]" ng-if="key!='FieldID' ">{{key}}</td></tr>
						
			<tr ng-repeat="values in suggestions" ng-class="{'activeTR': selectedIndex == $index}" 
			ng-click="AssignValueAndHide($index)" id="innerAnchor{{$index}}">
			
			<td   ng-repeat="(key,value) in values" ng-if="key!='FieldID'">{{value}}</td>	
			</tr>																									
		</table>
	</div>
				
	<!--REGULAR SALE SECTION END-->				
			
	<div class="panel panel-primary"  ng-if="dtlist_array.length>0">
	<div class="panel-body" >
	<!--{{dtlist_array}}-->
	
	
	
		<table class="table table-bordered table-hover table-condensed "  >	
			
			<!--<tr  style="background-color:#999999">
			<td  >Srl</td>
			
			<td align="center" ng-repeat="(key,value) in dtlist_array[0]" ng-if="key!='id' ">{{key}}</td>
			<td  >Edit</td>
			<td  >Delete</td>
			</tr>	
											
			<tr ng-repeat="values in dtlist_array" >
			<td  align="right" ng-repeat="(key,value) in values" ng-if="key=='id'">{{$index+1}}</td>	
			<td  align="right" ng-repeat="(key,value) in values" ng-if="key!='id'">{{value}}</td>	
			<td ng-repeat="(key,value) in values" ng-if="key=='id'" >
			<button class="btn-block btn-info" ng-click="dtlist_view(value)" >Edit</button>
			</td>
			
			<td ng-repeat="(key,value) in values" ng-if="key=='id'" >
			<button class="btn-block btn-info" ng-click="delete_item(value)" >Delete</button>
			</td>
			
			</tr>	-->
			
			
			<tr  style="background-color:#999999">			
				<td>Srl</td>
				<td align="center" ng-repeat="(key,value) in dtlist_array[0]" 
				ng-if="value.InputType!='hidden' ">{{value.LabelName}}</td>
				<!--<td>Edit</td>-->
				<td>Delete</td>
			</tr>	
										
			<tr ng-repeat="values in dtlist_array track by $index" >
			
				<td  align="right">{{$index+1}}</td>	
				<td  align="right" ng-repeat="(key,value) in values" 
				ng-if="value.InputType!='hidden'">{{value.Inputvalue}}</td>	
				
				<td><button class="btn-block btn-info" ng-click="delete_item(values.id.Inputvalue)" >
				Delete</button></td>	
						
			</tr>	
			
			
			<tr style="background-color:#999999" class="input_field_height">
				<td colspan="11" align="right" class="input_field_height">Total :</td>	
				<td  align="right" class="input_field_height">{{dtlist_total_array['total_amt']}}</td>	
				<!--<td colspan="2" align="right" class="input_field_height">&nbsp;</td>
				<td align="right" class="input_field_height">{{dtlist_total_array['tot_discount']}}</td>	
				<td align="right">{{dtlist_total_array['Taxable_Amt']}}</td>	
				<td  align="right" class="input_field_height" >&nbsp;</td>
				<td align="right" >{{dtlist_total_array['totvatamt']}}</td>
				<td align="right">{{dtlist_total_array['Net_Amt']}}</td>	-->
				<td align="right" >&nbsp;</td>
		   </tr>	
																											
		</table>
   </div></div>		
				
  		
	<!--	{{dtlist_array}}-->

	
	<table class="table table-bordered table-striped" >								
		  <tr>
				<!--<td>Comment</td>
				<td    colspan="2">
				<textarea name="comment" cols="20" rows="3"
				ng-model="FormInputArray[0]['header'][0]['fields'][0]['comment']['Inputvalue']" 
				class="form-control"></textarea>
				</td> -->
				
				<td    colspan="3">
				<td>
				<button type="button" class="btn btn-success" id="Save" name="Save" 
				ng-click="save_prescription()">Save</button>
				
				<button type="button" class="btn btn-success" id="Save" name="Save" 
				ng-click="print_prescription(FormInputArray[0]['header'][0]['fields'][0]['prescription_id']['Inputvalue_id'])">Print </button>				
				
				<button type="button" class="btn btn-success"  
				ng-click="new_entry()">New Entry </button>
				
				<!--<a data-toggle="modal" data-target="#shortModal" 
					class="btn btn-primary"><i class="fa fa-pencil"></i> SHOW BARCODE</a>-->

				</td> 
			</tr>	
	</table>
	
	
		
	
	<!--LIST OF ALL CONSIGNMENT FROM TO WISE SEARCH-->		  
	<div class="panel panel-primary" >
			<div class="panel-body" align="center" style="background-color:#3c8dbc">
	 <div class="form-row">
			   
				<div class="form-group col-md-4">
				  <label for="inputState">From Date</label>
				 <input type="text"  id="startdate"  
				 name="startdate" class="form-control"  ng-model="startdate" autocomplete="off"> 				
				</div>
				
				 <div class="form-group col-md-4">
				  <label for="inputState">To date</label>
				 <input type="text"  id="enddate" 
				 name="enddate" class="form-control"  ng-model="enddate" autocomplete="off"> 
				 </div>
				 
				  <div class="form-group col-md-4">
				<button type="button" class="btn btn-block btn-success" name="Save" 
				ng-click="prescription_list(1)">Display</button>
				
				<!-- <button ng-click="print_documents('POS_INVOICE')"  class="btn btn-block btn-success">Print</button>-->
			
				
		
			  	 </div>
				 
				
				
			  </div>
	
	</div></div>
	
	
	
		<table class="table table-bordered table-hover table-condensed "  >	
			
			<tr  style="background-color:#999999">
			<td align="center" ng-repeat="(key,value) in main_grid_array[0]" ng-if="key!='id' ">{{key}}</td>
			<!--<td  >Edit</td>-->
			<!--<td  >Label</td>
			-->
			<!--<td  >A4 Print</td>-->
			<td  >Print</td>
			
			</tr>	
							
			<tr ng-repeat="values in main_grid_array" ng-init="setTotals(values)">
			
				<td  align="right" ng-repeat="(key,value) in values" ng-if="key!='id'">{{value}}</td>	
				<td    ng-repeat="(key,value) in values" ng-if="key=='id'" >
				<button class="btn-block btn-info" ng-click="print_prescription(value)" >Print</button>
				</td>
				
			<?php /*?>	<td ng-repeat="(key,value) in values" ng-if="key=='id'" >
				<button class="btn-block btn-info" ng-click="print_label(value)" >Label</button>
				</td>
				
				<td ng-repeat="(key,value) in values" ng-if="key=='id'" >
				<button class="btn-block btn-info" ng-click="print_documents('POS_INVOICE',value)" >Print</button>
				</td><?php */?>
				
				
			
			</tr>
			
			
		<!--	<tr class="input_field_height" style="background-color:#999999">
			<td  colspan="4" >Total</td>			
			<td align="right" >{{grandtotal}}</td>
			<td colspan="4" >&nbsp;</td>
			</tr>				-->																						
		</table>
			
	

</div>



<!--{{FormInputArray[0]['header'][1]['fields'][0]['rate']}}-->

<!--main array : {{FormInputArray[0]['header'][1]['fields'][0]['batchno']}}
<br />

Temp array : {{final_array[0]['header'][1]['fields'][0]['batchno']}}
-->

<!--server msg :{{FormInputArray[0]["header"][1]['fields']}}-->

<!--{{FormInputArray[0]["header"][1]['fields'][0]['product_id']['validation_msg']}}
-->

<!--{{suggestions}}-->




<!--BB-  {{FormInputArray[0]["header"][0]['fields'][0]['doctor_ledger_id']}} <BR />-->



<!--potency_id-  {{FormInputArray[0]["header"][1]['fields'][0]['potency_id']['Inputvalue_id']}} <BR />
pack_id-  {{FormInputArray[0]["header"][1]['fields'][0]['pack_id']['Inputvalue_id']}} <BR />
product_group_id-  {{FormInputArray[0]["header"][1]['fields'][0]['product_group_id']['Inputvalue_id']}} <BR />
-->

<!--{{FormInputArray[0]["header"][1]['fields'][0]['product_id']}}-->



