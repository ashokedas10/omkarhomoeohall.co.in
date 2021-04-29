//creating a General service Factory for using other app(module)
'use strict';


//var domain_name="http://royhomoeohall.co.in/distributors/";
//var domain_name="http://adequatesolutions.co.in/homeopathi/";
//var domain_name="http://localhost/Subhojit_DEPAK_BHUIYA/homeopathi/";

var GeneralServices=angular.module('GeneralServices',[]);

//GENERAL FUNCTIONS FOR TRANSACTIONS

GeneralServices.factory('general_functions',['$http','$rootScope',function($http,$rootScope)
{
   
   var factoryobj={};
   var itm='test ';	 

   factoryobj.list_items=function(form_name,param,BaseUrl,id)
   {
	   $rootScope.searchItems=[];				
	   var data_link=BaseUrl;
	   var success={};	
	   
	   var data_save = {'form_name':form_name,'subtype':'view_list','id':id};
	   console.log(form_name + id);
	   
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){
	   angular.forEach(response.data,function(value,key)
	   {	
			 $rootScope.FormInputArray[0] ={header:value.header};	
	   });	
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });
	   
	   //return $rootScope.searchItems;
	   
	   return itm;
   }

   factoryobj.other_search=function(form_name,subtype,BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   
	   var success={};	
	   var data = JSON.stringify($rootScope.FormInputArray);
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'other_search','id':id,
	   'raw_data':data,'header_index':$rootScope.indx1,'field_index':$rootScope.index2,
	   'searchelement':$rootScope.searchelement};

	   //console.log('form_name '+$rootScope.current_form_report+' searchelement '+$rootScope.searchelement);

	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response)
	   {	
			angular.forEach(response.data,function(value,key)
			{
				$rootScope.FormInputArray[0] ={	header:value.header};
			});
				//$scope.$apply();
				//$rootScope.spiner=false;	
	   },	   
	   function error(response)
	   {
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   }
	 
	   );
	   return $rootScope.FormInputArray;
   }	

   factoryobj.other_search_new=function(form_name,subtype,BaseUrl,id,input_id_index)
   {

	   var data_link=BaseUrl;	   
	   var success={};	
	   var data = JSON.stringify($rootScope.FormInputArray);
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'other_search','id':id,
	   'raw_data':data,'header_index':$rootScope.indx1,'field_index':$rootScope.index2,
	   'searchelement':$rootScope.searchelement};

	   //console.log('form_name '+$rootScope.current_form_report+' searchelement '+$rootScope.searchelement);

	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response)
	   {	
			angular.forEach(response.data,function(value,key)
			{
				$rootScope.FormInputArray[0] ={	header:value.header};
			});
			//$scope.$apply();
	   },	   
	   function error(response)
	   {
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   }
	 
	   );
	   return $rootScope.FormInputArray;
   }	


   ///SALE SECTION MAIN FUNTIONS START

