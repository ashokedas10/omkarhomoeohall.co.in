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

   factoryobj.delete_bill=function(form_name,subtype,BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};		   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'delete_bill','id':id};
		console.log('ID:'+$rootScope.current_form_report);
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

   factoryobj.dtlist=function(BaseUrl,id)
   {

	   var data_link=BaseUrl;
	   var success={};		
	   
	   var data_save = {'form_name':$rootScope.current_form_report,'subtype':'dtlist','id':id};
	   console.log('ID: '+$rootScope.current_form_report);	
	   var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
	   $http.post(data_link,data_save,config).then (function success(response){

		   $rootScope.dtlist_array=response.data.header;
		   console.log(' aaaaaaa '+$rootScope.dtlist_array);
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
 