//    {"MainTable":"productmstr","LinkField":"productname","frmrpttemplatehdrID":"35","DIVClass":"3","Section":"0",
//    "SectionType":"HEADER","input_id_index":9,"LabelName":"Product","InputName":"product_id","Inputvalue":"",
//    "Inputvalue_id":"","InputType":"text","validation_type":"0","validation_msg":"","datafields":[],"under_fields":[]}

   factoryobj.all_master=function(BaseUrl)
   {

	   var data_link=BaseUrl;	   
	   var success={};	
	   var data = JSON.stringify($rootScope.FormInputArray);
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'all_master'};
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response)
	   {	
			
		// 	$rootScope.test=response.data.PRODUCT_B;
		// //$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
		// 	angular.forEach($rootScope.test,function(value,key){
		// 	//	$rootScope.all_master.push({FieldID: value.FieldID,FieldVal:value.FieldVal});			
		// 	});
			
			
	   },	   
	   function error(response)
	   {
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   }
	 
	   );
	   return $rootScope.all_master;
   }	
   

   factoryobj.download_all_master=function(BaseUrl)
   {

	   var data_link=BaseUrl;	   
	   var success={};	
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'download_all_master'};
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response)
	   {	
						
			$rootScope.all_master['MAIN_PRODUCT_GROUP']=response.data.MAIN_PRODUCT_GROUP;
			$rootScope.all_master['PRODUCT_M']=response.data.PRODUCT_M;
			$rootScope.all_master['PRODUCT_T']=response.data.PRODUCT_T;
			$rootScope.all_master['PRODUCT_B']=response.data.PRODUCT_B;
			$rootScope.all_master['PRODUCT_D']=response.data.PRODUCT_D;
			$rootScope.all_master['PRODUCT_W']=response.data.PRODUCT_W;
			$rootScope.all_master['PRODUCT_S']=response.data.PRODUCT_S;

			$rootScope.all_master['POTENCY_M']=response.data.POTENCY_M;
			$rootScope.all_master['POTENCY_T']=response.data.POTENCY_T;
			$rootScope.all_master['POTENCY_D']=response.data.POTENCY_D;
			$rootScope.all_master['POTENCY_B']=response.data.POTENCY_B;
			$rootScope.all_master['POTENCY_W']=response.data.POTENCY_W;
			$rootScope.all_master['POTENCY_S']=response.data.POTENCY_S;

			$rootScope.all_master['PACK_SIZE_M']=response.data.PACK_SIZE_M;
			$rootScope.all_master['PACK_SIZE_T']=response.data.PACK_SIZE_T;
			$rootScope.all_master['PACK_SIZE_D']=response.data.PACK_SIZE_D;
			$rootScope.all_master['PACK_SIZE_B']=response.data.PACK_SIZE_B;
			$rootScope.all_master['PACK_SIZE_W']=response.data.PACK_SIZE_W;
			$rootScope.all_master['PACK_SIZE_S']=response.data.PACK_SIZE_S;

			$rootScope.all_master['RATE_MASTER']=response.data.RATE_MASTER;
			

			//console.log('MAIN_PRODUCT_GROUP ID : '+$rootScope.all_master['MAIN_PRODUCT_GROUP']['FieldVal'].findIndex('D'));
			
	   },	   
	   function error(response)
	   {
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   }
	 
	   );
	 
   }	

   

   factoryobj.populate_data=function(indx1,index2,searchelement,BaseUrl)
   {

		if(searchelement=='main_group_id' )	
		{
			 
			var Inputvalue= $rootScope.FormInputArray[0]['header'][indx1]['fields'][index2][searchelement]['Inputvalue'];
			var Inputvalue_id= $rootScope.FormInputArray[0]['header'][indx1]['fields'][index2][searchelement]['Inputvalue_id'];
			
			if(Inputvalue=='P' )
			{
				
				if(Inputvalue=='P' )
				{var field_list=['product_Synonym','batchno','rate','qnty','subtotal','disc_per','disc_per2','label_print','exp_monyr', 'mfg_monyr'];}

				angular.forEach(field_list, function (values, key) 
				{ 
					$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['InputType']='text';
					$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['input_id_index']=10+key;			
					
				}); 


				var data_link=BaseUrl;	   
				var success={};	
				var data_save = {'form_name':$rootScope.current_form_report,'subtype':'download_patent'};
				var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
				$http.post(data_link,data_save,config).then (function success(response)
				{	
					 $rootScope.all_master['PRODUCT_P']=response.data.PRODUCT_P;

					$rootScope.product_master=[];
					angular.forEach($rootScope.all_master['PRODUCT_P'],function(product,product_key){
						$rootScope.product_master.push({FieldID: product.FieldID,FieldVal:product.FieldVal,Company:product.Company
							,Available_qnty:product.available_qnty,Minimum_Stock:product.minimum_stock});			
					});			
					 
				},	   
				function error(response)
				{
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				}
			  
				);


			}
			else
			{
					//FIELD OPEN AND HIDDEN
					if(Inputvalue=='M' || Inputvalue=='T' || Inputvalue=='B')
					{var field_list=['product_Synonym','potency_id','Synonym','pack_id','mrp','rate','qnty','subtotal','disc_per','disc_per2','label_print','exp_monyr', 'mfg_monyr'];}

					if(Inputvalue=='S')
					{var field_list=['product_Synonym','potency_id','Synonym','no_of_dose','dose_Synonym','mrp','rate','qnty','subtotal','disc_per','disc_per2','label_print','exp_monyr', 'mfg_monyr'];}

					if(Inputvalue=='D' )
					{var field_list=['product_Synonym','potency_id','Synonym','pack_id','pack_synonym','mrp','rate','qnty','subtotal','disc_per','disc_per2','label_print','exp_monyr', 'mfg_monyr'];}

					if(Inputvalue=='W' )
					{var field_list=['product_Synonym','potency_id','Synonym','pack_id','no_of_dose','mrp','rate','qnty','subtotal','disc_per','disc_per2','label_print','exp_monyr', 'mfg_monyr'];}
				

					angular.forEach(field_list, function (values, key) 
					{ 
						$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['InputType']='text';
						$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['input_id_index']=10+key;			
						
					}); 

					//MASTER LOAD

					angular.forEach($rootScope.all_master['MAIN_PRODUCT_GROUP'], function (values, key) 
					{ 				
						if(values.FieldVal==Inputvalue)
						{ 
							$rootScope.FormInputArray[0]['header'][1]['fields'][0]['main_group_name']['Inputvalue']=values.MAIN_GROUP_NAME;
							//console.log('PRODUCT_'+Inputvalue);

							$rootScope.product_master=[];
							angular.forEach($rootScope.all_master['PRODUCT_'+Inputvalue],function(product,product_key){
								$rootScope.product_master.push({FieldID: product.FieldID,FieldVal:product.FieldVal});			
							});			
							
							$rootScope.potency_master=[];
							angular.forEach($rootScope.all_master['POTENCY_'+Inputvalue],function(potency,potency_key){
								$rootScope.potency_master.push({FieldID: potency.FieldID,FieldVal:potency.FieldVal});			
							});		

							$rootScope.pack_master=[];
							angular.forEach($rootScope.all_master['PACK_SIZE_'+Inputvalue],function(pack,pack_key){
								$rootScope.pack_master.push({FieldID: pack.FieldID,FieldVal:pack.FieldVal});			
							});						
						
						}
									
					}); 	

			}


		}
		
		if(searchelement=='product_id' )	
		{
			var main_group_id= $rootScope.FormInputArray[0]['header'][indx1]['fields'][index2]['main_group_id']['Inputvalue'];
			var Inputvalue= $rootScope.FormInputArray[0]['header'][indx1]['fields'][index2][searchelement]['Inputvalue'];
			var Inputvalue_id= $rootScope.FormInputArray[0]['header'][indx1]['fields'][index2][searchelement]['Inputvalue_id'];	

			if(main_group_id=='P' )
			{



			}
			else
			{
				angular.forEach($rootScope.all_master['PRODUCT_'+main_group_id], function (values, key) 
				{ 	
					if(values.FieldID==Inputvalue_id)
					{ 
						//console.log('FieldID '+values.FieldID + ' group id :'+values.product_group_id);
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['product_group_id']['Inputvalue']=values.product_group_id;
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['product_group_id']['Inputvalue_id']=values.product_group_id;
					}
				}); 

			}	

		}	

   }

   factoryobj.set_rate=function()
   {

		var main_group_val =$rootScope.FormInputArray[0]['header'][1]['fields'][0]['main_group_id']['Inputvalue'];

		if(main_group_val=='MM1' | main_group_val=='MM2' | main_group_val=='MM3' 
		| main_group_val=='MM4' | main_group_val=='MM5' | main_group_val=='MM6')
		{
			main_group_val='M';
		}

		if(main_group_val=='M' || main_group_val=='T' || main_group_val=='B' || main_group_val=='D' 
		|| main_group_val=='W'  || main_group_val=='S' )
		{
			var product_group_id =Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['product_group_id']['Inputvalue_id']);
			var potency_id =Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['potency_id']['Inputvalue_id']);
			var pack_id =Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['pack_id']['Inputvalue_id']);
			var no_of_dose =Number($rootScope.FormInputArray[0]['header'][1]['fields'][0]['no_of_dose']['Inputvalue']); 
			
			console.log('product_group_id:'+product_group_id+' potency_id:'+potency_id+' pack_id:'+pack_id+' no_of_dose:'+no_of_dose);

			if(main_group_val=='M' || main_group_val=='T' || main_group_val=='B' || main_group_val=='D')
			{
				if(product_group_id>0 && potency_id>0 && pack_id>0)
				{
					$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=
					$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_1']['MRP'];

					$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=
					$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_1']['RATE'];

				}		
			}

			if(main_group_val=='W')
			{
				if(product_group_id>0 && potency_id>0 && pack_id>0)
				{

					var dose1_rate=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_1']['RATE'];
					var dose1_mrp=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_1']['MRP'];

					var dose2_rate=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_2']['RATE'];
					var dose2_mrp=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_2']['MRP'];

					var dose3_rate=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_3']['RATE'];
					var dose3_mrp=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_3']['MRP'];

					var dose4_rate=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_4']['RATE'];
					var dose4_mrp=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_4']['MRP'];

					var dose5_rate=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_5']['RATE'];
					var dose5_mrp=	$rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_5']['MRP'];

					console.log(no_of_dose);

					if(no_of_dose==1)
					{
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=dose1_rate;
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=dose1_mrp;
					}
					else if(no_of_dose==2)
					{
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=dose2_rate*no_of_dose;
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=dose2_mrp*no_of_dose;
					}
					else if(no_of_dose==3)
					{
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=dose3_rate*no_of_dose;
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=dose3_mrp*no_of_dose;
					}
					else if(no_of_dose==4)
					{
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=dose4_rate*no_of_dose;
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=dose4_mrp*no_of_dose;
					}
					else if(no_of_dose>=5)
					{
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=dose5_rate*no_of_dose;
						$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=dose5_mrp*no_of_dose;
					}
				

				}		
			}

			if(main_group_val=='S' )
			{
				pack_id=249; //1 DOSE
				if(product_group_id>0 && potency_id>0 && pack_id>0)
				{
					var dose1_rate=	Number($rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_1']['RATE']);
					var dose1_mrp=	Number($rootScope.all_master['RATE_MASTER'][product_group_id][potency_id][pack_id]['DOSE_1']['MRP']);			
					
					$rootScope.FormInputArray[0]['header'][1]['fields'][0]['rate']['Inputvalue']=dose1_rate*no_of_dose;
					$rootScope.FormInputArray[0]['header'][1]['fields'][0]['mrp']['Inputvalue']=dose1_mrp*no_of_dose;
				}
			}
		}
   }	

   ///SALE SECTION MAIN FUNTIONS



   factoryobj.delete_bill=function(form_name,subtype,BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};		   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'delete_bill','id':id};
		console.log(data_save);
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){	

		$rootScope.server_msg=response.data.server_msg;
		
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });
	  
   }	

   factoryobj.delete_item=function(form_name,subtype,BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};		   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'delete_item','id':id};
		console.log(id);
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){	

		$rootScope.server_msg=response.data.server_msg;
		
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });
	  
   }	


   factoryobj.view_detail=function(form_name,BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};	
	   var data = JSON.stringify($rootScope.FormInputArray);
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'other_search','id':id,
	   'raw_data':data,'header_index':$rootScope.indx1,'field_index':$rootScope.index2,
	   'searchelement':'view_detail'};

	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){	
	   angular.forEach(response.data,function(value,key)
	   {
		 $rootScope.FormInputArray[0] ={	header:value.header};});
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.FormInputArray;
   }	

   factoryobj.batch_search=function(form_name,subtype,BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};	
	   var data = JSON.stringify($rootScope.final_array);
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'other_search','id':id,
	   'raw_data':data,'header_index':$rootScope.indx1,'field_index':$rootScope.index2,'searchelement':$rootScope.searchelement};

	   console.log('form_name '+$rootScope.current_form_report+' searchelement '+$rootScope.searchelement);

	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){	
	   angular.forEach(response.data,function(value,key)
	   {
		 $rootScope.final_array[0] ={	header:value.header};});
		 
		 $rootScope.FormInputArray[0]['header'][1]['fields'][0]['batchno']['datafields']=
		 $rootScope.final_array[0]['header'][1]['fields'][0]['batchno']['datafields'];

		 //TAX SECTION
		 $rootScope.FormInputArray[0]['header'][1]['fields'][0]['tax_ledger_id']['Inputvalue_id']=
		 $rootScope.final_array[0]['header'][1]['fields'][0]['tax_ledger_id']['Inputvalue_id'];

		 $rootScope.FormInputArray[0]['header'][1]['fields'][0]['tax_ledger_id']['Inputvalue']=
		 $rootScope.final_array[0]['header'][1]['fields'][0]['tax_ledger_id']['Inputvalue'];

		 //PRODUCT VALIDATION
		 $rootScope.FormInputArray[0]['header'][1]['fields'][0]['product_id']['validation_msg']=
		 $rootScope.final_array[0]['header'][1]['fields'][0]['product_id']['validation_msg'];




		 //console.log(' datavalue:   '+$rootScope.final_array[0]['header'][1]['fields'][0]['batchno']['datafields']);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.final_array;
   }	

   factoryobj.main_grid=function(form_name,subtype,BaseUrl,id,startdate,enddate)
   {

	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'MAIN_GRID',
	   'id':id,'startdate':startdate,'enddate':enddate	};
	   console.log(data_save);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.main_grid_array=response.data.header;
		   console.log($rootScope.main_grid_array);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.main_grid_array;
   }	

   //PRESCRIPTION PORTION
   

   factoryobj.prescription_edit=function(BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};	
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'prescription_edit','id':id};
	  
	   console.log(data_save);

	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){	
	   angular.forEach(response.data,function(value,key)
	   {
		 $rootScope.FormInputArray[0] ={	header:value.header};});
		 $scope.$apply();
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.FormInputArray;
   }	

   factoryobj.prescription_list=function(form_name,subtype,BaseUrl,id,startdate,enddate)
   {
	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'prescription_list',
	   'id':id,'startdate':startdate,'enddate':enddate	};
	   console.log(data_save);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.main_grid_array=response.data.header;
		   console.log($rootScope.main_grid_array);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.main_grid_array;
   }

   factoryobj.patient_list=function(BaseUrl)
   {
	  $rootScope.server_msg="Patient Data Loading....";
	  var data_link=BaseUrl;	   
	   var success={};	
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'patient_list'};
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response)
	   {	
			
			$rootScope.patient_list_array=response.data;
			$scope.$apply();
			$rootScope.server_msg="Patient Data Loaded";
	   },	   
	   function error(response)
	   {
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   }
	 
	);
	 
   }	

   
   factoryobj.set_patient_records=function(id)
   {
		

		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['party_name']['Inputvalue']=$rootScope.patient_list_array[id]['records']['party_name'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['SEX']['Inputvalue']=$rootScope.patient_list_array[id]['records']['SEX'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['PATIENT_TYPE']['Inputvalue']=$rootScope.patient_list_array[id]['records']['PATIENT_TYPE'];

		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['mobno']['Inputvalue']=$rootScope.patient_list_array[id]['records']['mobno'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['emailid']['Inputvalue']=$rootScope.patient_list_array[id]['records']['emailid'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['address']['Inputvalue']=$rootScope.patient_list_array[id]['records']['address'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['Address2']['Inputvalue']=$rootScope.patient_list_array[id]['records']['Address2'];

		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['age_yy']['Inputvalue']=$rootScope.patient_list_array[id]['records']['age_yy'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['age_mm']['Inputvalue']=$rootScope.patient_list_array[id]['records']['age_mm'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['DOB']['Inputvalue']=$rootScope.patient_list_array[id]['records']['DOB'];

		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['agent_id']['Inputvalue_id']=$rootScope.patient_list_array[id]['records']['agent_id'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['agent_id']['Inputvalue']=$rootScope.patient_list_array[id]['agent_name'];

		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['Inputvalue_id']=$rootScope.patient_list_array[id]['records']['doctor_mstr_id'];
		$rootScope.FormInputArray[0]['header'][0]['fields'][0]['doctor_mstr_id']['Inputvalue']=$rootScope.patient_list_array[id]['doctor_name'];
		

		// $rootScope.FormInputArray[0]['header'][0]['fields'][0]['agent_id']['Inputvalue']=$rootScope.patient_list_array[id]['agent_id'];
		// $rootScope.FormInputArray[0]['header'][0]['fields'][0]['agent_id']['Inputvalue']=$rootScope.patient_list_array[id]['agent_id'];
		// $rootScope.FormInputArray[0]['header'][0]['fields'][0]['agent_id']['Inputvalue']=$rootScope.patient_list_array[id]['agent_id'];
		// $rootScope.FormInputArray[0]['header'][0]['fields'][0]['agent_id']['Inputvalue']=$rootScope.patient_list_array[id]['agent_id'];
		// $rootScope.FormInputArray[0]['header'][0]['fields'][0]['agent_id']['Inputvalue']=$rootScope.patient_list_array[id]['agent_id'];

		//return true;

   }	
  
   //PRESCRIPTION PORTION
   	
   factoryobj.receipt_payment_list=function(form_name,subtype,BaseUrl,id,startdate,enddate)
   {
	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'receipt_payment_list',
	   'id':id,'startdate':startdate,'enddate':enddate	};
	   console.log(data_save);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.main_grid_array=response.data.header;
		   console.log($rootScope.main_grid_array);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.main_grid_array;
   }	

   factoryobj.dtlist=function(BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'dtlist','id':id};
	   console.log(data_save);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.dtlist_array=response.data.header;
		   console.log($rootScope.dtlist_array);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.dtlist_array;
   }	


   factoryobj.view_bill_wise_item=function(BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'view_bill_wise_item','id':id};
	   console.log(data_save);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.dtlist_array=response.data.header;
		   console.log($rootScope.dtlist_array);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.dtlist_array;
   }	


   factoryobj.dtlist_total=function(BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'dtlist_total','id':id};
	   console.log(data_save);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.dtlist_total_array=response.data.header;
		   console.log($rootScope.dtlist_array);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.dtlist_array;
   }	

   factoryobj.dtlist_view=function(BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};	
	   var data = JSON.stringify($rootScope.FormInputArray);
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'dtlist_view','id':id,'raw_data':data};
	   
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){	
	   angular.forEach(response.data,function(value,key)
	   {$rootScope.FormInputArray[0] ={header:value.header};});
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.FormInputArray;
   }	

   //RATE MASTER -------

   factoryobj.get_master=function(BaseUrl,id,master_name)
   {

	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'subtype':master_name,'id':id};
	   console.log(data_save);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.rate_list_array['group_list']=response.data.group_list;		   
		   console.log($rootScope.rate_list_array);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.rate_list_array;
   }	

   factoryobj.rate_list=function(BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'subtype':'rate_list','id':id};
	   console.log(data_save);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.rate_list_array['header']=response.data.header;
		   $rootScope.rate_list_array['body']=response.data.body;
		   console.log($rootScope.rate_list_array);
	   },
	   function error(response){
		   $scope.errorMessage = 'Error adding user!';
		   $scope.message = '';
	   });

	   return $rootScope.rate_list_array;
   }	
   
   //RATE MASTER -------
   
   
   return factoryobj;

}]);


// Consignment Autocomplete section
GeneralServices.factory('AutocompleteConsignment',['$http','$rootScope',function($http,$rootScope)
{
    // var baseurl=
    //define an object
    var factoryobj={};

    $rootScope.selectedIndex = -1;
	console.log('domain_name '+domain_name);
	var BaseUrl=domain_name+"Primary_sale_controller/ConsignmentEntry_angular/";
	
	console.log(BaseUrl);
	
	var data_link=BaseUrl+"consignee/consignee";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_consignee_name=response.data	;
	});

	var data_link=BaseUrl+"consignor/consignor";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_consignor_name=response.data	;
	});

	var data_link=BaseUrl+"source_dest/source_dest";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_source_dest=response.data	;
	});
	
	var data_link=BaseUrl+"general_master/CLIENT_TYPE";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_client_type_name=response.data;
	});	

	var data_link=BaseUrl+"general_master/billing_party";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_billing_party=response.data;
	});	

	var data_link=BaseUrl+"general_master/PAYMENT_DONE";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_PAYMENT_DONE=response.data;
	});	

	var data_link=BaseUrl+"general_master/RISK_TYPE";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_risk_id=response.data;
	});	

	var data_link=BaseUrl+"general_master/PACK_TYPE";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_pack_type=response.data;
	});	

	
		
	factoryobj.list_items=function(param){
		$rootScope.searchItems=[];
		console.log(param);

		if(param=='consignee_name'){		
			angular.forEach($rootScope.list_consignee_name, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}
		if(param=='consignor_name'){		
			angular.forEach($rootScope.list_consignor_name, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}
		if(param=='origin_name' ||  param=='destination_name'){		
			angular.forEach($rootScope.list_source_dest, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}
		if(param=='client_type_name')
		{		
				angular.forEach($rootScope.list_client_type_name, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}
		if(param=='billing_party')
		{		
				angular.forEach($rootScope.list_billing_party, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}
		if(param=='PAYMENT_DONE')
		{		
				angular.forEach($rootScope.list_PAYMENT_DONE, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}
		if(param=='risk_id')
		{		
				angular.forEach($rootScope.list_risk_id, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}
		if(param=='pack_type')
		{		
				angular.forEach($rootScope.list_pack_type, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}
		
		

		
        return $rootScope.searchItems;
	}

    
    
    factoryobj.CurrentDate=function(CurrentDate){

		//var date = new Date();
		var year = CurrentDate.getFullYear();
		var month = CurrentDate.getMonth()+1;
		var dt = CurrentDate.getDate();

		if (dt < 10) {
		dt = '0' + dt;
		}
		if (month < 10) {
		month = '0' + month;
		}
			var finaldt=year+'-' + month + '-'+dt;

        return finaldt;
    };

    return factoryobj;
 
 }]);
 
// Accounts Sections

 GeneralServices.factory('PurchaseEntry',['$http','$rootScope',function($http,$rootScope)
 {
    // var baseurl=
    //define an object
    var factoryobj={};

    $rootScope.selectedIndex = -1;
	//var domain_name="http://localhost/abir_das_unitedlab/road_transport_final/";	
	//var domain_name="http://durgapurtransport.co.in/";
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/PurchaseEntry/";
	console.log(BaseUrl);
	//ReceiveTransacions

	var data_link=BaseUrl+"tbl_party_id_name";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_party=response.data	;
	});


	var data_link=BaseUrl+"product_id_name";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_products=response.data	;
	});


	factoryobj.list_items=function(param,trantype){
		$rootScope.searchItems=[];
		console.log(param);

		if(param=='tbl_party_id_name'){		
			angular.forEach($rootScope.list_party, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}	
		
		if(param=='product_id_name'){		
			angular.forEach($rootScope.list_products, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}	
		
		
        return $rootScope.searchItems;
	}
    
    return factoryobj;
 
 }]);

 GeneralServices.factory('purchase_rtn',['$http','$rootScope',function($http,$rootScope)
 {
    // var baseurl=
    //define an object
    var factoryobj={};

    $rootScope.selectedIndex = -1;
	//var domain_name="http://localhost/abir_das_unitedlab/road_transport_final/";	
	//var domain_name="http://durgapurtransport.co.in/";
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/purchase_rtn/";
	console.log(BaseUrl);
	//ReceiveTransacions

	var data_link=BaseUrl+"tbl_party_id_name";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_party=response.data	;
	});


	var data_link=BaseUrl+"product_id_name";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.list_products=response.data	;
	});


	factoryobj.list_items=function(param,trantype){
		$rootScope.searchItems=[];
		console.log(param);

		if(param=='tbl_party_id_name'){		
			angular.forEach($rootScope.list_party, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}	
		
		if(param=='product_id_name'){		
			angular.forEach($rootScope.list_products, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}	
		
		
        return $rootScope.searchItems;
	}
    
    return factoryobj;
 
 }]);


 GeneralServices.factory('Sale_test',['$http','$rootScope',function($http,$rootScope)
 {
    // var baseurl=
    //define an object
    var factoryobj={};

    // $rootScope.selectedIndex = -1;
	// //var domain_name="http://localhost/abir_das_unitedlab/road_transport_final/";	
	// //var domain_name="http://durgapurtransport.co.in/";
	// var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/SaleEntry/";
	// console.log(BaseUrl);
	// //ReceiveTransacions

	// var data_link=BaseUrl+"tbl_party_id_name";
	// $http.get(data_link)
	// .then(function(response) {
	// $rootScope.list_party=response.data	;
	// });

	

	// var data_link=BaseUrl+"doctor_ledger_id_name";

	// $http.get(data_link).then(function(response) {$rootScope.list_doctor_ledger_id_name=response.data;});

	
    var factoryobj={};
	
	
    
    //return factoryobj;



	factoryobj.list_items=function(param,product_id)
	{
		$rootScope.searchItems=[];
		
		if(param=='batchno'){	
			
			var data_link=BaseUrl+"batchno/"+product_id;
			console.log(data_link);			
			$http.get(data_link)
			.then(function(response) {
			$rootScope.list_batch=response.data	;
			});			
			angular.forEach($rootScope.list_batch, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}	
		

		if(param=='tbl_party_id_name'){		
			angular.forEach($rootScope.list_party, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});						
		}	
		
		if(param=='product_id_name'){	
				
				var data_link=BaseUrl+"product_id_name/"+product_id;
				console.log(data_link);			
				$http.get(data_link)
				.then(function(response) {
				$rootScope.list_products=response.data	;
				});			
				angular.forEach($rootScope.list_products, function(value, key) {
				//$rootScope.searchItems.push(value.name);
				$rootScope.searchItems.push({name: value.name,available_qnty:value.available_qnty});
				});		
				
				//$rootScope.searchItems=$rootScope.jsontest;

				//console.log($rootScope.searchItems);

		}	

		if(param=='product_id_name_mixer'){		
			// angular.forEach($rootScope.list_products_mixer, function(value, key) {
			// 	$rootScope.searchItems.push(value.name);
			// 	});		
				
				var data_link=BaseUrl+"product_id_name/"+product_id;
				console.log(data_link);			
				$http.get(data_link)
				.then(function(response) {
				$rootScope.list_products=response.data	;
				});			
				angular.forEach($rootScope.list_products, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});		
		}	

		if(param=='doctor_ledger_id_name'){		
			angular.forEach($rootScope.list_doctor_ledger_id_name, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});	
			}
		
        return $rootScope.searchItems;
	}
    
    return factoryobj;
 
 }]);



 
 GeneralServices.factory('AccountsTransaction',['$http','$rootScope',function($http,$rootScope){
    // var baseurl=
    //define an object
    var factoryobj={};

    $rootScope.selectedIndex = -1;
	//var domain_name="http://localhost/abir_das_unitedlab/road_transport_final/";	
	//var domain_name="http://durgapurtransport.co.in/";
	
	//RECEIVE SECTION
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/RECEIVE/";	
	var data_link=BaseUrl+"ledger_account_name/CR/";
	console.log(data_link);
	$http.get(data_link)
	.then(function(response) {
	$rootScope.CRledgers_RECEIVE=response.data	;
	});
	
	var data_link=BaseUrl+"ledger_account_name/DR/";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.DRledgers_RECEIVE=response.data	;
	});

	//PAYMENT SECTION
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/PAYMENT/";	
	var data_link=BaseUrl+"ledger_account_name/CR/";
	console.log(data_link);
	$http.get(data_link)
	.then(function(response) {
	$rootScope.CRledgers_PAYMENT=response.data	;
	});
	
	var data_link=BaseUrl+"ledger_account_name/DR/";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.DRledgers_PAYMENT=response.data	;
	});

	//JOURNAL SECTION
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/JOURNAL/";	
	var data_link=BaseUrl+"ledger_account_name/CR/";
	console.log(data_link);
	$http.get(data_link)
	.then(function(response) {
	$rootScope.CRledgers_JOURNAL=response.data	;
	});
	
	var data_link=BaseUrl+"ledger_account_name/DR/";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.DRledgers_JOURNAL=response.data	;
	});

	//CONTRA SECTION
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/CONTRA/";	
	var data_link=BaseUrl+"ledger_account_name/CR/";
	console.log(data_link);
	$http.get(data_link)
	.then(function(response) {
	$rootScope.CRledgers_CONTRA=response.data	;
	});
	
	var data_link=BaseUrl+"ledger_account_name/DR/";
	$http.get(data_link)
	.then(function(response) {
	$rootScope.DRledgers_CONTRA=response.data	;
	});
	
	
	var data_link=BaseUrl+"EMPLOYEE_NAME/";	
	$http.get(data_link)
	.then(function(response) {
	$rootScope.EMPLOYEE_NAME=response.data	;
	});

	factoryobj.list_items=function(element_name,parent_element_id,CRDR_TYPE,AcTranType,detailtype){
		
		 console.log('element_name '+element_name
		 +' parent_element_id '+parent_element_id+' AcTranType'+AcTranType);
		
		$rootScope.searchItems=[];	
				
		// if(element_name=='truck_no'){		
		// 	angular.forEach($rootScope.truck_no, function(value, key) {
		// 		$rootScope.searchItems.push(value.name);
		// 		});		
		// 	}

		if(element_name=='EMPLOYEE_NAME'){		
			angular.forEach($rootScope.EMPLOYEE_NAME, function(value, key) {
				$rootScope.searchItems.push(value.name);
				});		
			}

		if(AcTranType=='RECEIVE')
		{
			if(element_name=='ledger_account_name' && CRDR_TYPE=='CR'){		
				angular.forEach($rootScope.CRledgers_RECEIVE, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
				}
	
			if(element_name=='ledger_account_name' && CRDR_TYPE=='DR'){		
				angular.forEach($rootScope.DRledgers_RECEIVE, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
				}
	
			if(element_name=='BILL_INSTRUMENT_NO')
			{		
				var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/RECEIVE/";
				var data_link=BaseUrl+"BILLNO/"+parent_element_id+'/';
				console.log(data_link);
				$http.get(data_link)
				.then(function(response) {
				$rootScope.bills=response.data	;
				});
	
				angular.forEach($rootScope.bills, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
			}	
		}

		if(AcTranType=='PAYMENT')
		{
			if(element_name=='ledger_account_name' && CRDR_TYPE=='CR'){		
				angular.forEach($rootScope.CRledgers_PAYMENT, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
				}
	
			if(element_name=='ledger_account_name' && CRDR_TYPE=='DR'){		
				angular.forEach($rootScope.DRledgers_PAYMENT, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
				}
	
			if(element_name=='BILL_INSTRUMENT_NO')
			{		
				var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/PAYMENT/";
				var data_link=BaseUrl+"BILLNO/"+parent_element_id+'/';
				$http.get(data_link)
				.then(function(response) {
				$rootScope.bills=response.data	;
				});
	
				angular.forEach($rootScope.bills, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
			}	
		}

		if(AcTranType=='JOURNAL')
		{
			if(element_name=='ledger_account_name' && CRDR_TYPE=='CR'){		
				angular.forEach($rootScope.CRledgers_JOURNAL, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
				}
	
			if(element_name=='ledger_account_name' && CRDR_TYPE=='DR'){		
				angular.forEach($rootScope.DRledgers_JOURNAL, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
				}
	
			if(element_name=='BILLNO')
			{		
				var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/JOURNAL/";
				var data_link=BaseUrl+"BILLNO/"+parent_element_id+'/';
				$http.get(data_link)
				.then(function(response) {
				$rootScope.bills=response.data	;
				});
	
				angular.forEach($rootScope.bills, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
			}	
		}

		if(AcTranType=='CONTRA')
		{
			if(element_name=='ledger_account_name' && CRDR_TYPE=='CR'){		
				angular.forEach($rootScope.CRledgers_CONTRA, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
				}
	
			if(element_name=='ledger_account_name' && CRDR_TYPE=='DR'){		
				angular.forEach($rootScope.DRledgers_CONTRA, function(value, key) {
					$rootScope.searchItems.push(value.name);
					});		
				}	
			
		}
		

        return $rootScope.searchItems;
	}

	factoryobj.bill_summary=function(index_value)
	{
		var billdetail_length=$rootScope.FormInputArray[index_value].details.length;
		var total_bill_amt=0;
		for(var i=0; i<=billdetail_length-1;i++)
		{			
			total_bill_amt=total_bill_amt+
			Number($rootScope.FormInputArray[index_value].details[i].AMOUNT |0);
		}
		console.log('total_bill_amt:'+total_bill_amt);
		return total_bill_amt;
	}
	    
    return factoryobj;
 
 }]);
 