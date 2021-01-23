//CRUD VIDEO
// https://www.youtube.com/watch?v=DB-kVs76XZ4
//https://www.codeproject.com/Tips/872181/CRUD-in-Angular-js
//https://github.com/chieffancypants/angular-hotkeys/ 
//http://www.codexworld.com/angularjs-crud-operations-php-mysql/
'use strict';

//var domain_name="http://adequatesolutions.co.in/homeopathi/";

var domain_name="https://omkarhomoeohall.co.in/";

//var domain_name="http://localhost/Subhojit_DEPAK_BHUIYA/homeopathi/omkar_homeo/";


var query_result_link=domain_name+"Accounts_controller/query_result/";

//************************ACCOUNT RECEIVE START*****************************************//
var app = angular.module('Accounts',['GeneralServices']);

//************************ACCOUNT PURCHASE START*****************************************//



app.controller('Product_master',['$scope','$rootScope','$http',
function($scope,$rootScope,$http)
{
	"use strict";

	//$scope.appState='EMIPAYMENT';
	//var domain_name="http://localhost/abir_das_unitedlab/SATNAM/";	
	$scope.spiner='ON';
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/Product_master/";
			

			$scope.mainOperation=function(event,element_name)
			{	
					console.log('event '+event);
					// if(element_name===19)
					// {
					// 	$scope.get_set_value('','','DRCRCHECKING');
					// 	document.getElementById(7).focus();			
					// }			

					// if(event.keyCode === 13)
					// {	
					// 	if(element_name===10)
					// 	{document.getElementById('exp_monyr').focus();}		

					// 	if(element_name===11)
					// 	{document.getElementById('mfg_monyr').focus();}			

					// 	element_name=Number(element_name+1);			
					// 	document.getElementById(element_name).focus();		
					// }						
			}

			//===================START SEARCH SECTION =========================================
		
			$rootScope.taxlist= [];
			$rootScope.brandlist= [];
			$rootScope.grouplist= [];
			$rootScope.productlist=[];			

			var data_link=query_result_link+"34/";
			$http.get(data_link).then(function(response){angular.forEach(response.data,function(value,key)
			{$rootScope.productlist_master.push({id: value.id,name:value.productname});});});
			
			$scope.savemsg='Product master Loading. Please wait ...... '; 	
			var data_link=BaseUrl+"product_master/";							
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){		
					$scope.savemsg='All product loaded'; 			
					$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
				});
			});
			
			$scope.spiner='OFF';

			var data_link=query_result_link+"38/";
			console.log(data_link);
			$http.get(data_link).then(function(response){angular.forEach(response.data,function(value,key)
			{$rootScope.brandlist.push({id:value.id,name:value.name});});});

			var data_link=query_result_link+"39/";
			console.log(data_link);
			$http.get(data_link).then(function(response){angular.forEach(response.data,function(value,key)
			{$rootScope.grouplist.push({id:value.id,name:value.name});});});

			var data_link=query_result_link+"40/";
			console.log(data_link);
			$http.get(data_link).then(function(response){angular.forEach(response.data,function(value,key)
			{$rootScope.taxlist.push({id:value.id,name:value.acc_name});});});


		$rootScope.search = function(searchelement)
		{
		
			$scope.SEARCHTYPE='PRODUCT';
			$rootScope.searchelement=searchelement;
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];
		
			if($rootScope.searchelement=='product_id_name')
			{$rootScope.searchItems=$rootScope.productlist;}	
			else if($rootScope.searchelement=='group_id_name')
			{$rootScope.searchItems=$rootScope.grouplist;}	
			else if($rootScope.searchelement=='brand_id_name')
			{$rootScope.searchItems=$rootScope.brandlist;}	
			else if($rootScope.searchelement=='tax_per')
			{$rootScope.searchItems=$rootScope.taxlist;}	
			else
			{//Sale_test.list_items($rootScope.searchelement,$scope.product_id);}
			}
	
			console.log($rootScope.searchItems);
						
			$rootScope.searchItems.sort();	
			var myMaxSuggestionListLength = 0;
			for(var i=0; i<$rootScope.searchItems.length; i++)
			{
				
					var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].name);
					var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
					if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) >=0){
	
						if($rootScope.searchelement=='product_id_name' )
						{	$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name,available_qnty:$rootScope.searchItems[i].available_qnty} );}
						else
						{$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name} );}
	
						myMaxSuggestionListLength += 1;
						if(myMaxSuggestionListLength === 1500)
						{break;}
					}
			}

		};
	
	$rootScope.$watch('selectedIndex',function(val){		
		if(val !== -1) {
		//	$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex];
			$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex]['name'];	
		}
	});		
	$rootScope.checkKeyDown = function(event){
		console.log(event.keyCode);

		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
				$rootScope.selectedIndex++;
			}else{
				$rootScope.selectedIndex = 0;
			}
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex-1 >= 0){
				$rootScope.selectedIndex--;
			}else{
				$rootScope.selectedIndex = $rootScope.suggestions.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			//console.log($rootScope.selectedIndex);
		//	event.preventDefault();			
		//	$rootScope.suggestions = [];
		//	$rootScope.searchItems=[];
		//	$rootScope.selectedIndex = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex>-1){
				$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			console.log($rootScope.selectedIndex);
			event.preventDefault();
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];
			$rootScope.selectedIndex = -1;
		}else{
			$rootScope.search();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.searchelement);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions = [];
			$rootScope.selectedIndex = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];
				$rootScope.selectedIndex = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide = function(index)
	{

		$scope[$rootScope.searchelement]= $rootScope.suggestions[index];
	
		if($rootScope.searchelement=='product_id_name')
		{
			var id=	$rootScope.suggestions[index]['id'];	
			var data_link=BaseUrl+"product_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name']=value.name; // NAME 					
					$scope['hsncode']=value.hsncode; 

					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; 

					$scope['group_id']=value.group_id; 
					$scope['group_id_name']=value.group_id_name; 

					$scope['brand_id']=value.brand_id; 
					$scope['brand_id_name']=value.brand_id_name; 
																					
				});
			});

			
		}

		if($rootScope.searchelement=='group_id_name')
		{
			$scope['group_id']=$rootScope.suggestions[index]['id'];
			$scope['group_id_name']=$rootScope.suggestions[index]['name'];				
		}
		if($rootScope.searchelement=='tax_per')
		{
			$scope['tax_ledger_id']=$rootScope.suggestions[index]['id'];
			$scope['tax_per']=$rootScope.suggestions[index]['name'];				
		}
		if($rootScope.searchelement=='brand_id_name')
		{
			$scope['brand_id']=$rootScope.suggestions[index]['id'];
			$scope['brand_id_name']=$rootScope.suggestions[index]['name'];				
		}
			
		 $rootScope.suggestions=[];
		 $rootScope.searchItems=[];
		 $rootScope.selectedIndex = -1;
	};

	//===================END SEARCH SECTION =========================================


	$scope.savedata=function()
	{
		var data_link=BaseUrl+"SAVE";
		var success={};
		$scope.spiner='ON';
		//console.log('$scope.id_detail'+$scope.id_detail);
		var data_save = 
		{
			'product_id': $scope.get_set_value($scope.product_id,'num','SETVALUE'),
			'productname': $scope.get_set_value($scope.product_id_name,'str','SETVALUE'),
			'hsncode': $scope.get_set_value($scope.hsncode,'str','SETVALUE'),
			'group_id': $scope.get_set_value($scope.group_id,'num','SETVALUE'),
			'tax_ledger_id': $scope.get_set_value($scope.tax_ledger_id,'num','SETVALUE'),
			'brand_id': $scope.get_set_value($scope.brand_id,'num','SETVALUE')
		};	
	
		var config = {headers : 
			{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
		}
		//$http.post(data_link, data,config)
		$http.post(data_link,data_save,config)
		.then (function success(response){

			//console.log('ID HEADER '+response.data.id_header);
			$scope.savemsg='Product has been saved Successfully!'; 			
			$scope.get_set_value(response.data,'','REFRESH');
			document.getElementById('1').focus();
			
		},
		function error(response){
			$scope.errorMessage = 'Error adding user!';
			$scope.message = '';
		});
		

	}
	
	$scope.get_set_value=function(datavalue,datatype,operation)
	{
		if(operation=='SETVALUE')
		{
			if(angular.isUndefined(datavalue)==true)
			{
				if(datatype=='num')
				{return 0;}
				if(datatype=='str')
				{return '';}		
			}
			else
			{return datavalue;}
		}
	
		if(operation=='REFRESH')
		{		
			$scope.spiner='OFF';
			$scope['product_id']='';
			$scope['product_id_name']='';				
			$scope['hsncode']='';
			$scope['group_id']='';
			$scope['group_id_name']='';
			$scope['tax_ledger_id']='';
			$scope['tax_per']='';
			$scope['brand_id']='';
			$scope['brand_id_name']='';	
			document.getElementById('1').focus();	

		}
	}



}]);




//************************PRODUCT MASTER END*****************************************//

app.directive('scrollToBottom', function($timeout, $window) {
	return {
			scope: {
					scrollToBottom: "="
			},
			restrict: 'A',
			link: function(scope, element, attr) {
					scope.$watchCollection('scrollToBottom', function(newVal) {
							if (newVal) {
									$timeout(function() {
											element[0].scrollTop =  element[0].scrollHeight;
									}, 0);
							}

					});
			}
	};
});


//************************ACCOUNT PURCHASE START*****************************************//
app.controller('PurchaseEntry',['$scope','$rootScope','$http','PurchaseEntry',
function($scope,$rootScope,$http,PurchaseEntry){
	"use strict";

		//$scope.appState='EMIPAYMENT';
		//var domain_name="http://localhost/abir_das_unitedlab/SATNAM/";	

		
		$scope.spiner='OFF';
		$scope.duplicate_bill='OK';
		var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/PurchaseEntry/";
		$scope.tran_date=$rootScope.tran_date;

		$scope.previous_transaction_details=function(product_id)
		{
			var data_link=BaseUrl+"previous_transaction_details/"+product_id;					
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope.savemsg=value.msg; 
					$scope.rate=value.rate;
				});
			});

		}

		$scope.duplicate_bill_check=function()
		{
				var data_link=BaseUrl+"check_previous_details/"+$scope.tbl_party_id+"/"+$scope.invoice_no;					
				console.log(data_link);					
				$http.get(data_link).then(function(response){
					angular.forEach(response.data,function(value,key){
						$scope.savemsg=value.msg; 
						$scope.duplicate_bill=value.savestatus; 					
					});
				});

				console.log($scope.duplicate_bill);

		}

		$scope.delete_product=function(id)
		{	
		 
			var data_link=domain_name+"Accounts_controller/AccountsTransactions/DELETE_PRODUCT";			
			var success={};		
			var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
			console.log(data_save);	
			var config = {headers : 
				{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
			}
			//$http.post(data_link, data,config)
			$http.post(data_link,data_save,config).then (function success(response){

				$scope.get_set_value(response.data.id_header,'','VIEWALLVALUE');
				document.getElementById('product_id_name').focus();

				//console.log('ID HEADER '+response.data.id_header);
				//	$scope.get_set_value(response.data,'','REFRESH');
				//	document.getElementById('product_id_name').focus();
			},
			function error(response){
				$scope.errorMessage = 'Error adding user!';
				$scope.message = '';
			});

		}
		
		$scope.delete_invoice=function(id)
		{	
				var data_link=BaseUrl+"DELETE_INVOICE";		
				console.log(data_link);	
				var success={};		
				var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
				console.log(data_save);	
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config).then (function success(response){

					$scope.GetAllList($scope.startdate,$scope.enddate);
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});
		}

		$scope.check_previous_details=function(party_id)
		{
			var data_link=BaseUrl+"check_previous_details/"+party_id+"/"+$scope.invoice_no;					
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope.invid=value.id; 				
				});
			});

			$scope.VIEWALLVALUE($scope.invid);

		}

		

		$scope.clearcokie=function()
		{
			$cookies.remove('username');
			$cookies.remove('username');
			$cookies.remove('username');
		}

		$scope.mainOperation=function(event,element_name)
		{	
				console.log('element_name '+element_name);
				if(element_name===19)
				{
					$scope.get_set_value('','','DRCRCHECKING');
				  document.getElementById(7).focus();			
				}			

				if(event.keyCode === 13)
				{	
					if(element_name===10)
					{document.getElementById('exp_monyr').focus();}		

					if(element_name===11)
					{document.getElementById('mfg_monyr').focus();}			

					element_name=Number(element_name+1);			
					document.getElementById(element_name).focus();		
				}						
		}
		 
			$rootScope.partylist= [];
			$rootScope.doctorlist= [];
			$rootScope.productlist=[];			
			//var data_link=domain_name+'product_master.json';	

			
			$scope.savemsg='All Master Loading........Please Wait.';

			var data_link=query_result_link+"32/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.doctorlist.push({id:value.id,name:value.acc_name});
				});
			});

			var data_link=query_result_link+"35/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.partylist.push({id:value.id,name:value.acc_name});
				});
			});

			var data_link=BaseUrl+"product_id_name/";							
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope.savemsg=value.name; 
					$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
					$scope.savemsg='All Master Loaded.';
				});
			});

		$rootScope.search = function(searchelement)
		{
		
			$scope.SEARCHTYPE='PRODUCT';
			$rootScope.searchelement=searchelement;
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];
			//console.log($rootScope.searchelement);
			/*
			PurchaseEntry.list_items($rootScope.searchelement,$scope.trantype);
			$rootScope.searchItems.sort();	
			var myMaxSuggestionListLength = 0;
			for(var i=0; i<$rootScope.searchItems.length; i++){
				var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i]);
				var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
				if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) !== -1){
					$rootScope.suggestions.push(searchItemsSmallLetters);
					myMaxSuggestionListLength += 1;
					if(myMaxSuggestionListLength === 400){
						break;
					}
				}
			}*/


			if($rootScope.searchelement=='product_id_name')
			{
				//Sale_test.list_items($rootScope.searchelement,$scope.product_id_name);
				$rootScope.searchItems=$rootScope.productlist;
			}		
			else if($rootScope.searchelement=='tbl_party_id_name')
			{$rootScope.searchItems=$rootScope.partylist; 	}	
			else
			{//Sale_test.list_items($rootScope.searchelement,$scope.product_id);}
			}
	
		//	console.log($rootScope.searchItems);
		// DIS AGROPYRUM - 10M/10000 (1 X 1 PCS)
					
			$rootScope.searchItems.sort();	
			var myMaxSuggestionListLength = 0;
			for(var i=0; i<$rootScope.searchItems.length; i++)
			{
				
					var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].name);
					var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
					if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) >=0){
	
						if($rootScope.searchelement=='product_id_name' )
						{	$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name,available_qnty:$rootScope.searchItems[i].available_qnty} );}
						else
						{$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name} );}
	
						myMaxSuggestionListLength += 1;
						if(myMaxSuggestionListLength === 1500)
						{break;}
					}
					
			}



		};
	
	$rootScope.$watch('selectedIndex',function(val){		
		if(val !== -1) {
		//	$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex];
			$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex]['name'];	
		}
	});		
	$rootScope.checkKeyDown = function(event){
		console.log(event.keyCode);

		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
				$rootScope.selectedIndex++;
			}else{
				$rootScope.selectedIndex = 0;
			}
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex-1 >= 0){
				$rootScope.selectedIndex--;
			}else{
				$rootScope.selectedIndex = $rootScope.suggestions.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			//console.log($rootScope.selectedIndex);
		//	event.preventDefault();			
		//	$rootScope.suggestions = [];
		//	$rootScope.searchItems=[];
		//	$rootScope.selectedIndex = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex>-1){
				$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			console.log($rootScope.selectedIndex);
			event.preventDefault();
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];
			$rootScope.selectedIndex = -1;
		}else{
			$rootScope.search();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.searchelement);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions = [];
			$rootScope.selectedIndex = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];
				$rootScope.selectedIndex = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide = function(index)
	{
		$scope[$rootScope.searchelement]= $rootScope.suggestions[index];

		if($rootScope.searchelement=='tbl_party_id_name')
		{
			//var str=$scope.tbl_party_id_name;
		//	var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));
			var id=	$rootScope.suggestions[index]['id'];	
			var data_link=BaseUrl+"tbl_party_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['tbl_party_id']=value.id;  //ACTUAL ID
					$scope['tbl_party_id_name']=value.name; // NAME 		
					$scope.check_previous_details(value.id);		
					$scope.duplicate_bill_check();			
				});
			});
		}
		if($rootScope.searchelement=='product_id_name')
		{
			//var str=$scope.product_id_name;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];	
			var data_link=BaseUrl+"product_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name']=value.name; // NAME 	
					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; // NAME 
					$scope.previous_transaction_details(value.id);															
				});
			});

			
		}
		console.log('data_link');
			
		 $rootScope.suggestions=[];
		 $rootScope.searchItems=[];
		 $rootScope.selectedIndex = -1;
	};
	//===================END SEARCH SECTION =========================================

	//=========batch wise search=====================

	$rootScope.search_batch = function(searchelement)
	{	
		$scope.SEARCHTYPE='BATCH';		

		$rootScope.searchelement=searchelement;
		$rootScope.suggestions_batch = [];
		$rootScope.searchItems=[];

		var data_link=BaseUrl+"batchno/"+$scope.product_id;
		console.log(data_link);			
		$http.get(data_link)
		.then(function(response) {
		$rootScope.suggestions_batch=response.data	;
		});			

	};
	
	$rootScope.$watch('selectedIndex_batch',function(val){		
		if(val !== -1) {	
			$scope['batchno'] =
			$rootScope.suggestions_batch[$rootScope.selectedIndex_batch].batchno;		
		}
	});		

	$rootScope.checkKeyDown_batch = function(event){
		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch+1 < $rootScope.suggestions_batch.length){
				$rootScope.selectedIndex_batch++;
			}else{
				$rootScope.selectedIndex_batch = 0;
			}
		
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch-1 >= 0){
				$rootScope.selectedIndex_batch--;
			}else{
				$rootScope.selectedIndex_batch = $rootScope.suggestions_batch.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			//console.log($rootScope.selectedIndex);
			// event.preventDefault();			
			// $rootScope.suggestions_batch = [];
			// $rootScope.searchItems=[];		
			// $rootScope.selectedIndex_batch = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex_batch>-1){
				$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);			
			event.preventDefault();
			$rootScope.suggestions_batch = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex_batch = -1;
		}else{
			$rootScope.search_batch();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.batchno);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions_batch = [];			
			$rootScope.selectedIndex_batch = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp_batch = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions_batch = [];
				$rootScope.searchItems=[];			
				$rootScope.selectedIndex_batch = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide_batch = function(index)
	{

			$scope[$rootScope.searchelement]= $rootScope.suggestions_batch[index].batchno;
			//console.log($rootScope.suggestions_batch[index].exp_monyr);
		
			//	$scope.previous_transaction_details();
			
			$scope['exp_monyr']=$rootScope.suggestions_batch[index].exp_monyr;  
			$scope['mfg_monyr']=$rootScope.suggestions_batch[index].mfg_monyr; 
			$scope['rackno']=$rootScope.suggestions_batch[index].rackno; 
			$scope['rate']=$rootScope.suggestions_batch[index].rate; 
			$scope['srate']=$rootScope.suggestions_batch[index].srate; 
			$scope['mrp']=$rootScope.suggestions_batch[index].mrp; 
			$scope['ptr']=$rootScope.suggestions_batch[index].ptr; 
			$scope['AVAILABLE_QTY']=$rootScope.suggestions_batch[index].AVAILABLE_QTY; 
		
			$rootScope.suggestions_batch=[];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex = -1;
	};
	//===================END batch SEARCH SECTION =========================================

	$scope.savedata=function()
	{
		var data_link=BaseUrl+"SAVE";
		var success={};
		$scope.spiner='ON';
	
		var data_save = 
		{
			'id_header': $scope.get_set_value($scope.id_header,'num','SETVALUE'),
			'id_detail': $scope.get_set_value($scope.id_detail,'num','SETVALUE'),
			'product_id': $scope.get_set_value($scope.product_id,'num','SETVALUE'),
			'tbl_party_id': $scope.get_set_value($scope.tbl_party_id,'num','SETVALUE'),
			'invoice_no': $scope.get_set_value($scope.invoice_no,'str','SETVALUE'),
			'invoice_date': $scope.get_set_value($scope.invoice_date,'str','SETVALUE'),
			'challan_no': $scope.get_set_value($scope.challan_no,'str','SETVALUE'),
			'challan_date': $scope.get_set_value($scope.challan_date,'str','SETVALUE'),
			'tbl_party_id_name': $scope.get_set_value($scope.tbl_party_id_name,'str','SETVALUE'),
			'comment': $scope.get_set_value($scope.comment,'str','SETVALUE'),
			'product_id_name': $scope.get_set_value($scope.product_id_name,'str','SETVALUE'),
			'batchno': $scope.get_set_value($scope.batchno,'str','SETVALUE'),
			'qnty': $scope.get_set_value($scope.qnty,'num','SETVALUE'),
			'exp_monyr': $scope.get_set_value($scope.exp_monyr,'str','SETVALUE'),
			'mfg_monyr': $scope.get_set_value($scope.mfg_monyr,'str','SETVALUE'),
			'rate': $scope.get_set_value($scope.rate,'num','SETVALUE'),
			'mrp': $scope.get_set_value($scope.mrp,'num','SETVALUE'),
			'ptr': $scope.get_set_value($scope.ptr,'num','SETVALUE'),
			'srate': $scope.get_set_value($scope.srate,'num','SETVALUE'),
			'tax_per': $scope.get_set_value($scope.tax_per,'num','SETVALUE'),	
			'disc_per': $scope.get_set_value($scope.disc_per,'num','SETVALUE'),	
			'tax_ledger_id': $scope.get_set_value($scope.tax_ledger_id,'num','SETVALUE'),
			'disc_per2': $scope.get_set_value($scope.disc_per2,'num','SETVALUE'),
			'tot_cash_discount': $scope.get_set_value($scope.tot_cash_discount,'num','SETVALUE'),
			'rackno': $scope.get_set_value($scope.rackno,'num','SETVALUE')
		};	
	
		var config = {headers : 
			{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
		}
		//$http.post(data_link, data,config)

		console.log('data_save'+data_save + 'config' + config);

		$http.post(data_link,data_save,config)
		.then (function success(response){
		
			console.log(response);
			
			console.log('ID HEADER '+response.data.id_header);
			$scope.get_set_value(response.data,'','REFRESH');
			document.getElementById('product_id_name').focus();
			
		},
		function error(response){
			$scope.errorMessage = 'Error adding user!';
			$scope.message = '';
		});
		

	}
	


	$scope.submit_print=function()
	{
		$scope.spiner='ON';
		var data_link=BaseUrl+"FINAL_SUBMIT/"+$scope.id_header;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{$scope.ListOfTransactions=response.data;});

		// var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
		// console.log(data_link);
		// $http.get(data_link).then(function(response) 
		// {$scope.listOfDetails=response.data;});
		
	 $scope.VIEWALLVALUE($scope.id_header);
		$scope.spiner='OFF';

	}

	$scope.get_set_value=function(datavalue,datatype,operation)
	{

		if(operation=='SETVALUE')
		{
			if(angular.isUndefined(datavalue)==true)
			{
				if(datatype=='num')
				{return 0;}
				if(datatype=='str')
				{return '';}		
			}
			else
			{return datavalue;}
		}

		if(operation=='DRCRCHECKING')
		{
			var savestatus='OK';
			$scope.savestatus1='NOTOK';		

			if($scope.invoice_date == null || $scope.invoice_date === "")			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER INVOICE DATE';
			document.getElementById('invoice_date').focus();
			}
			if($scope.tbl_party_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PARTY';
			document.getElementById('tbl_party_id_name').focus();
			}

			if($scope.product_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PRODUCT';
			document.getElementById('product_id_name').focus();
			}

			//console.log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'+$scope.get_set_value($scope.id_header,'num','SETVALUE'));
		
			if(savestatus=='OK' && $scope.duplicate_bill=='OK' )
			{
				$scope.savedata();
				$scope.savemsg='Receord Has been saved Successfully!';
			}
		}

		if(operation=='REFRESH')
		{		
			//HEADER SECTION
			$scope.id_header=datavalue.id_header;
			$scope.invoice_no=datavalue.invoice_no;
			$scope.invoice_date=datavalue.invoice_date;
			$scope.challan_no=datavalue.challan_no;
			$scope.challan_date=datavalue.challan_date;
			$scope.tbl_party_id_name=datavalue.tbl_party_id_name;
			$scope.tbl_party_id=datavalue.tbl_party_id;
			$scope.comment=datavalue.comment;

			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.disc_per2=$scope.rackno='';

			//data list
			 var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});
			
			 $scope.VIEWALLVALUE($scope.id_header);
			 $scope.spiner='OFF';

			$cookies.remove('product_id_name');
			$cookies.remove('batchno');
		

			//$scope.consignment_value();
			//$scope.GetAllConsignment($scope.startdate,$scope.enddate);

		}

		if(operation=='NEWENTRY')
		{		
			
			//HEADER SECTION
			$scope.id_header='';
			$scope.invoice_no='';
			$scope.invoice_date='';
			$scope.challan_no='';
			$scope.challan_date='';
			$scope.tbl_party_id_name='';
			$scope.tbl_party_id='';
			$scope.comment='';
			
			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.disc_per2=$scope.rackno=$scope.tot_cash_discount='';

			//data list
			 var data_link=BaseUrl+"DTLLIST/"+0;
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});

			 document.getElementById('invoice_date').focus();
		}

		if(operation=='VIEWDTL')
		{	
			var data_link=BaseUrl+"VIEWDTL/"+datavalue;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){
					$scope['id_detail']=value.id;  
					$scope['product_id_name']=value.product_id_name;  
					$scope['product_id']=value.product_id;  					
					$scope['batchno']=value.batchno;  
					$scope['qnty']=value.qnty;  
					$scope['exp_monyr']=value.exp_monyr;  
					$scope['mfg_monyr']=value.mfg_monyr; 
					$scope['rate']=value.rate;
					$scope['mrp']=value.mrp;	
					$scope['ptr']=value.ptr;
					$scope['srate']=value.srate;
					$scope['tax_per']=value.tax_per;
					$scope['tax_ledger_id']=value.tax_ledger_id;
					$scope['disc_per']=value.disc_per;
					$scope['disc_per2']=value.disc_per2;
					$scope['rackno']=value.rackno;
				});			
				
			});
		}

		if(operation=='VIEWALLVALUE')
		{	
			var data_link=BaseUrl+"DTLLIST/"+datavalue;
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails=response.data;});

			$scope.VIEWALLVALUE(datavalue);
	
		}

	}

	$scope.view_dtl=function(dtl_id,type)
	{
		var data_link=BaseUrl+"VIEWDTL/"+dtl_id;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{
			angular.forEach(response.data,function(value,key){
				$scope['id_detail']=value.id;  
				$scope['product_id_name']=value.product_id_name;  
				$scope['product_id']=value.product_id;  					
				$scope['batchno']=value.batchno;  
				$scope['qnty']=value.qnty;  
				$scope['exp_monyr']=value.exp_monyr;  
				$scope['mfg_monyr']=value.mfg_monyr; 
				$scope['rate']=value.rate;
				$scope['mrp']=value.mrp;	
				$scope['ptr']=value.ptr;
				$scope['srate']=value.srate;
				$scope['tax_per']=value.tax_per;
				$scope['tax_ledger_id']=value.tax_ledger_id;
				$scope['disc_per']=value.disc_per;
				$scope['disc_per2']=value.disc_per2;
				$scope['rackno']=value.rackno;
			});			
			
		});
	}


	$scope.VIEWALLVALUE=function(invoice_id)
	{

		var data_link=BaseUrl+"VIEWALLVALUE/"+invoice_id;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){

					$scope['id_header']=value.id_header;  					
					$scope['invoice_no']=value.invoice_no;  
					$scope['invoice_date']=value.invoice_date;  
					$scope['challan_no']=value.challan_no;  
					$scope['challan_date']=value.challan_date;  
					$scope['tbl_party_id_name']=value.tbl_party_id_name;  
					$scope['tbl_party_id']=value.tbl_party_id;								
					$scope['comment']=value.comment;
					$scope['tot_cash_discount']=value.tot_cash_discount;

					$scope['total_amt']=value.total_amt;  
					$scope['tot_discount']=value.tot_discount;	
					$scope['tot_taxable_amt']=$scope['total_amt']-$scope['tot_discount'];								
					$scope['totvatamt']=value.totvatamt;
					$scope['grandtot']=value.grandtot;
				});	
				
			});		

	}


	$scope.submit_print=function()
	{
		$scope.spiner='ON';
		var data_link=BaseUrl+"FINAL_SUBMIT/"+$scope.id_header;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{$scope.ListOfTransactions=response.data;});

		var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{$scope.listOfDetails=response.data;});

	// 	var data_link=BaseUrl+"DTLLISTMIX/"+$scope.MIX_RAW_LINK_ID;
	// 	console.log(data_link);
	//  $http.get(data_link).then(function(response) 
	//  {$scope.listOfDetails_mix=response.data;});
	 
	 $scope.VIEWALLVALUE($scope.id_header);
	 
		// var data_link=BaseUrl+"print_invoice/"+$scope.id_header+'/'+'INVOICE';		
		// console.log(data_link);
		// window.popup(data_link); 

		$scope.spiner='OFF';

	}
	
	$scope.GetAllList=function(fromdate,todate){
			//var BaseUrl=domain_name+"Primary_sale_controller/ConsignmentList/";
			//data list GetAllConsignment			
			var data_link=BaseUrl+'GetAllList/PAYMENT/-/-/'+fromdate+'/'+todate;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{$scope.ListOfTransactions=response.data;});
	}
	
	$scope.print_barcode = function(id_header) 
	{ 
		var BaseUrl=domain_name+"Project_controller/print_all/";
		var data_link=BaseUrl+id_header;
		window.popup(data_link); 
	};

}]);

//************************ACCOUNT PURCHASE END*****************************************//

//************************ACCOUNT SALE START*****************************************//
app.controller('Sale_test',['$scope','$rootScope','$http','$window','Sale_test',
function($scope,$rootScope,$http,$window,Sale_test)
{
	"use strict";

	//http://plnkr.co/edit/Z6tdvG9Rt8DhuiHGurRu?p=preview   --scroll section

	//$scope.appState='EMIPAYMENT';
	//var domain_name="http://localhost/abir_das_unitedlab/SATNAM/";	
	//$window.scrollTo(400,400);
	//$scope.messageWindowHeight = parseInt($window.innerHeight - 170) + 'px';
	
	var CurrentDate=new Date();
	var year = CurrentDate.getFullYear();
	var month = CurrentDate.getMonth()+1;
	var dt = CurrentDate.getDate();
  
	if (dt < 10) {	dt = '0' + dt;}
	if (month < 10) {month = '0' + month;}
	$scope.invoice_date=year+'-' + month + '-'+dt;
	
	//$scope.todate=year+'-' + month + '-'+dt;

	//SCROLL SECTION
	//http://jsfiddle.net/kMzR9/3/

	

	$scope.spiner='OFF';
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/SaleEntry/";
	$scope.tran_date=$rootScope.tran_date;
	$scope.product_profit=false;

		$scope.previous_transaction_details=function(product_id,batchno,validation_type,tbl_party_id)
		{
			
			
			if(validation_type=='PRODUCT_WISE_RATE_VALIDATION')
			{
					var data_link=BaseUrl+"previous_transaction_details/"+product_id+'/'+batchno+'/'+validation_type+'/'+tbl_party_id;					
					console.log(data_link);					
					$http.get(data_link).then(function(response){
						angular.forEach(response.data,function(value,key){
							$scope.savemsg=value.msg; 
						});
					});
			}
			if(validation_type=='PRODUCT_BATCH_WISE_PURCHASE_RATE_VALIDATION')
			{
					var data_link=BaseUrl+"previous_transaction_details/"+product_id+'/'+batchno+'/'+validation_type+'/'+tbl_party_id;			
					console.log(data_link);					
					$http.get(data_link).then(function(response){
						angular.forEach(response.data,function(value,key){
							var disc_amt1=Number($scope.rate)*Number($scope.disc_per/100); 
							var after_first_disc_amount=Number($scope.rate-disc_amt1);
							var disc_amt2=Number(after_first_disc_amount*$scope.disc_per2/100);
							var salerate=Number($scope.rate-disc_amt1-disc_amt2);
							console.log('salerate'+salerate+' Purchase Rate'+value.purchase_rate);
							if(salerate<value.purchase_rate)
							{$scope.savemsg='Your Purchase Rate:'+value.purchase_rate+' | Your Sale rate '+salerate; $scope.product_profit=false;}
							else
							{$scope.savemsg='You are in profit.Which is :'+Math.round(Number(salerate-value.purchase_rate),2)+' / Qnty'; $scope.product_profit=true;}


						});
					});
			}
			
		}

		$scope.mainOperation=function(event,element_name)
		{	
			
			console.log('element_name '+element_name);
			if(event.keyCode === 13)
				{	
					element_name=Number(element_name+1);			
					document.getElementById(element_name).focus();		
				}				

				if(element_name===20)
				{
					$scope.get_set_value('','','DRCRCHECKING');
				  document.getElementById(8).focus();			
				}
				if(element_name===114)
				{
					$scope.get_set_value('','','ADDMIXTURE');
				  document.getElementById(102).focus();			
				}
		 }

	
		 
			$scope.delete_product=function(id)
			{	
			 
				var data_link=domain_name+"Accounts_controller/AccountsTransactions/DELETE_PRODUCT";			
				var success={};		
				var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
				console.log(data_save);	
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config).then (function success(response){

					$scope.get_set_value(response.data.id_header,'','VIEWALLVALUE');
					document.getElementById('product_id_name').focus();

					//console.log('ID HEADER '+response.data.id_header);
				  //	$scope.get_set_value(response.data,'','REFRESH');
				  //	document.getElementById('product_id_name').focus();
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});

			}

			$scope.delete_invoice=function(id)
			{	
					var data_link=BaseUrl+"DELETE_INVOICE";		
					console.log(data_link);	
					var success={};		
					var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
					console.log(data_save);	
					var config = {headers : 
						{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
					}
					//$http.post(data_link, data,config)
					$http.post(data_link,data_save,config).then (function success(response){

						$scope.GetAllList($scope.startdate,$scope.enddate);
					},
					function error(response){
						$scope.errorMessage = 'Error adding user!';
						$scope.message = '';
					});
			}


		$rootScope.partylist= [];
		$rootScope.doctorlist= [];
		$rootScope.productlist=[];			
		$rootScope.csrlist=[];			
		$rootScope.billtypelist=[];		
	
		
			$scope.savemsg='All master Loading. Please wait ...... '; 
		
			var data_link=query_result_link+"32/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.doctorlist.push({id:value.id,name:value.acc_name});
				});
			});

			var data_link=query_result_link+"33/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.partylist.push({id:value.id,name:value.acc_name});
				});
			});

			var data_link=query_result_link+"36/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.csrlist.push({id:value.id,name:value.name});
				});
			});

			var data_link=query_result_link+"37/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.billtypelist.push({id:value.id,name:value.FieldID});
				});
			});

			// var data_link=query_result_link+"34/";
			// console.log(data_link);
			// $http.get(data_link).then(function(response){
			// 	angular.forEach(response.data,function(value,key){					
			// 		$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
			// 		$scope.savemsg='All Master Loaded.';
			// 	});
			// });

			var data_link=BaseUrl+"product_id_name/";							
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
					$scope.savemsg='All Master Loaded.';
				});
			});


		//	console.log($rootScope.partylist);
			
		$scope.divtop=0;
		$scope.divtop_final=0;
		$rootScope.batchlist=[];		

		$rootScope.search = function(searchelement)
		{			
			
					
				$rootScope.searchelement=searchelement;
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];
				console.log($rootScope.searchelement);		

				if($rootScope.searchelement=='product_id_name')
				{
					//Sale_test.list_items($rootScope.searchelement,$scope.product_id_name);
					$rootScope.searchItems=$rootScope.productlist;
				}
				else if($rootScope.searchelement=='product_id_name_mixer')
				{
					//Sale_test.list_items($rootScope.searchelement,$scope.product_id_name_mixer);
					$rootScope.searchItems=$rootScope.productlist;
				}
				else if($rootScope.searchelement=='doctor_ledger_id_name')
				{$rootScope.searchItems=$rootScope.doctorlist; }
				else if($rootScope.searchelement=='tbl_party_id_name')
				{$rootScope.searchItems=$rootScope.partylist; 	}	
				else if($rootScope.searchelement=='BILL_TYPE')
				{$rootScope.searchItems=$rootScope.billtypelist; 	}	
				else if($rootScope.searchelement=='hq_id_name')
				{$rootScope.searchItems=$rootScope.csrlist; 	}	
				else if($rootScope.searchelement=='batchno')
				{
					var data_link=BaseUrl+"batchno/"+$scope.product_id;	
					console.log(' New '+data_link);
					$http.get(data_link).then(function(response){
						angular.forEach(response.data,function(value,key){	
							if($rootScope.batchlist.indexOf($rootScope.batchlist[key]) === -1) {				
						
								$rootScope.batchlist.push({
								id:value.id,
								name:value.batchno+'//',		
								batchno:value.batchno,									
								PURCHASEID:value.id,
								AVAILABLE_QTY:value.qty_available,
								exp_monyr:value.exp_monyr,
								mfg_monyr:value.mfg_monyr,
								rate:value.rate,
								srate:value.srate,
								mrp:value.mrp,
								rackno:value.rackno,
								ptr:value.ptr});
								
							}
						});
					});		
					$rootScope.searchItems=$rootScope.batchlist;
					
					console.log($rootScope.searchItems);
				}	
				else
				{
					//Sale_test.list_items($rootScope.searchelement,$scope.product_id);}
				}

				//	console.log($rootScope.searchItems);
				// DIS AGROPYRUM - 10M/10000 (1 X 1 PCS)
				
				$rootScope.searchItems.sort();	
				var myMaxSuggestionListLength = 0;
				for(var i=0; i<$rootScope.searchItems.length; i++)
				{
					
						var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].name);
						var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
						if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) >=0){
							
							if($rootScope.searchelement=='batchno')
							{
								$rootScope.suggestions.push(
									{
										id:$rootScope.searchItems[i].id,
										name:$rootScope.searchItems[i].name,
										batchno:$rootScope.searchItems[i].batchno,
										PURCHASEID:$rootScope.searchItems[i].PURCHASEID,
										AVAILABLE_QTY:$rootScope.searchItems[i].AVAILABLE_QTY,
										exp_monyr:$rootScope.searchItems[i].exp_monyr,
										mfg_monyr:$rootScope.searchItems[i].mfg_monyr,
										srate:$rootScope.searchItems[i].srate,
										mrp:$rootScope.searchItems[i].mrp,
										rackno:$rootScope.searchItems[i].rackno,
										ptr:$rootScope.searchItems[i].ptr});
							 }
							else if($rootScope.searchelement=='product_id_name' || $rootScope.searchelement=='product_id_name_mixer')
							{	$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name,available_qnty:$rootScope.searchItems[i].available_qnty} );}
							else
							{$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name} );}

							myMaxSuggestionListLength += 1;
							if(myMaxSuggestionListLength === 1500)
							{break;}
						}
						
				}

				console.log('suggestions  final test : : '+$rootScope.suggestions);
		};
	
	$rootScope.$watch('selectedIndex',function(val){		
		if(val !== -1) {					
			$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex]['name'];	

			var elmnt = document.getElementById('9');
			var x = elmnt.scrollLeft;
			var y = elmnt.scrollTop;
			console.log(' SCROLL TOP : '+elmnt.scrollTop);
			//console.log(elmnt.scrollTop+'-'+$scope.divtop+val);
			elmnt.scrollTop = 100;
			//$scope.divtop_final=$scope.divtop+val;

		}
	});		

	$rootScope.checkKeyDown = function(event)
	{
		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
				$rootScope.selectedIndex++;
			}else{
				$rootScope.selectedIndex = 0;
			}
		
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex-1 >= 0){
				$rootScope.selectedIndex--;
			}else{
				$rootScope.selectedIndex = $rootScope.suggestions.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			//console.log($rootScope.selectedIndex);
			// event.preventDefault();			
			// $rootScope.suggestions = [];
			// $rootScope.searchItems=[];		
			// $rootScope.selectedIndex = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex>-1){
				$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			console.log($rootScope.selectedIndex);
			event.preventDefault();
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex = -1;
		}else{
			$rootScope.search();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.searchelement);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions = [];			
			$rootScope.selectedIndex = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];			
				$rootScope.selectedIndex = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide = function(index)
	{
		$scope[$rootScope.searchelement]= $rootScope.suggestions[index]['name'];

		if($rootScope.searchelement=='product_id_name')
		{
						
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"product_id/"+id;					
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name']=value.name; // NAME 	
					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; // NAME 	
					$scope['Synonym']=value.Synonym; // NAME 
					$scope.previous_transaction_details(value.id,0,'PRODUCT_WISE_RATE_VALIDATION',$scope.tbl_party_id);														
				});
			});
		}
		if($rootScope.searchelement=='product_id_name_mixer')
		{
			//var str=$scope.product_id_name_mixer;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"product_id/"+id;	
			console.log(data_link);		
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name_mixer']=value.name; // NAME 	
					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; // NAME 	
					$scope['Synonym']=value.Synonym; // NAME 
					$scope['TRANTYPE']='MIXER'; // NAME 
					$scope.previous_transaction_details(value.id,0,'PRODUCT_WISE_RATE_VALIDATION',$scope.tbl_party_id);																
				});
			});
		}

		if($rootScope.searchelement=='doctor_ledger_id_name')
		{
			//var str=$scope.doctor_ledger_id_name;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"doctor_ledger_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['doctor_ledger_id']=value.id;  //ACTUAL ID
					$scope['doctor_ledger_id_name']=value.name; // NAME 					
				});
			});
		}

		if($rootScope.searchelement=='tbl_party_id_name')
		{
			//var str=$scope.tbl_party_id_name;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"tbl_party_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['tbl_party_id']=value.id;  //ACTUAL ID
					$scope['tbl_party_id_name']=value.name; // NAME 					
				});
			});
		}


		if($rootScope.searchelement=='BILL_TYPE')
		{$scope['BILL_TYPE']=	$rootScope.suggestions[index]['name'];}

		if($rootScope.searchelement=='hq_id_name')
		{
			$scope['hq_id_name']=	$rootScope.suggestions[index]['name'];
			$scope['hq_id']=	$rootScope.suggestions[index]['id'];
		}

		if($rootScope.searchelement=='batchno')
		{			
			$scope['batchno']= $rootScope.suggestions[index]['batchno'];
			$scope['exp_monyr']= $rootScope.suggestions[index]['exp_monyr'];
			$scope['mfg_monyr']=$rootScope.suggestions[index]['mfg_monyr']; 
			$scope['rate']=$rootScope.suggestions[index]['srate']; 
			$scope['srate']=$rootScope.suggestions[index]['srate']; 
			$scope['mrp']=$rootScope.suggestions[index]['mrp']; 
			$scope['ptr']=$rootScope.suggestions[index]['ptr']; 
			$scope['rackno']=$rootScope.suggestions[index]['rackno']; 
			$scope['AVAILABLE_QTY']=$rootScope.suggestions[index]['AVAILABLE_QTY'];  
			//$scope['purchase_rate']=$rootScope.suggestions[index]['purchase_rate'];  //purchase rate
			$scope['PURCHASEID']=$rootScope.suggestions[index]['PURCHASEID'];  
			$scope.previous_transaction_details($scope.product_id,$scope.batchno,'PRODUCT_BATCH_WISE_PURCHASE_RATE_VALIDATION');		
		}
			
		 $rootScope.suggestions=[];
		 $rootScope.searchItems=[];	
		 $rootScope.batchlist=[];			
		 $rootScope.selectedIndex = -1;
	};
	//===================END SEARCH SECTION =========================================

	
	$rootScope.search_batch = function(searchelement)
	{			
		$rootScope.searchelement=searchelement;
		$rootScope.suggestions_batch = [];
		$rootScope.searchItems=[];

		var data_link=BaseUrl+"batchno/"+$scope.product_id;
		console.log(data_link);			
		$http.get(data_link)
		.then(function(response) {
		$rootScope.suggestions_batch=response.data	;
		});			

	};
	
	$rootScope.$watch('selectedIndex_batch',function(val)
	{		
		if(val !== -1) {	
			$scope['batchno'] =	$rootScope.suggestions_batch[$rootScope.selectedIndex_batch].batchno;		
		}
	});		

	$rootScope.checkKeyDown_batch = function(event){
		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch+1 < $rootScope.suggestions_batch.length){
				$rootScope.selectedIndex_batch++;
			}else{
				$rootScope.selectedIndex_batch = 0;
			}
		
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch-1 >= 0){
				$rootScope.selectedIndex_batch--;
			}else{
				$rootScope.selectedIndex_batch = $rootScope.suggestions_batch.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			//console.log($rootScope.selectedIndex);
			// event.preventDefault();			
			// $rootScope.suggestions_batch = [];
			// $rootScope.searchItems=[];		
			// $rootScope.selectedIndex_batch = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex_batch>-1){
				$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);			
			event.preventDefault();
			$rootScope.suggestions_batch = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex_batch = -1;
		}else{
			$rootScope.search_batch();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.batchno);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions_batch = [];			
			$rootScope.selectedIndex_batch = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp_batch = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions_batch = [];
				$rootScope.searchItems=[];			
				$rootScope.selectedIndex_batch = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide_batch = function(index)
	{

	$scope[$rootScope.searchelement]= $rootScope.suggestions_batch[index].batchno;
		
	
	console.log($rootScope.suggestions_batch[index].batchno);
	
		
	$scope['PURCHASEID']=$rootScope.suggestions_batch[index].id;  
	$scope['exp_monyr']=$rootScope.suggestions_batch[index].exp_monyr;  
	$scope['mfg_monyr']=$rootScope.suggestions_batch[index].mfg_monyr; 
	$scope['purchase_rate']=$rootScope.suggestions_batch[index].rate; 
	$scope['rate']=$rootScope.suggestions_batch[index].srate; 
	$scope['srate']=$rootScope.suggestions_batch[index].srate; 
	$scope['mrp']=$rootScope.suggestions_batch[index].mrp; 
	$scope['ptr']=$rootScope.suggestions_batch[index].ptr; 
	$scope['AVAILABLE_QTY']=$rootScope.suggestions_batch[index].AVAILABLE_QTY; 
	
	$rootScope.suggestions_batch=[];
		 $rootScope.searchItems=[];		
		 $rootScope.selectedIndex = -1;
	};
	
	//===================END batch SEARCH SECTION =========================================


	$scope.savedata=function()
	{
		//console.log('purchase_rate'+$scope.purchase_rate);
		
		$scope.spiner='ON';
		//	$scope.savemsg='Please Wait data saving....';
		var data_link=BaseUrl+"SAVE";
		var success={};		
		var data_save = {
			'id_header': $scope.get_set_value($scope.id_header,'num','SETVALUE'),
			'id_detail': $scope.get_set_value($scope.id_detail,'num','SETVALUE'),
			'doctor_ledger_id': $scope.get_set_value($scope.doctor_ledger_id,'num','SETVALUE'),
			'invoice_no': $scope.get_set_value($scope.invoice_no,'str','SETVALUE'),
			'product_id': $scope.get_set_value($scope.product_id,'num','SETVALUE'),
			'MIX_RAW_LINK_ID': $scope.get_set_value($scope.MIX_RAW_LINK_ID,'num','SETVALUE'),
			'RELATED_TO_MIXER': $scope.get_set_value($scope.RELATED_TO_MIXER,'str','SETVALUE'),	
			'product_name_mixture': $scope.get_set_value($scope.product_name_mixture,'str','SETVALUE'),
			'batchno_mixture': $scope.get_set_value($scope.batchno_mixture,'str','SETVALUE'),
			'qnty_mixture': $scope.get_set_value($scope.qnty_mixture,'num','SETVALUE'),
			'rate_mixture': $scope.get_set_value($scope.rate_mixture,'num','SETVALUE'),
			'mfg_monyr_mixture': $scope.get_set_value($scope.mfg_monyr_mixture,'str','SETVALUE'),
			'exp_monyr_mixture': $scope.get_set_value($scope.exp_monyr_mixture,'str','SETVALUE'),		
			'tbl_party_id': $scope.get_set_value($scope.tbl_party_id,'num','SETVALUE'),
			'BILL_TYPE': $scope.get_set_value($scope.BILL_TYPE,'str','SETVALUE'),
			'hq_id': $scope.get_set_value($scope.hq_id,'num','SETVALUE'),
			'invoice_date': $scope.get_set_value($scope.invoice_date,'str','SETVALUE'),
			'challan_no': $scope.get_set_value($scope.challan_no,'str','SETVALUE'),
			'challan_date': $scope.get_set_value($scope.challan_date,'str','SETVALUE'),
			'tbl_party_id_name': $scope.get_set_value($scope.tbl_party_id_name,'str','SETVALUE'),
			'comment': $scope.get_set_value($scope.comment,'str','SETVALUE'),
			'product_id_name': $scope.get_set_value($scope.product_id_name,'str','SETVALUE'),
			'batchno': $scope.get_set_value($scope.batchno,'str','SETVALUE'),
			'qnty': $scope.get_set_value($scope.qnty,'num','SETVALUE'),
			'exp_monyr': $scope.get_set_value($scope.exp_monyr,'str','SETVALUE'),
			'mfg_monyr': $scope.get_set_value($scope.mfg_monyr,'str','SETVALUE'),
			'rate': $scope.get_set_value($scope.rate,'num','SETVALUE'),
			'mrp': $scope.get_set_value($scope.mrp,'num','SETVALUE'),
			'ptr': $scope.get_set_value($scope.ptr,'num','SETVALUE'),
			'srate': $scope.get_set_value($scope.srate,'num','SETVALUE'),
			'tax_per': $scope.get_set_value($scope.tax_per,'num','SETVALUE'),	
			'disc_per': $scope.get_set_value($scope.disc_per,'num','SETVALUE'),	
			'tax_ledger_id': $scope.get_set_value($scope.tax_ledger_id,'num','SETVALUE'),
			'PURCHASEID': $scope.get_set_value($scope.PURCHASEID,'num','SETVALUE'),
			'purchase_rate': $scope.get_set_value($scope.purchase_rate,'num','SETVALUE'),			
			'Synonym': $scope.get_set_value($scope.Synonym,'num','SETVALUE'),
			'label_print': $scope.get_set_value($scope.label_print,'num','SETVALUE'),
			'disc_per2': $scope.get_set_value($scope.disc_per2,'num','SETVALUE'),
			'tot_cash_discount': $scope.get_set_value($scope.tot_cash_discount,'num','SETVALUE'),
			'submit_type': $scope.get_set_value($scope.submit_type,'str','SETVALUE')
											
		};	
	
		console.log(data_save);
		
		var config = {headers : 
			{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
		}
		//$http.post(data_link, data,config)
		$http.post(data_link,data_save,config)
		.then (function success(response){

			console.log('masg '+response.data.return_msg);			
			$scope.savemsg=response.data.return_msg;
			$scope.get_set_value(response.data,'','REFRESH');
			//$scope.print_invoice(response.data,'','REFRESH');
			document.getElementById('product_id_name').focus();
		},
		function error(response){
			$scope.errorMessage = 'Error adding user!';
			$scope.message = '';
	  });

	}
		
	$scope.submit_print=function()
	{
		$scope.spiner='ON';
		var data_link=BaseUrl+"FINAL_SUBMIT/"+$scope.id_header;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{$scope.ListOfTransactions=response.data;});

		var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{$scope.listOfDetails=response.data;});

		var data_link=BaseUrl+"DTLLISTMIX/"+$scope.MIX_RAW_LINK_ID;
		console.log(data_link);
	 $http.get(data_link).then(function(response) 
	 {$scope.listOfDetails_mix=response.data;});
	 
	 $scope.VIEWALLVALUE($scope.id_header);
	 
		// var data_link=BaseUrl+"print_invoice/"+$scope.id_header+'/'+'INVOICE';		
		// console.log(data_link);
		// window.popup(data_link); 

		$scope.spiner='OFF';

	}

	$scope.get_set_value=function(datavalue,datatype,operation)
	{
			if(operation=='SETVALUE')
			{
				if(angular.isUndefined(datavalue)==true)
				{
					if(datatype=='num')
					{return 0;}
					if(datatype=='str')
					{return '';}		
				}
				else
				{return datavalue;}
		}
		if(operation=='DRCRCHECKING')
		{
			var savestatus='OK';


			
			// var data_link=BaseUrl+"checking_validation/"+id;		
			// $http.get(data_link).then(function(response){
			// 	angular.forEach(response.data,function(value,key){
			// 		$scope['tbl_party_id']=value.id;  //ACTUAL ID
			// 		$scope['tbl_party_id_name']=value.name; // NAME 					
			// 	});
			// });


			if($scope.doctor_ledger_id_name == null || $scope.doctor_ledger_id_name === "")			
			{
				console.log('DOCTOR NAME  '+ $scope.doctor_ledger_id_name);				
				$scope.doctor_ledger_id=0;
			}

			if($scope.hq_id_name == null || $scope.hq_id_name === "")			
			{$scope.hq_id=0;}
						
			if($scope.invoice_date == null || $scope.invoice_date === "")			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER INVOICE DATE';
			document.getElementById('invoice_date').focus();
			}
			if($scope.tbl_party_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PARTY';
			document.getElementById('tbl_party_id_name').focus();
			}

			if($scope.product_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PRODUCT';
			document.getElementById('product_id_name').focus();
			}

			if($scope.product_profit==false)			
			{
			//savestatus='NOTOK';$scope.savemsg='Please Enter Correct sale rate!';
			//document.getElementById('rate').focus();
			}

			
		
			if(savestatus=='OK')
			{			
				$scope.product_name_mixture='NA';
				$scope.batchno_mixture='NA';
				$scope.qnty_mixture=0;
				$scope.rate_mixture=0;
				$scope.mfg_monyr_mixture='NA';
				$scope.exp_monyr_mixture='NA';
				$scope.MIX_RAW_LINK_ID=0;
				$scope.RELATED_TO_MIXER='NO';
				$scope.savedata();
				
			}

		}

		if(operation=='ADDMIXTURE')
		{
			var savestatus='OK';
									
			if($scope.product_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PRODUCT';
			document.getElementById('product_id_name').focus();
			}

			if($scope.invoice_date == null || $scope.invoice_date === "")			
			{
				savestatus='NOTOK';$scope.savemsg='ENTER INVOICE DATE';
				document.getElementById('invoice_date').focus();
			}

			if($scope.tbl_party_id == '0')			
			{
				savestatus='NOTOK';$scope.savemsg='ENTER PARTY';
				document.getElementById('tbl_party_id_name').focus();
			}

			if($scope.product_profit==false)			
			{
			savestatus='NOTOK';$scope.savemsg='Please Enter Correct sale rate!';
			document.getElementById('rate').focus();
			}
		
			if(savestatus=='OK')
			{
				//$scope.savedata_mixture();
				$scope.RELATED_TO_MIXER='YES';
				$scope.savedata();					
				$scope.savemsg='Receord Has been saved Successfully!';
			}
		}

		if(operation=='RESET_MIXER_PAGE')
		{
			$scope.id_detail='';
			$scope.MIX_RAW_LINK_ID='';
		}

		
		if(operation=='REFRESH')
		{		
			//HEADER SECTION

			$scope.id_header=datavalue.id_header;
		  //console.log('After save id_header :'+$scope.id_header)
			$scope.invoice_no=datavalue.invoice_no;
			$scope.invoice_date=datavalue.invoice_date;
			$scope.challan_no=datavalue.challan_no;
			$scope.challan_date=datavalue.challan_date;
			$scope.tbl_party_id_name=datavalue.tbl_party_id_name;
			$scope.tbl_party_id=datavalue.tbl_party_id;
			$scope.comment=datavalue.comment;
			$scope.MIX_RAW_LINK_ID=datavalue.MIX_RAW_LINK_ID;

			
			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.product_id_name_mixer=$scope.disc_per2=$scope.Synonym=$scope.label_print='';
			

			//data list
			 var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
			 console.log(data_link);
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});

			 var data_link=BaseUrl+"DTLLISTMIX/"+$scope.MIX_RAW_LINK_ID;
			 console.log(data_link);
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails_mix=response.data;});
			
			$scope.VIEWALLVALUE($scope.id_header);

			$scope.spiner='OFF';
			//$scope.consignment_value();
			//$scope.GetAllConsignment($scope.startdate,$scope.enddate);

		}
		if(operation=='NEWENTRY')
		{		
			
			//HEADER SECTION
			$scope.id_header='';
			$scope.invoice_no='';
			//$scope.invoice_date='';
			$scope.challan_no='';
			$scope.challan_date='';
			$scope.tbl_party_id_name='';
			$scope.tbl_party_id='';
			$scope.comment=$scope.comment='';
			$scope.BILL_TYPE=$scope.doctor_ledger_id_name=$scope.hq_id_name='';
			


			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.disc_per2=$scope.Synonym=$scope.label_print='';
			//data list
			 var data_link=BaseUrl+"DTLLIST/"+0;
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});

			 document.getElementById('2').focus();
		}

	

		if(operation=='VIEWDTLMIX')
		{	
			
			$scope['product_id_name']='';  
			$scope['product_id_name_mixer']='';  					
			$scope['product_id']='0';   					
			$scope['batchno']='';  
			$scope['qnty']=='';  
			$scope['exp_monyr']='';  
			$scope['mfg_monyr']='';  
			$scope['rate']='';  
			$scope['mrp']='';  	
			$scope['ptr']='';  
			$scope['srate']='';  
			$scope['tax_per']='';  
			$scope['tax_ledger_id']='';  
			$scope['disc_per']='';  
			$scope['id_detail']='';

			var data_link=BaseUrl+"DTLLISTMIX/"+datavalue;
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails_mix=response.data;});
			
			var data_link=BaseUrl+"VIEWDTL/"+datavalue;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){
					//$scope['id_detail']=value.id;  
					$scope['product_name_mixture']=value.product_id_name; 
					$scope['batchno_mixture']=value.batchno;  
					$scope['qnty_mixture']=value.qnty;  
					$scope['rate_mixture']=value.rate;
					$scope['exp_monyr_mixture']=value.exp_monyr;  
					$scope['mfg_monyr_mixture']=value.mfg_monyr; 
					$scope['MIX_RAW_LINK_ID']=value.id; 
					$scope['purchase_rate']=value.purchase_rate;
				});			
				
			});
		}

		

		if(operation=='VIEWALLVALUE')
		{	
			var data_link=BaseUrl+"DTLLIST/"+datavalue;
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails=response.data;});

			$scope.VIEWALLVALUE(datavalue);
	
		}

	}

	$scope.view_dtl=function(dtl_id,type)
	{

			var data_link=BaseUrl+"VIEWDTL/"+dtl_id;
		//	console.log('VIEWDTL NORMANL :'+data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){
					$scope['id_detail']=value.id;  
					$scope['product_id_name']=value.product_id_name;  
					$scope['product_id_name_mixer']=value.product_id_name;  					
					$scope['product_id']=value.product_id;  					
					$scope['batchno']=value.batchno;  
					$scope['qnty']=value.qnty;  
					$scope['exp_monyr']=value.exp_monyr;  
					$scope['mfg_monyr']=value.mfg_monyr; 
					$scope['rate']=value.rate;
					$scope['mrp']=value.mrp;	
					$scope['ptr']=value.ptr;
					$scope['srate']=value.srate;
					$scope['purchase_rate']=value.purchase_rate;
					$scope['PURCHASEID']=value.PURCHASEID;
					$scope['tax_per']=value.tax_per;
					$scope['tax_ledger_id']=value.tax_ledger_id;
					$scope['disc_per']=value.disc_per;
					$scope['disc_per2']=value.disc_per2;
					$scope['Synonym']=value.Synonym;
					$scope['label_print']=value.label_print;
				
				});			
				
			});
			if(type=='FINISH'){document.getElementById(6).focus();}
			if(type=='MIXTURE'){document.getElementById(6).focus();}
					
	}

	$scope.VIEWALLVALUE=function(invoice_id)
	{

		var data_link=BaseUrl+"VIEWALLVALUE/"+invoice_id;
			console.log('VIEWALLVALUE '+data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){

					$scope['id_header']=value.id_header;  					
					$scope['invoice_no']=value.invoice_no;  
					$scope['invoice_date']=value.invoice_date;  
					$scope['challan_no']=value.challan_no;  
					$scope['challan_date']=value.challan_date;  
					$scope['tbl_party_id_name']=value.tbl_party_id_name;  
					$scope['tbl_party_id']=value.tbl_party_id;								
					$scope['comment']=value.comment;
					$scope['tot_cash_discount']=value.tot_cash_discount;
					$scope['total_amt']=value.total_amt;  
					$scope['tot_discount']=value.tot_discount;	
					$scope['tot_taxable_amt']=$scope['total_amt']-$scope['tot_discount'];								
					$scope['totvatamt']=value.totvatamt;
					$scope['grandtot']=value.grandtot;
					$scope['doctor_ledger_id']=value.doctor_ledger_id;
					$scope['doctor_ledger_id_name']=value.doctor_ledger_id_name;

					$scope['BILL_TYPE']=value.BILL_TYPE;
					$scope['hq_id']=value.hq_id;
					$scope['hq_id_name']=value.hq_id_name;
					
					
				});	
				
			});		

	}

	
	$scope.barcode_value=function(barcodefrom,event)
	{

		//	console.log(barcodefrom+event );


		if(event.keyCode === 13){

			if(barcodefrom=='barcode')
			{	
				var str=$scope.barcode;
				var strid =str.split("|");
			}
			if(barcodefrom=='barcodemix')
			{	
				var str=$scope.barcodemix;
				var strid =str.split("|");
			}
			if(barcodefrom=='billbarcode')
			{	
				var str=$scope.billbarcode;
				var strid =str.split("|");			
				$scope.get_set_value(strid[0],'','VIEWALLVALUE')		
			}
			console.log('strid'+strid);

			$scope.barcodemix=$scope.barcode=$scope.billbarcode='';

			if(barcodefrom=='barcode' || barcodefrom=='barcodemix')
			{
				var data_link=BaseUrl+"VIEWDTL/"+strid[0];
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
						angular.forEach(response.data,function(value,key){
						$scope['id_detail']=value.id;  
						$scope['product_id_name']=value.product_id_name;  
						$scope['product_id_name_mixer']=value.product_id_name;  					
						$scope['product_id']=value.product_id;  					
						$scope['batchno']=value.batchno;  
						$scope['qnty']=value.qnty;  
						$scope['exp_monyr']=value.exp_monyr;  
						$scope['mfg_monyr']=value.mfg_monyr; 
						$scope['rate']=value.srate;
						$scope['mrp']=value.mrp;	
						$scope['ptr']=value.ptr;
						$scope['srate']=value.srate;
						$scope['tax_per']=value.tax_per;
						$scope['tax_ledger_id']=value.tax_ledger_id;
						$scope['disc_per']=value.disc_per;
					
					});			
					
				});
			}
			
			if(barcodefrom=='billbarcode')
			{
				var data_link=BaseUrl+"DTLLIST/"+strid[0];
				$http.get(data_link).then(function(response) 
				{$scope.listOfDetails=response.data;});

				var data_link=BaseUrl+"VIEWALLVALUE/"+strid[0];
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
					angular.forEach(response.data,function(value,key){

						$scope['id_header']=value.id_header;  					
						$scope['invoice_no']=value.invoice_no;  
						$scope['invoice_date']=value.invoice_date;  
						$scope['challan_no']=value.challan_no;  
						$scope['challan_date']=value.challan_date;  
						$scope['tbl_party_id_name']=value.tbl_party_id_name;  
						$scope['tbl_party_id']=value.tbl_party_id;							
						$scope['doctor_ledger_id_name']=value.doctor_ledger_id_name;  
						$scope['doctor_ledger_id']=value.doctor_ledger_id;	
						$scope['comment']=value.comment;
					});	
					
				});		

			}

		}

	};

	$scope.GetAllList=function(fromdate,todate)
	{
			//var BaseUrl=domain_name+"Primary_sale_controller/ConsignmentList/";
			//data list GetAllConsignment			
			console.log($scope.startdate);
			var data_link=BaseUrl+'GetAllList/PAYMENT/-/-/'+fromdate+'/'+todate;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				$scope.ListOfTransactions=response.data;
			});
	};
	
	$scope.print_invoice = function(printtype) 
	{ 
		var data_link=BaseUrl+"print_invoice/"+$scope.id_header+'/'+printtype;
		console.log(data_link);
		//	$http.get(data_link).then(function(response){});
		window.popup(data_link); 
		
	};


	$scope.print_label = function(id_header,PRINTTYPE) 
	{ 
		var BaseUrl=domain_name+"Project_controller/print_all/";
		var data_link=BaseUrl+id_header+'/'+PRINTTYPE;
		window.popup(data_link); 
	};

}]);
//************************ACCOUNT SALE END*****************************************//



//************************ACCOUNT SALE START*****************************************//
app.controller('sale_return',['$scope','$rootScope','$http','$window','Sale_test',
function($scope,$rootScope,$http,$window,Sale_test)
{
	"use strict";

	var CurrentDate=new Date();
	var year = CurrentDate.getFullYear();
	var month = CurrentDate.getMonth()+1;
	var dt = CurrentDate.getDate();
  
	if (dt < 10) {	dt = '0' + dt;}
	if (month < 10) {month = '0' + month;}
	$scope.invoice_date=year+'-' + month + '-'+dt;
	
	//$scope.todate=year+'-' + month + '-'+dt;


	$scope.spiner='OFF';
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/sale_return/";
	$scope.tran_date=$rootScope.tran_date;
	$scope.product_profit=true;

		$scope.previous_transaction_details=function(product_id,batchno,validation_type)
		{
			if(validation_type=='PRODUCT_WISE_RATE_VALIDATION')
			{
					// var data_link=BaseUrl+"previous_transaction_details/"+product_id+'/'+batchno+'/'+validation_type;					
					// console.log(data_link);					
					// $http.get(data_link).then(function(response){
					// 	angular.forEach(response.data,function(value,key){
					// 		$scope.savemsg=value.msg; 
					// 	});
					// });
			}
			if(validation_type=='PRODUCT_BATCH_WISE_PURCHASE_RATE_VALIDATION')
			{
					// var data_link=BaseUrl+"previous_transaction_details/"+product_id+'/'+batchno+'/'+validation_type;			
					// console.log(data_link);					
					// $http.get(data_link).then(function(response){
					// 	angular.forEach(response.data,function(value,key){
					// 		var disc_amt1=Number($scope.rate)*Number($scope.disc_per/100); 
					// 		var after_first_disc_amount=Number($scope.rate-disc_amt1);
					// 		var disc_amt2=Number(after_first_disc_amount*$scope.disc_per2/100);
					// 		var salerate=Number($scope.rate-disc_amt1-disc_amt2);
					// 		console.log('salerate'+salerate+' Purchase Rate'+value.purchase_rate);
					// 		if(salerate<value.purchase_rate)
					// 		{$scope.savemsg='Your Purchase Rate:'+value.purchase_rate+' | Your Sale rate '+salerate; $scope.product_profit=false;}
					// 		else
					// 		{$scope.savemsg='You are in profit.Which is :'+Math.round(Number(salerate-value.purchase_rate),2)+' / Qnty'; $scope.product_profit=true;}


					// 	});
					// });
			}
			
		}

		$scope.mainOperation=function(event,element_name)
		{	
			
			console.log('element_name '+element_name);
			if(event.keyCode === 13)
				{	
					element_name=Number(element_name+1);			
					document.getElementById(element_name).focus();		
				}				

				if(element_name===20)
				{
					$scope.get_set_value('','','DRCRCHECKING');
				  document.getElementById(8).focus();			
				}
				if(element_name===114)
				{
					$scope.get_set_value('','','ADDMIXTURE');
				  document.getElementById(102).focus();			
				}
		 }

	
		 
			$scope.delete_product=function(id)
			{	
			 
				var data_link=domain_name+"Accounts_controller/AccountsTransactions/DELETE_PRODUCT";			
				var success={};		
				var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
				console.log(data_save);	
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config).then (function success(response){

					$scope.get_set_value(response.data.id_header,'','VIEWALLVALUE');
					document.getElementById('product_id_name').focus();

					//console.log('ID HEADER '+response.data.id_header);
				  //	$scope.get_set_value(response.data,'','REFRESH');
				  //	document.getElementById('product_id_name').focus();
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});

			}

			$scope.delete_invoice=function(id)
			{	
					var data_link=BaseUrl+"DELETE_INVOICE";		
					console.log(data_link);	
					var success={};		
					var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
					console.log(data_save);	
					var config = {headers : 
						{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
					}
					//$http.post(data_link, data,config)
					$http.post(data_link,data_save,config).then (function success(response){

						$scope.GetAllList($scope.startdate,$scope.enddate);
					},
					function error(response){
						$scope.errorMessage = 'Error adding user!';
						$scope.message = '';
					});
			}


		$rootScope.partylist= [];
		$rootScope.doctorlist= [];
		$rootScope.productlist=[];			
		$rootScope.csrlist=[];			
		$rootScope.billtypelist=[];		
	
		
			$scope.savemsg='All master Loading. Please wait ...... '; 
		
			var data_link=query_result_link+"32/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.doctorlist.push({id:value.id,name:value.acc_name});
				});
			});

			var data_link=query_result_link+"33/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.partylist.push({id:value.id,name:value.acc_name});
				});
			});

			var data_link=query_result_link+"36/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.csrlist.push({id:value.id,name:value.name});
				});
			});

			var data_link=query_result_link+"37/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.billtypelist.push({id:value.id,name:value.FieldID});
				});
			});

			// var data_link=query_result_link+"34/";
			// console.log(data_link);
			// $http.get(data_link).then(function(response){
			// 	angular.forEach(response.data,function(value,key){					
			// 		$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
			// 		$scope.savemsg='All Master Loaded.';
			// 	});
			// });

			var data_link=BaseUrl+"product_id_name/";							
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
					$scope.savemsg='All Master Loaded.';
				});
			});


		//	console.log($rootScope.partylist);
			
		$scope.divtop=0;
		$scope.divtop_final=0;
		$rootScope.batchlist=[];		

		$rootScope.search = function(searchelement)
		{					
					
				$rootScope.searchelement=searchelement;
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];
				console.log($rootScope.searchelement);		

				if($rootScope.searchelement=='product_id_name')
				{
					//Sale_test.list_items($rootScope.searchelement,$scope.product_id_name);
					$rootScope.searchItems=$rootScope.productlist;
				}
				else if($rootScope.searchelement=='product_id_name_mixer')
				{
					//Sale_test.list_items($rootScope.searchelement,$scope.product_id_name_mixer);
					$rootScope.searchItems=$rootScope.productlist;
				}
				else if($rootScope.searchelement=='doctor_ledger_id_name')
				{$rootScope.searchItems=$rootScope.doctorlist; }
				else if($rootScope.searchelement=='tbl_party_id_name')
				{$rootScope.searchItems=$rootScope.partylist; 	}	
				else if($rootScope.searchelement=='BILL_TYPE')
				{$rootScope.searchItems=$rootScope.billtypelist; 	}	
				else if($rootScope.searchelement=='hq_id_name')
				{$rootScope.searchItems=$rootScope.csrlist; 	}	
				else if($rootScope.searchelement=='batchno')
				{
					var data_link=BaseUrl+"batchno/"+$scope.product_id+'/'+$scope.tbl_party_id;	
					console.log(' New '+data_link);
					$http.get(data_link).then(function(response){
						angular.forEach(response.data,function(value,key){	
							if($rootScope.batchlist.indexOf($rootScope.batchlist[key]) === -1) {				
							$rootScope.batchlist.push({
								id:value.id,
								name:value.batchno+'//',		
								batchno:value.batchno,									
								PURCHASEID:value.id,
								AVAILABLE_QTY:value.qty_available,
								exp_monyr:value.exp_monyr,
								mfg_monyr:value.mfg_monyr,
								rate:value.rate,
								srate:value.srate,
								mrp:value.mrp,
								rackno:value.rackno,
								ptr:value.ptr});
							}
						});
					});		
					$rootScope.searchItems=$rootScope.batchlist;
					
					console.log($rootScope.searchItems);
				}	
				else
				{//Sale_test.list_items($rootScope.searchelement,$scope.product_id);}
				}

				//	console.log($rootScope.searchItems);
				// DIS AGROPYRUM - 10M/10000 (1 X 1 PCS)
				
				$rootScope.searchItems.sort();	
				var myMaxSuggestionListLength = 0;
				for(var i=0; i<$rootScope.searchItems.length; i++)
				{
					
						var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].name);
						var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
						if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) >=0){
							
							if($rootScope.searchelement=='batchno')
							{
								$rootScope.suggestions.push(
									{
										id:$rootScope.searchItems[i].id,
										name:$rootScope.searchItems[i].name,
										batchno:$rootScope.searchItems[i].batchno,
										PURCHASEID:$rootScope.searchItems[i].PURCHASEID,
										AVAILABLE_QTY:$rootScope.searchItems[i].AVAILABLE_QTY,
										exp_monyr:$rootScope.searchItems[i].exp_monyr,
										mfg_monyr:$rootScope.searchItems[i].mfg_monyr,
										srate:$rootScope.searchItems[i].srate,
										mrp:$rootScope.searchItems[i].mrp,
										rackno:$rootScope.searchItems[i].rackno,
										ptr:$rootScope.searchItems[i].ptr});
							 }
							else if($rootScope.searchelement=='product_id_name' || $rootScope.searchelement=='product_id_name_mixer')
							{	$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name,available_qnty:$rootScope.searchItems[i].available_qnty} );}
							else
							{$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name} );}

							myMaxSuggestionListLength += 1;
							if(myMaxSuggestionListLength === 1500)
							{break;}
						}
						
				}

				console.log('suggestions : '+$rootScope.suggestions);
		};
	
	$rootScope.$watch('selectedIndex',function(val){		
		if(val !== -1) {					
			$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex]['name'];	

			var elmnt = document.getElementById("prodt");
			var x = elmnt.scrollLeft;
			var y = elmnt.scrollTop;
			console.log(elmnt.scrollTop+'-'+$scope.divtop+val);
			elmnt.scrollTop = 100;
			//$scope.divtop_final=$scope.divtop+val;

		}
	});		

	$rootScope.checkKeyDown = function(event)
	{
		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
				$rootScope.selectedIndex++;
			}else{
				$rootScope.selectedIndex = 0;
			}
		
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex-1 >= 0){
				$rootScope.selectedIndex--;
			}else{
				$rootScope.selectedIndex = $rootScope.suggestions.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			//console.log($rootScope.selectedIndex);
			// event.preventDefault();			
			// $rootScope.suggestions = [];
			// $rootScope.searchItems=[];		
			// $rootScope.selectedIndex = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex>-1){
				$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			console.log($rootScope.selectedIndex);
			event.preventDefault();
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex = -1;
		}else{
			$rootScope.search();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.searchelement);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions = [];			
			$rootScope.selectedIndex = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];			
				$rootScope.selectedIndex = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide = function(index)
	{
		$scope[$rootScope.searchelement]= $rootScope.suggestions[index]['name'];

		if($rootScope.searchelement=='product_id_name')
		{
						
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"product_id/"+id;					
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name']=value.name; // NAME 	
					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; // NAME 	
					$scope['Synonym']=value.Synonym; // NAME 
					$scope.previous_transaction_details(value.id,0,'PRODUCT_WISE_RATE_VALIDATION');														
				});
			});
		}
		if($rootScope.searchelement=='product_id_name_mixer')
		{
			//var str=$scope.product_id_name_mixer;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"product_id/"+id;	
			console.log(data_link);		
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name_mixer']=value.name; // NAME 	
					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; // NAME 	
					$scope['Synonym']=value.Synonym; // NAME 
					$scope['TRANTYPE']='MIXER'; // NAME 
					$scope.previous_transaction_details(value.id,0,'PRODUCT_WISE_RATE_VALIDATION');																
				});
			});
		}

		if($rootScope.searchelement=='doctor_ledger_id_name')
		{
			//var str=$scope.doctor_ledger_id_name;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"doctor_ledger_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['doctor_ledger_id']=value.id;  //ACTUAL ID
					$scope['doctor_ledger_id_name']=value.name; // NAME 					
				});
			});
		}

		if($rootScope.searchelement=='tbl_party_id_name')
		{
			//var str=$scope.tbl_party_id_name;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"tbl_party_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['tbl_party_id']=value.id;  //ACTUAL ID
					$scope['tbl_party_id_name']=value.name; // NAME 					
				});
			});
		}


		if($rootScope.searchelement=='BILL_TYPE')
		{$scope['BILL_TYPE']=	$rootScope.suggestions[index]['name'];}

		if($rootScope.searchelement=='hq_id_name')
		{
			$scope['hq_id_name']=	$rootScope.suggestions[index]['name'];
			$scope['hq_id']=	$rootScope.suggestions[index]['id'];
		}

		if($rootScope.searchelement=='batchno')
		{			
			$scope['batchno']= $rootScope.suggestions[index]['batchno'];
			$scope['exp_monyr']= $rootScope.suggestions[index]['exp_monyr'];
			$scope['mfg_monyr']=$rootScope.suggestions[index]['mfg_monyr']; 
			$scope['rate']=$rootScope.suggestions[index]['srate']; 
			$scope['srate']=$rootScope.suggestions[index]['srate']; 
			$scope['mrp']=$rootScope.suggestions[index]['mrp']; 
			$scope['ptr']=$rootScope.suggestions[index]['ptr']; 
			$scope['rackno']=$rootScope.suggestions[index]['rackno']; 
			$scope['AVAILABLE_QTY']=$rootScope.suggestions[index]['AVAILABLE_QTY'];  
			//$scope['purchase_rate']=$rootScope.suggestions[index]['purchase_rate'];  //purchase rate
			$scope['PURCHASEID']=$rootScope.suggestions[index]['PURCHASEID'];  
			$scope.previous_transaction_details($scope.product_id,$scope.batchno,'PRODUCT_BATCH_WISE_PURCHASE_RATE_VALIDATION');		
		}
			
		 $rootScope.suggestions=[];
		 $rootScope.searchItems=[];	
		 $rootScope.batchlist=[];			
		 $rootScope.selectedIndex = -1;
	};
	//===================END SEARCH SECTION =========================================

	/*
	$rootScope.search_batch = function(searchelement)
	{			
		$rootScope.searchelement=searchelement;
		$rootScope.suggestions_batch = [];
		$rootScope.searchItems=[];

		var data_link=BaseUrl+"batchno/"+$scope.product_id+'/'+$scope.tbl_party_id;
		console.log(data_link);			
		$http.get(data_link)
		.then(function(response) {
		$rootScope.suggestions_batch=response.data	;
		});			

	};
	
	$rootScope.$watch('selectedIndex_batch',function(val)
	{		
		if(val !== -1) {	
			$scope['batchno'] =	$rootScope.suggestions_batch[$rootScope.selectedIndex_batch].batchno;		
		}
	});		

	$rootScope.checkKeyDown_batch = function(event){
		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch+1 < $rootScope.suggestions_batch.length){
				$rootScope.selectedIndex_batch++;
			}else{
				$rootScope.selectedIndex_batch = 0;
			}
		
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch-1 >= 0){
				$rootScope.selectedIndex_batch--;
			}else{
				$rootScope.selectedIndex_batch = $rootScope.suggestions_batch.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			//console.log($rootScope.selectedIndex);
			// event.preventDefault();			
			// $rootScope.suggestions_batch = [];
			// $rootScope.searchItems=[];		
			// $rootScope.selectedIndex_batch = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex_batch>-1){
				$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);			
			event.preventDefault();
			$rootScope.suggestions_batch = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex_batch = -1;
		}else{
			$rootScope.search_batch();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.batchno);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions_batch = [];			
			$rootScope.selectedIndex_batch = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp_batch = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions_batch = [];
				$rootScope.searchItems=[];			
				$rootScope.selectedIndex_batch = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide_batch = function(index)
	{

	$scope[$rootScope.searchelement]= $rootScope.suggestions_batch[index].batchno;
		
	
	console.log($rootScope.suggestions_batch[index].batchno);
	
		
	$scope['PURCHASEID']=$rootScope.suggestions_batch[index].id;  
	$scope['exp_monyr']=$rootScope.suggestions_batch[index].exp_monyr;  
	$scope['mfg_monyr']=$rootScope.suggestions_batch[index].mfg_monyr; 
	$scope['purchase_rate']=$rootScope.suggestions_batch[index].rate; 
	$scope['rate']=$rootScope.suggestions_batch[index].srate; 
	$scope['srate']=$rootScope.suggestions_batch[index].srate; 
	$scope['mrp']=$rootScope.suggestions_batch[index].mrp; 
	$scope['ptr']=$rootScope.suggestions_batch[index].ptr; 
	$scope['AVAILABLE_QTY']=$rootScope.suggestions_batch[index].AVAILABLE_QTY; 
	
	$rootScope.suggestions_batch=[];
		 $rootScope.searchItems=[];		
		 $rootScope.selectedIndex = -1;
	};
	*/

	//===================END batch SEARCH SECTION =========================================


	$scope.savedata=function()
	{
		//console.log('purchase_rate'+$scope.purchase_rate);
		
		$scope.spiner='ON';
		//	$scope.savemsg='Please Wait data saving....';
		var data_link=BaseUrl+"SAVE";
		var success={};		
		var data_save = {
			'id_header': $scope.get_set_value($scope.id_header,'num','SETVALUE'),
			'id_detail': $scope.get_set_value($scope.id_detail,'num','SETVALUE'),
			'doctor_ledger_id': $scope.get_set_value($scope.doctor_ledger_id,'num','SETVALUE'),
			'product_id': $scope.get_set_value($scope.product_id,'num','SETVALUE'),
			'MIX_RAW_LINK_ID': $scope.get_set_value($scope.MIX_RAW_LINK_ID,'num','SETVALUE'),
			'RELATED_TO_MIXER': $scope.get_set_value($scope.RELATED_TO_MIXER,'str','SETVALUE'),	
			'product_name_mixture': $scope.get_set_value($scope.product_name_mixture,'str','SETVALUE'),
			'batchno_mixture': $scope.get_set_value($scope.batchno_mixture,'str','SETVALUE'),
			'qnty_mixture': $scope.get_set_value($scope.qnty_mixture,'num','SETVALUE'),
			'rate_mixture': $scope.get_set_value($scope.rate_mixture,'num','SETVALUE'),
			'mfg_monyr_mixture': $scope.get_set_value($scope.mfg_monyr_mixture,'str','SETVALUE'),
			'exp_monyr_mixture': $scope.get_set_value($scope.exp_monyr_mixture,'str','SETVALUE'),		
			'tbl_party_id': $scope.get_set_value($scope.tbl_party_id,'num','SETVALUE'),
			'BILL_TYPE': $scope.get_set_value($scope.BILL_TYPE,'str','SETVALUE'),
			'hq_id': $scope.get_set_value($scope.hq_id,'num','SETVALUE'),
			'invoice_date': $scope.get_set_value($scope.invoice_date,'str','SETVALUE'),
			'challan_no': $scope.get_set_value($scope.challan_no,'str','SETVALUE'),
			'challan_date': $scope.get_set_value($scope.challan_date,'str','SETVALUE'),
			'tbl_party_id_name': $scope.get_set_value($scope.tbl_party_id_name,'str','SETVALUE'),
			'comment': $scope.get_set_value($scope.comment,'str','SETVALUE'),
			'product_id_name': $scope.get_set_value($scope.product_id_name,'str','SETVALUE'),
			'batchno': $scope.get_set_value($scope.batchno,'str','SETVALUE'),
			'qnty': $scope.get_set_value($scope.qnty,'num','SETVALUE'),
			'exp_monyr': $scope.get_set_value($scope.exp_monyr,'str','SETVALUE'),
			'mfg_monyr': $scope.get_set_value($scope.mfg_monyr,'str','SETVALUE'),
			'rate': $scope.get_set_value($scope.rate,'num','SETVALUE'),
			'mrp': $scope.get_set_value($scope.mrp,'num','SETVALUE'),
			'ptr': $scope.get_set_value($scope.ptr,'num','SETVALUE'),
			'srate': $scope.get_set_value($scope.srate,'num','SETVALUE'),
			'tax_per': $scope.get_set_value($scope.tax_per,'num','SETVALUE'),	
			'disc_per': $scope.get_set_value($scope.disc_per,'num','SETVALUE'),	
			'tax_ledger_id': $scope.get_set_value($scope.tax_ledger_id,'num','SETVALUE'),
			'PURCHASEID': $scope.get_set_value($scope.PURCHASEID,'num','SETVALUE'),
			'purchase_rate': $scope.get_set_value($scope.purchase_rate,'num','SETVALUE'),			
			'Synonym': $scope.get_set_value($scope.Synonym,'num','SETVALUE'),
			'label_print': $scope.get_set_value($scope.label_print,'num','SETVALUE'),
			'disc_per2': $scope.get_set_value($scope.disc_per2,'num','SETVALUE'),
			'tot_cash_discount': $scope.get_set_value($scope.tot_cash_discount,'num','SETVALUE'),
			'submit_type': $scope.get_set_value($scope.submit_type,'str','SETVALUE')
											
		};	
	
		var config = {headers : 
			{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
		}
		//$http.post(data_link, data,config)
		$http.post(data_link,data_save,config)
		.then (function success(response){

			console.log('masg '+response.data.return_msg);			
			$scope.savemsg=response.data.return_msg;
			$scope.get_set_value(response.data,'','REFRESH');
			//$scope.print_invoice(response.data,'','REFRESH');
			document.getElementById('product_id_name').focus();
		},
		function error(response){
			$scope.errorMessage = 'Error adding user!';
			$scope.message = '';
	  });

	}
		
	$scope.submit_print=function()
	{
		$scope.spiner='ON';
		var data_link=BaseUrl+"FINAL_SUBMIT/"+$scope.id_header;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{$scope.ListOfTransactions=response.data;});

		var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{$scope.listOfDetails=response.data;});

		var data_link=BaseUrl+"DTLLISTMIX/"+$scope.MIX_RAW_LINK_ID;
		console.log(data_link);
	 $http.get(data_link).then(function(response) 
	 {$scope.listOfDetails_mix=response.data;});
	 
	 $scope.VIEWALLVALUE($scope.id_header);
	 
		// var data_link=BaseUrl+"print_invoice/"+$scope.id_header+'/'+'INVOICE';		
		// console.log(data_link);
		// window.popup(data_link); 

		$scope.spiner='OFF';

	}

	$scope.get_set_value=function(datavalue,datatype,operation)
	{
			if(operation=='SETVALUE')
			{
				if(angular.isUndefined(datavalue)==true)
				{
					if(datatype=='num')
					{return 0;}
					if(datatype=='str')
					{return '';}		
				}
				else
				{return datavalue;}
		}
		if(operation=='DRCRCHECKING')
		{
			var savestatus='OK';


			
			// var data_link=BaseUrl+"checking_validation/"+id;		
			// $http.get(data_link).then(function(response){
			// 	angular.forEach(response.data,function(value,key){
			// 		$scope['tbl_party_id']=value.id;  //ACTUAL ID
			// 		$scope['tbl_party_id_name']=value.name; // NAME 					
			// 	});
			// });


			if($scope.doctor_ledger_id_name == null || $scope.doctor_ledger_id_name === "")			
			{
				console.log('DOCTOR NAME  '+ $scope.doctor_ledger_id_name);				
				$scope.doctor_ledger_id=0;
			}

			if($scope.hq_id_name == null || $scope.hq_id_name === "")			
			{$scope.hq_id=0;}
						
			if($scope.invoice_date == null || $scope.invoice_date === "")			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER INVOICE DATE';
			document.getElementById('invoice_date').focus();
			}
			if($scope.tbl_party_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PARTY';
			document.getElementById('tbl_party_id_name').focus();
			}

			if($scope.product_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PRODUCT';
			document.getElementById('product_id_name').focus();
			}

			if($scope.product_profit==false)			
			{
			savestatus='NOTOK';$scope.savemsg='Please Enter Correct sale rate!';
			document.getElementById('rate').focus();
			}

			
		
			if(savestatus=='OK')
			{			
				$scope.product_name_mixture='NA';
				$scope.batchno_mixture='NA';
				$scope.qnty_mixture=0;
				$scope.rate_mixture=0;
				$scope.mfg_monyr_mixture='NA';
				$scope.exp_monyr_mixture='NA';
				$scope.MIX_RAW_LINK_ID=0;
				$scope.RELATED_TO_MIXER='NO';
				$scope.savedata();
				
			}

		}

		if(operation=='ADDMIXTURE')
		{
			var savestatus='OK';
									
			if($scope.product_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PRODUCT';
			document.getElementById('product_id_name').focus();
			}

			if($scope.invoice_date == null || $scope.invoice_date === "")			
			{
				savestatus='NOTOK';$scope.savemsg='ENTER INVOICE DATE';
				document.getElementById('invoice_date').focus();
			}

			if($scope.tbl_party_id == '0')			
			{
				savestatus='NOTOK';$scope.savemsg='ENTER PARTY';
				document.getElementById('tbl_party_id_name').focus();
			}

			// if($scope.product_profit==false)			
			// {
			// savestatus='NOTOK';$scope.savemsg='Please Enter Correct sale rate!';
			// document.getElementById('rate').focus();
			// }
		
			if(savestatus=='OK')
			{
				//$scope.savedata_mixture();
				$scope.RELATED_TO_MIXER='YES';
				$scope.savedata();					
				$scope.savemsg='Receord Has been saved Successfully!';
			}
		}

		if(operation=='RESET_MIXER_PAGE')
		{
			$scope.id_detail='';
			$scope.MIX_RAW_LINK_ID='';
		}

		
		if(operation=='REFRESH')
		{		
			//HEADER SECTION

			$scope.id_header=datavalue.id_header;
		  //console.log('After save id_header :'+$scope.id_header)
			$scope.invoice_no=datavalue.invoice_no;
			$scope.invoice_date=datavalue.invoice_date;
			$scope.challan_no=datavalue.challan_no;
			$scope.challan_date=datavalue.challan_date;
			$scope.tbl_party_id_name=datavalue.tbl_party_id_name;
			$scope.tbl_party_id=datavalue.tbl_party_id;
			$scope.comment=datavalue.comment;
			$scope.MIX_RAW_LINK_ID=datavalue.MIX_RAW_LINK_ID;

			
			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.product_id_name_mixer=$scope.disc_per2=$scope.Synonym=$scope.label_print='';
			

			//data list
			 var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
			 console.log(data_link);
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});

			 var data_link=BaseUrl+"DTLLISTMIX/"+$scope.MIX_RAW_LINK_ID;
			 console.log(data_link);
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails_mix=response.data;});
			
			$scope.VIEWALLVALUE($scope.id_header);

			$scope.spiner='OFF';
			//$scope.consignment_value();
			//$scope.GetAllConsignment($scope.startdate,$scope.enddate);

		}
		if(operation=='NEWENTRY')
		{		
			
			//HEADER SECTION
			$scope.id_header='';
			$scope.invoice_no='';
			//$scope.invoice_date='';
			$scope.challan_no='';
			$scope.challan_date='';
			$scope.tbl_party_id_name='';
			$scope.tbl_party_id='';
			$scope.comment=$scope.comment='';
			$scope.BILL_TYPE=$scope.doctor_ledger_id_name=$scope.hq_id_name='';
			


			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.disc_per2=$scope.Synonym=$scope.label_print='';
			//data list
			 var data_link=BaseUrl+"DTLLIST/"+0;
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});

			 document.getElementById('2').focus();
		}

	

		if(operation=='VIEWDTLMIX')
		{	
			
			$scope['product_id_name']='';  
			$scope['product_id_name_mixer']='';  					
			$scope['product_id']='0';   					
			$scope['batchno']='';  
			$scope['qnty']=='';  
			$scope['exp_monyr']='';  
			$scope['mfg_monyr']='';  
			$scope['rate']='';  
			$scope['mrp']='';  	
			$scope['ptr']='';  
			$scope['srate']='';  
			$scope['tax_per']='';  
			$scope['tax_ledger_id']='';  
			$scope['disc_per']='';  
			$scope['id_detail']='';

			var data_link=BaseUrl+"DTLLISTMIX/"+datavalue;
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails_mix=response.data;});
			
			var data_link=BaseUrl+"VIEWDTL/"+datavalue;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){
					//$scope['id_detail']=value.id;  
					$scope['product_name_mixture']=value.product_id_name; 
					$scope['batchno_mixture']=value.batchno;  
					$scope['qnty_mixture']=value.qnty;  
					$scope['rate_mixture']=value.rate;
					$scope['exp_monyr_mixture']=value.exp_monyr;  
					$scope['mfg_monyr_mixture']=value.mfg_monyr; 
					$scope['MIX_RAW_LINK_ID']=value.id; 
					$scope['purchase_rate']=value.purchase_rate;
				});			
				
			});
		}

		

		if(operation=='VIEWALLVALUE')
		{	
			var data_link=BaseUrl+"DTLLIST/"+datavalue;
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails=response.data;});

			$scope.VIEWALLVALUE(datavalue);
	
		}

	}

	$scope.view_dtl=function(dtl_id,type)
	{

			var data_link=BaseUrl+"VIEWDTL/"+dtl_id;
		//	console.log('VIEWDTL NORMANL :'+data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){
					$scope['id_detail']=value.id;  
					$scope['product_id_name']=value.product_id_name;  
					$scope['product_id_name_mixer']=value.product_id_name;  					
					$scope['product_id']=value.product_id;  					
					$scope['batchno']=value.batchno;  
					$scope['qnty']=value.qnty;  
					$scope['exp_monyr']=value.exp_monyr;  
					$scope['mfg_monyr']=value.mfg_monyr; 
					$scope['rate']=value.rate;
					$scope['mrp']=value.mrp;	
					$scope['ptr']=value.ptr;
					$scope['srate']=value.srate;
					$scope['purchase_rate']=value.purchase_rate;
					$scope['PURCHASEID']=value.PURCHASEID;
					$scope['tax_per']=value.tax_per;
					$scope['tax_ledger_id']=value.tax_ledger_id;
					$scope['disc_per']=value.disc_per;
					$scope['disc_per2']=value.disc_per2;
					$scope['Synonym']=value.Synonym;
					$scope['label_print']=value.label_print;
				
				});			
				
			});
			if(type=='FINISH'){document.getElementById(6).focus();}
			if(type=='MIXTURE'){document.getElementById(6).focus();}
					
	}

	$scope.VIEWALLVALUE=function(invoice_id)
	{

		var data_link=BaseUrl+"VIEWALLVALUE/"+invoice_id;
			console.log('VIEWALLVALUE '+data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){

					$scope['id_header']=value.id_header;  					
					$scope['invoice_no']=value.invoice_no;  
					$scope['invoice_date']=value.invoice_date;  
					$scope['challan_no']=value.challan_no;  
					$scope['challan_date']=value.challan_date;  
					$scope['tbl_party_id_name']=value.tbl_party_id_name;  
					$scope['tbl_party_id']=value.tbl_party_id;								
					$scope['comment']=value.comment;
					$scope['tot_cash_discount']=value.tot_cash_discount;
					$scope['total_amt']=value.total_amt;  
					$scope['tot_discount']=value.tot_discount;	
					$scope['tot_taxable_amt']=$scope['total_amt']-$scope['tot_discount'];								
					$scope['totvatamt']=value.totvatamt;
					$scope['grandtot']=value.grandtot;
					$scope['doctor_ledger_id']=value.doctor_ledger_id;
					$scope['doctor_ledger_id_name']=value.doctor_ledger_id_name;

					$scope['BILL_TYPE']=value.BILL_TYPE;
					$scope['hq_id']=value.hq_id;
					$scope['hq_id_name']=value.hq_id_name;
					
					
				});	
				
			});		

	}

	
	$scope.barcode_value=function(barcodefrom,event)
	{

		//	console.log(barcodefrom+event );


		if(event.keyCode === 13){

			if(barcodefrom=='barcode')
			{	
				var str=$scope.barcode;
				var strid =str.split("|");
			}
			if(barcodefrom=='barcodemix')
			{	
				var str=$scope.barcodemix;
				var strid =str.split("|");
			}
			if(barcodefrom=='billbarcode')
			{	
				var str=$scope.billbarcode;
				var strid =str.split("|");			
				$scope.get_set_value(strid[0],'','VIEWALLVALUE')		
			}
			console.log('strid'+strid);

			$scope.barcodemix=$scope.barcode=$scope.billbarcode='';

			if(barcodefrom=='barcode' || barcodefrom=='barcodemix')
			{
				var data_link=BaseUrl+"VIEWDTL/"+strid[0];
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
						angular.forEach(response.data,function(value,key){
						$scope['id_detail']=value.id;  
						$scope['product_id_name']=value.product_id_name;  
						$scope['product_id_name_mixer']=value.product_id_name;  					
						$scope['product_id']=value.product_id;  					
						$scope['batchno']=value.batchno;  
						$scope['qnty']=value.qnty;  
						$scope['exp_monyr']=value.exp_monyr;  
						$scope['mfg_monyr']=value.mfg_monyr; 
						$scope['rate']=value.srate;
						$scope['mrp']=value.mrp;	
						$scope['ptr']=value.ptr;
						$scope['srate']=value.srate;
						$scope['tax_per']=value.tax_per;
						$scope['tax_ledger_id']=value.tax_ledger_id;
						$scope['disc_per']=value.disc_per;
					
					});			
					
				});
			}
			
			if(barcodefrom=='billbarcode')
			{
				var data_link=BaseUrl+"DTLLIST/"+strid[0];
				$http.get(data_link).then(function(response) 
				{$scope.listOfDetails=response.data;});

				var data_link=BaseUrl+"VIEWALLVALUE/"+strid[0];
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
					angular.forEach(response.data,function(value,key){

						$scope['id_header']=value.id_header;  					
						$scope['invoice_no']=value.invoice_no;  
						$scope['invoice_date']=value.invoice_date;  
						$scope['challan_no']=value.challan_no;  
						$scope['challan_date']=value.challan_date;  
						$scope['tbl_party_id_name']=value.tbl_party_id_name;  
						$scope['tbl_party_id']=value.tbl_party_id;							
						$scope['doctor_ledger_id_name']=value.doctor_ledger_id_name;  
						$scope['doctor_ledger_id']=value.doctor_ledger_id;	
						$scope['comment']=value.comment;
					});	
					
				});		

			}

		}

	};

	$scope.GetAllList=function(fromdate,todate)
	{
			//var BaseUrl=domain_name+"Primary_sale_controller/ConsignmentList/";
			//data list GetAllConsignment			
			console.log($scope.startdate);
			var data_link=BaseUrl+'GetAllList/PAYMENT/-/-/'+fromdate+'/'+todate;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				$scope.ListOfTransactions=response.data;
			});
	};
	
	$scope.print_invoice = function(printtype) 
	{ 
		var data_link=BaseUrl+"print_invoice/"+$scope.id_header+'/'+printtype;
		console.log(data_link);
		//	$http.get(data_link).then(function(response){});
		window.popup(data_link); 
		
	};


	$scope.print_label = function(id_header,PRINTTYPE) 
	{ 
		var BaseUrl=domain_name+"Project_controller/print_all/";
		var data_link=BaseUrl+id_header+'/'+PRINTTYPE;
		window.popup(data_link); 
	};

}]);
//************************ACCOUNT SALE END*****************************************//



//************************ACCOUNT SALE START*****************************************//
app.controller('sale_return_old',['$scope','$rootScope','$http','$window','Sale_test',
function($scope,$rootScope,$http,$window,Sale_test)
{
	"use strict";

	//http://plnkr.co/edit/Z6tdvG9Rt8DhuiHGurRu?p=preview   --scroll section

	//$scope.appState='EMIPAYMENT';
	//var domain_name="http://localhost/abir_das_unitedlab/SATNAM/";	
	//$window.scrollTo(400,400);
	//$scope.messageWindowHeight = parseInt($window.innerHeight - 170) + 'px';
	
	$scope.spiner='OFF';
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/sale_return/";
		$scope.tran_date=$rootScope.tran_date;
		var jsonvalue
		$scope.previous_transaction_details=function(product_id)
		{
			var data_link=BaseUrl+"previous_transaction_details/"+product_id;					
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope.savemsg=value.msg; 
				});
			});
		}

		$scope.mainOperation=function(event,element_name)
		{	
			
			console.log('element_name '+element_name);
			if(event.keyCode === 13)
				{	
					element_name=Number(element_name+1);			
					document.getElementById(element_name).focus();		
				}				

				if(element_name===18)
				{
					$scope.get_set_value('','','DRCRCHECKING');
				  document.getElementById(6).focus();			
				}
				if(element_name===114)
				{
					$scope.get_set_value('','','ADDMIXTURE');
				  document.getElementById(102).focus();			
				}
	 	}

		$rootScope.partylist= [];
		$rootScope.doctorlist= [];
		$rootScope.productlist=[];			
			//var data_link=domain_name+'product_master.json';	

		
			var data_link=query_result_link+"34/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
				});
			});
		
			var data_link=query_result_link+"32/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.doctorlist.push({id:value.id,name:value.acc_name});
				});
			});

			var data_link=query_result_link+"33/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.partylist.push({id:value.id,name:value.acc_name});
				});
			});

		


			

		//	console.log($rootScope.partylist);
			
			$scope.divtop=0;
			$scope.divtop_final=0;
		$rootScope.search = function(searchelement)
		{			
				$rootScope.searchelement=searchelement;
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];
				console.log($rootScope.searchelement);		

				if($rootScope.searchelement=='product_id_name')
				{
					//Sale_test.list_items($rootScope.searchelement,$scope.product_id_name);
					$rootScope.searchItems=$rootScope.productlist;
				}
				else if($rootScope.searchelement=='product_id_name_mixer')
				{
					//Sale_test.list_items($rootScope.searchelement,$scope.product_id_name_mixer);
					$rootScope.searchItems=$rootScope.productlist;
				}
				else if($rootScope.searchelement=='doctor_ledger_id_name')
				{$rootScope.searchItems=$rootScope.doctorlist; }
				else if($rootScope.searchelement=='tbl_party_id_name')
				{$rootScope.searchItems=$rootScope.partylist; 	}	
				else
				{//Sale_test.list_items($rootScope.searchelement,$scope.product_id);}
				}

				//	console.log($rootScope.searchItems);
				// DIS AGROPYRUM - 10M/10000 (1 X 1 PCS)
				
				$rootScope.searchItems.sort();	
				var myMaxSuggestionListLength = 0;
				for(var i=0; i<$rootScope.searchItems.length; i++)
				{
					
						var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].name);
						var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
						if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) >=0){

							if($rootScope.searchelement=='product_id_name' || $rootScope.searchelement=='product_id_name_mixer')
							{	$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name,available_qnty:$rootScope.searchItems[i].available_qnty} );}
							else
							{$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name} );}

							myMaxSuggestionListLength += 1;
							if(myMaxSuggestionListLength === 1500)
							{break;}
						}
						
				}

				console.log($rootScope.suggestions);
		};
	
	$rootScope.$watch('selectedIndex',function(val){		
		if(val !== -1) {					
			$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex]['name'];	

			var elmnt = document.getElementById("prodt");
			var x = elmnt.scrollLeft;
			var y = elmnt.scrollTop;
			console.log(elmnt.scrollTop+'-'+$scope.divtop+val);
			elmnt.scrollTop = 100;
			//$scope.divtop_final=$scope.divtop+val;

		}
	});		

	$rootScope.checkKeyDown = function(event)
	{
		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
				$rootScope.selectedIndex++;
			}else{
				$rootScope.selectedIndex = 0;
			}
		
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex-1 >= 0){
				$rootScope.selectedIndex--;
			}else{
				$rootScope.selectedIndex = $rootScope.suggestions.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			//console.log($rootScope.selectedIndex);
			// event.preventDefault();			
			// $rootScope.suggestions = [];
			// $rootScope.searchItems=[];		
			// $rootScope.selectedIndex = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex>-1){
				$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			console.log($rootScope.selectedIndex);
			event.preventDefault();
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex = -1;
		}else{
			$rootScope.search();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.searchelement);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions = [];			
			$rootScope.selectedIndex = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];			
				$rootScope.selectedIndex = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide = function(index)
	{
		$scope[$rootScope.searchelement]= $rootScope.suggestions[index]['name'];

		if($rootScope.searchelement=='product_id_name')
		{
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"product_id/"+id;					
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name']=value.name; // NAME 	
					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; // NAME 	
					$scope['Synonym']=value.Synonym; // NAME 
					$scope.previous_transaction_details(value.id);														
				});
			});
		}
		if($rootScope.searchelement=='product_id_name_mixer')
		{
			//var str=$scope.product_id_name_mixer;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"product_id/"+id;	
			console.log(data_link);		
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name_mixer']=value.name; // NAME 	
					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; // NAME 	
					$scope['Synonym']=value.Synonym; // NAME 
					$scope['TRANTYPE']='MIXER'; // NAME 
																			
				});
			});
		}

		if($rootScope.searchelement=='doctor_ledger_id_name')
		{
			//var str=$scope.doctor_ledger_id_name;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"doctor_ledger_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['doctor_ledger_id']=value.id;  //ACTUAL ID
					$scope['doctor_ledger_id_name']=value.name; // NAME 					
				});
			});
		}

		if($rootScope.searchelement=='tbl_party_id_name')
		{
			//var str=$scope.tbl_party_id_name;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];
			var data_link=BaseUrl+"tbl_party_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['tbl_party_id']=value.id;  //ACTUAL ID
					$scope['tbl_party_id_name']=value.name; // NAME 					
				});
			});
		}


		// if($rootScope.searchelement=='batchno')
		// {			
		// 	var data_link=BaseUrl+"BATCH_DETAILS/"+$scope.product_id+"/"+$scope.batchno;					
		// 	//console.log(data_link);					
		// 	$http.get(data_link).then(function(response){
		// 		angular.forEach(response.data,function(value,key){
		// 			$scope['exp_monyr']=value.exp_monyr;  
		// 			$scope['mfg_monyr']=value.mfg_monyr; 
		// 			$scope['rate']=value.rate; 
		// 			$scope['srate']=value.srate; 
		// 			$scope['mrp']=value.mrp; 
		// 			$scope['ptr']=value.ptr; 
		// 			$scope['AVAILABLE_QTY']=value.AVAILABLE_QTY; 
																	
		// 		});
		// 	});
		// }
			
		 $rootScope.suggestions=[];
		 $rootScope.searchItems=[];		
		 $rootScope.selectedIndex = -1;
	};
	//===================END SEARCH SECTION =========================================

	
	$rootScope.search_batch = function(searchelement)
	{			
		$rootScope.searchelement=searchelement;
		$rootScope.suggestions_batch = [];
		$rootScope.searchItems=[];

		var data_link=BaseUrl+"batchno/"+$scope.product_id;
		console.log(data_link);			
		$http.get(data_link)
		.then(function(response) {
		$rootScope.suggestions_batch=response.data	;
		});			

	};
	
	$rootScope.$watch('selectedIndex_batch',function(val)
	{		
		if(val !== -1) {	
			$scope['batchno'] =
			$rootScope.suggestions_batch[$rootScope.selectedIndex_batch].batchno;		
		}
	});		

	$rootScope.checkKeyDown_batch = function(event){
		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch+1 < $rootScope.suggestions_batch.length){
				$rootScope.selectedIndex_batch++;
			}else{
				$rootScope.selectedIndex_batch = 0;
			}
		
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch-1 >= 0){
				$rootScope.selectedIndex_batch--;
			}else{
				$rootScope.selectedIndex_batch = $rootScope.suggestions_batch.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			//console.log($rootScope.selectedIndex);
			event.preventDefault();			
			$rootScope.suggestions_batch = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex_batch = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex_batch>-1){
				$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);			
			event.preventDefault();
			$rootScope.suggestions_batch = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex_batch = -1;
		}else{
			$rootScope.search_batch();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.batchno);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions_batch = [];			
			$rootScope.selectedIndex_batch = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp_batch = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions_batch = [];
				$rootScope.searchItems=[];			
				$rootScope.selectedIndex_batch = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide_batch = function(index)
	{

	$scope[$rootScope.searchelement]= $rootScope.suggestions_batch[index].batchno;
		//console.log($rootScope.suggestions_batch[index].exp_monyr);
	
		
	$scope['PURCHASEID']=$rootScope.suggestions_batch[index].PURCHASEID;  
	$scope['exp_monyr']=$rootScope.suggestions_batch[index].exp_monyr;  
	$scope['mfg_monyr']=$rootScope.suggestions_batch[index].mfg_monyr; 
	//$scope['rate']=$rootScope.suggestions_batch[index].rate; 
	$scope['rate']=$rootScope.suggestions_batch[index].srate; 
	$scope['srate']=$rootScope.suggestions_batch[index].srate; 
	$scope['mrp']=$rootScope.suggestions_batch[index].mrp; 
	$scope['ptr']=$rootScope.suggestions_batch[index].ptr; 
	$scope['AVAILABLE_QTY']=$rootScope.suggestions_batch[index].AVAILABLE_QTY; 
	
	$rootScope.suggestions_batch=[];
		 $rootScope.searchItems=[];		
		 $rootScope.selectedIndex = -1;
	};
	
	//===================END batch SEARCH SECTION =========================================


	$scope.savedata=function()
	{
		console.log('doctor_ledger_id'+$scope.doctor_ledger_id);
		
		$scope.spiner='ON';
		var data_link=BaseUrl+"SAVE";
		var success={};		
		var data_save = {
			'id_header': $scope.get_set_value($scope.id_header,'num','SETVALUE'),
			'id_detail': $scope.get_set_value($scope.id_detail,'num','SETVALUE'),
			'doctor_ledger_id': $scope.get_set_value($scope.doctor_ledger_id,'num','SETVALUE'),
			'product_id': $scope.get_set_value($scope.product_id,'num','SETVALUE'),
			'MIX_RAW_LINK_ID': $scope.get_set_value($scope.MIX_RAW_LINK_ID,'num','SETVALUE'),
			'RELATED_TO_MIXER': $scope.get_set_value($scope.RELATED_TO_MIXER,'str','SETVALUE'),	
			'product_name_mixture': $scope.get_set_value($scope.product_name_mixture,'str','SETVALUE'),
			'batchno_mixture': $scope.get_set_value($scope.batchno_mixture,'str','SETVALUE'),
			'qnty_mixture': $scope.get_set_value($scope.qnty_mixture,'num','SETVALUE'),
			'rate_mixture': $scope.get_set_value($scope.rate_mixture,'num','SETVALUE'),
			'mfg_monyr_mixture': $scope.get_set_value($scope.mfg_monyr_mixture,'str','SETVALUE'),
			'exp_monyr_mixture': $scope.get_set_value($scope.exp_monyr_mixture,'str','SETVALUE'),		
			'tbl_party_id': $scope.get_set_value($scope.tbl_party_id,'num','SETVALUE'),
			'invoice_date': $scope.get_set_value($scope.invoice_date,'str','SETVALUE'),
			'challan_no': $scope.get_set_value($scope.challan_no,'str','SETVALUE'),
			'challan_date': $scope.get_set_value($scope.challan_date,'str','SETVALUE'),
			'tbl_party_id_name': $scope.get_set_value($scope.tbl_party_id_name,'str','SETVALUE'),
			'comment': $scope.get_set_value($scope.comment,'str','SETVALUE'),
			'product_id_name': $scope.get_set_value($scope.product_id_name,'str','SETVALUE'),
			'batchno': $scope.get_set_value($scope.batchno,'str','SETVALUE'),
			'qnty': $scope.get_set_value($scope.qnty,'num','SETVALUE'),
			'exp_monyr': $scope.get_set_value($scope.exp_monyr,'str','SETVALUE'),
			'mfg_monyr': $scope.get_set_value($scope.mfg_monyr,'str','SETVALUE'),
			'rate': $scope.get_set_value($scope.rate,'num','SETVALUE'),
			'mrp': $scope.get_set_value($scope.mrp,'num','SETVALUE'),
			'ptr': $scope.get_set_value($scope.ptr,'num','SETVALUE'),
			'srate': $scope.get_set_value($scope.srate,'num','SETVALUE'),
			'tax_per': $scope.get_set_value($scope.tax_per,'num','SETVALUE'),	
			'disc_per': $scope.get_set_value($scope.disc_per,'num','SETVALUE'),	
			'tax_ledger_id': $scope.get_set_value($scope.tax_ledger_id,'num','SETVALUE'),
			'PURCHASEID': $scope.get_set_value($scope.PURCHASEID,'num','SETVALUE'),
			'Synonym': $scope.get_set_value($scope.Synonym,'num','SETVALUE'),
			'label_print': $scope.get_set_value($scope.label_print,'num','SETVALUE'),
			'disc_per2': $scope.get_set_value($scope.disc_per2,'num','SETVALUE'),
			'tot_cash_discount': $scope.get_set_value($scope.tot_cash_discount,'num','SETVALUE')
											
		};	
	
		var config = {headers : 
			{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
		}
		//$http.post(data_link, data,config)
		$http.post(data_link,data_save,config)
		.then (function success(response){
			console.log('ID HEADER '+response.data.id_header);
			$scope.get_set_value(response.data,'','REFRESH');
			document.getElementById('product_id_name').focus();
		},
		function error(response){
			$scope.errorMessage = 'Error adding user!';
			$scope.message = '';
	  });

	}
	


	$scope.get_set_value=
	function(datavalue,datatype,operation)
	{
		if(operation=='SETVALUE')
		{
			if(angular.isUndefined(datavalue)==true)
			{
				if(datatype=='num')
				{return 0;}
				if(datatype=='str')
				{return '';}		
			}
			else
			{return datavalue;}
		}
		if(operation=='DRCRCHECKING')
		{
			var savestatus='OK';
						
			if($scope.invoice_date == null || $scope.invoice_date === "")			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER INVOICE DATE';
			document.getElementById('invoice_date').focus();
			}
			if($scope.tbl_party_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PARTY';
			document.getElementById('tbl_party_id_name').focus();
			}

			if($scope.product_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PRODUCT';
			document.getElementById('product_id_name').focus();
			}
		
			if(savestatus=='OK')
			{
			
				$scope.product_name_mixture='NA';
				$scope.batchno_mixture='NA';
				$scope.qnty_mixture=0;
				$scope.rate_mixture=0;
				$scope.mfg_monyr_mixture='NA';
				$scope.exp_monyr_mixture='NA';
				$scope.MIX_RAW_LINK_ID=0;
				$scope.RELATED_TO_MIXER='NO';
				$scope.savedata();
				$scope.savemsg='Receord Has been saved Successfully!';
			}
		}

		if(operation=='ADDMIXTURE')
		{
			var savestatus='OK';
									
			if($scope.product_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PRODUCT';
			document.getElementById('product_id_name').focus();
			}

			if($scope.invoice_date == null || $scope.invoice_date === "")			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER INVOICE DATE';
			document.getElementById('invoice_date').focus();
			}

			if($scope.tbl_party_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PARTY';
			document.getElementById('tbl_party_id_name').focus();
			}
		
			if(savestatus=='OK')
			{
				//$scope.savedata_mixture();
				$scope.RELATED_TO_MIXER='YES';
				$scope.savedata();					
				$scope.savemsg='Receord Has been saved Successfully!';
			}
		}

		if(operation=='RESET_MIXER_PAGE')
		{
			$scope.id_detail='';
			$scope.MIX_RAW_LINK_ID='';
		}

		
		if(operation=='REFRESH')
		{		
			//HEADER SECTION

			$scope.id_header=datavalue.id_header;
		  	//console.log('After save id_header :'+$scope.id_header)
			$scope.invoice_no=datavalue.invoice_no;
			$scope.invoice_date=datavalue.invoice_date;
			$scope.challan_no=datavalue.challan_no;
			$scope.challan_date=datavalue.challan_date;
			$scope.tbl_party_id_name=datavalue.tbl_party_id_name;
			$scope.tbl_party_id=datavalue.tbl_party_id;
			$scope.comment=datavalue.comment;
			$scope.MIX_RAW_LINK_ID=datavalue.MIX_RAW_LINK_ID;

			
			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.product_id_name_mixer=$scope.disc_per2=$scope.Synonym=$scope.label_print='';
			

			//data list
			 var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
			 console.log(data_link);
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});

			 var data_link=BaseUrl+"DTLLISTMIX/"+$scope.MIX_RAW_LINK_ID;
			 console.log(data_link);
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails_mix=response.data;});
			
			$scope.VIEWALLVALUE($scope.id_header);
			$scope.spiner='OFF';
			//$scope.consignment_value();
			//$scope.GetAllConsignment($scope.startdate,$scope.enddate);

		}
		if(operation=='NEWENTRY')
		{		
			
			//HEADER SECTION
			$scope.id_header='';
			$scope.invoice_no='';
			$scope.invoice_date='';
			$scope.challan_no='';
			$scope.challan_date='';
			$scope.tbl_party_id_name='';
			$scope.tbl_party_id='';
			$scope.comment=$scope.comment='';
			
			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.disc_per2=$scope.Synonym=$scope.label_print='';
			//data list
			 var data_link=BaseUrl+"DTLLIST/"+0;
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});

			 document.getElementById('invoice_date').focus();
		}

	

		if(operation=='VIEWDTLMIX')
		{	
			
			$scope['product_id_name']='';  
			$scope['product_id_name_mixer']='';  					
			$scope['product_id']='0';   					
			$scope['batchno']='';  
			$scope['qnty']=='';  
			$scope['exp_monyr']='';  
			$scope['mfg_monyr']='';  
			$scope['rate']='';  
			$scope['mrp']='';  	
			$scope['ptr']='';  
			$scope['srate']='';  
			$scope['tax_per']='';  
			$scope['tax_ledger_id']='';  
			$scope['disc_per']='';  
			$scope['id_detail']='';

			var data_link=BaseUrl+"DTLLISTMIX/"+datavalue;
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails_mix=response.data;});
			
			var data_link=BaseUrl+"VIEWDTL/"+datavalue;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){
					//$scope['id_detail']=value.id;  
					$scope['product_name_mixture']=value.product_id_name; 
					$scope['batchno_mixture']=value.batchno;  
					$scope['qnty_mixture']=value.qnty;  
					$scope['rate_mixture']=value.rate;
					$scope['exp_monyr_mixture']=value.exp_monyr;  
					$scope['mfg_monyr_mixture']=value.mfg_monyr; 
					$scope['MIX_RAW_LINK_ID']=value.id; 
				});			
				
			});
		}

		

		if(operation=='VIEWALLVALUE')
		{	
			var data_link=BaseUrl+"DTLLIST/"+datavalue;
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails=response.data;});

			$scope.VIEWALLVALUE(datavalue);
	
		}

	}

	$scope.view_dtl=function(dtl_id,type)
	{

			var data_link=BaseUrl+"VIEWDTL/"+dtl_id;
		//	console.log('VIEWDTL NORMANL :'+data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){
					$scope['id_detail']=value.id;  
					$scope['product_id_name']=value.product_id_name;  
					$scope['product_id_name_mixer']=value.product_id_name;  					
					$scope['product_id']=value.product_id;  					
					$scope['batchno']=value.batchno;  
					$scope['qnty']=value.qnty;  
					$scope['exp_monyr']=value.exp_monyr;  
					$scope['mfg_monyr']=value.mfg_monyr; 
					$scope['rate']=value.rate;
					$scope['mrp']=value.mrp;	
					$scope['ptr']=value.ptr;
					$scope['srate']=value.srate;
					$scope['tax_per']=value.tax_per;
					$scope['tax_ledger_id']=value.tax_ledger_id;
					$scope['disc_per']=value.disc_per;
					$scope['disc_per2']=value.disc_per;
					$scope['Synonym']=value.Synonym;
					$scope['label_print']=value.label_print;
				
				});			
				
			});
			if(type=='FINISH'){document.getElementById(6).focus();}
			if(type=='MIXTURE'){document.getElementById(6).focus();}
					
	}

	$scope.VIEWALLVALUE=function(invoice_id)
	{

		var data_link=BaseUrl+"VIEWALLVALUE/"+invoice_id;
			console.log('VIEWALLVALUE '+data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){

					$scope['id_header']=value.id_header;  					
					$scope['invoice_no']=value.invoice_no;  
					$scope['invoice_date']=value.invoice_date;  
					$scope['challan_no']=value.challan_no;  
					$scope['challan_date']=value.challan_date;  
					$scope['tbl_party_id_name']=value.tbl_party_id_name;  
					$scope['tbl_party_id']=value.tbl_party_id;								
					$scope['comment']=value.comment;
					$scope['tot_cash_discount']=value.tot_cash_discount;
					$scope['total_amt']=value.total_amt;  
					$scope['tot_discount']=value.tot_discount;	
					$scope['tot_taxable_amt']=$scope['total_amt']-$scope['tot_discount'];								
					$scope['totvatamt']=value.totvatamt;
					$scope['grandtot']=value.grandtot;
					$scope['doctor_ledger_id']=value.doctor_ledger_id;
					$scope['doctor_ledger_id_name']=value.doctor_ledger_id_name;
					
				});	
				
			});		

	}

	
	$scope.barcode_value=function(barcodefrom,event)
	{

		if(event.keyCode === 13){

			if(barcodefrom=='barcode')
			{	
				var str=$scope.barcode;
				var strid =str.split("|");
			}
			if(barcodefrom=='barcodemix')
			{	
				var str=$scope.barcodemix;
				var strid =str.split("|");
			}
			if(barcodefrom=='billbarcode')
			{	
				var str=$scope.billbarcode;
				var strid =str.split("|");			
				$scope.get_set_value(strid[0],'','VIEWALLVALUE')		
			}
			console.log('strid'+strid);

			$scope.barcodemix=$scope.barcode=$scope.billbarcode='';

			if(barcodefrom=='barcode' || barcodefrom=='barcodemix')
			{
				var data_link=BaseUrl+"VIEWDTL/"+strid[0];
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
						angular.forEach(response.data,function(value,key){
						$scope['id_detail']=value.id;  
						$scope['product_id_name']=value.product_id_name;  
						$scope['product_id_name_mixer']=value.product_id_name;  					
						$scope['product_id']=value.product_id;  					
						$scope['batchno']=value.batchno;  
						$scope['qnty']=value.qnty;  
						$scope['exp_monyr']=value.exp_monyr;  
						$scope['mfg_monyr']=value.mfg_monyr; 
						$scope['rate']=value.srate;
						$scope['mrp']=value.mrp;	
						$scope['ptr']=value.ptr;
						$scope['srate']=value.srate;
						$scope['tax_per']=value.tax_per;
						$scope['tax_ledger_id']=value.tax_ledger_id;
						$scope['disc_per']=value.disc_per;
					
					});			
					
				});
			}

			if(barcodefrom=='billbarcode')
			{
				var data_link=BaseUrl+"DTLLIST/"+strid[0];
				$http.get(data_link).then(function(response) 
				{$scope.listOfDetails=response.data;});

				var data_link=BaseUrl+"VIEWALLVALUE/"+strid[0];
				console.log(data_link);
				$http.get(data_link).then(function(response) 
				{
					angular.forEach(response.data,function(value,key){

						$scope['id_header']=value.id_header;  					
						$scope['invoice_no']=value.invoice_no;  
						$scope['invoice_date']=value.invoice_date;  
						$scope['challan_no']=value.challan_no;  
						$scope['challan_date']=value.challan_date;  
						$scope['tbl_party_id_name']=value.tbl_party_id_name;  
						$scope['tbl_party_id']=value.tbl_party_id;							
						$scope['doctor_ledger_id_name']=value.doctor_ledger_id_name;  
						$scope['doctor_ledger_id']=value.doctor_ledger_id;	
						$scope['comment']=value.comment;
					});	
					
				});		

			}

		}

	};

	$scope.GetAllList=function(fromdate,todate){
			//var BaseUrl=domain_name+"Primary_sale_controller/ConsignmentList/";
			//data list GetAllConsignment			
			var data_link=BaseUrl+'GetAllList/PAYMENT/-/-/'+fromdate+'/'+todate;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				$scope.ListOfTransactions=response.data;
			});
	};
	
	$scope.print_invoice = function(printtype) 
	{ 
		var data_link=BaseUrl+"print_invoice/"+$scope.id_header+'/'+printtype;
		window.popup(data_link); 
		
	};


	$scope.print_label = function(id_header,PRINTTYPE) 
	{ 
		var BaseUrl=domain_name+"Project_controller/print_all/";
		var data_link=BaseUrl+id_header+'/'+PRINTTYPE;
		window.popup(data_link); 
	};
  

}]);
//************************ACCOUNT SALE RETURN END*****************************************//


//************************ACCOUNT PURCHASE RETURN START*****************************************//
app.controller('purchase_rtn',['$scope','$rootScope','$http','purchase_rtn',
function($scope,$rootScope,$http,purchase_rtn){
	"use strict";

		//$scope.appState='EMIPAYMENT';
		//var domain_name="http://localhost/abir_das_unitedlab/SATNAM/";	

		
		$scope.spiner='OFF';
		var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/purchase_rtn/";
		$scope.tran_date=$rootScope.tran_date;

		$scope.previous_transaction_details=function(product_id)
		{
			//$scope.savemsg=searchelement;
			//var product_id=$scope.product_id;
			//var batchno=$scope.batchno;

			var data_link=BaseUrl+"previous_transaction_details/"+product_id;					
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope.savemsg=value.msg; 
				});
			});
		}


		$scope.delete_product=function(id)
		{	
		 
			var data_link=domain_name+"Accounts_controller/AccountsTransactions/DELETE_PRODUCT";			
			var success={};		
			var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
			console.log(data_save);	
			var config = {headers : 
				{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
			}
			//$http.post(data_link, data,config)
			$http.post(data_link,data_save,config).then (function success(response){

				$scope.get_set_value(response.data.id_header,'','VIEWALLVALUE');
				document.getElementById('product_id_name').focus();

				//console.log('ID HEADER '+response.data.id_header);
				//	$scope.get_set_value(response.data,'','REFRESH');
				//	document.getElementById('product_id_name').focus();
			},
			function error(response){
				$scope.errorMessage = 'Error adding user!';
				$scope.message = '';
			});

		}

		$scope.delete_invoice=function(id)
		{	
				var data_link=BaseUrl+"DELETE_INVOICE";		
				console.log(data_link);	
				var success={};		
				var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
				console.log(data_save);	
				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}
				//$http.post(data_link, data,config)
				$http.post(data_link,data_save,config).then (function success(response){

					$scope.GetAllList($scope.startdate,$scope.enddate);
				},
				function error(response){
					$scope.errorMessage = 'Error adding user!';
					$scope.message = '';
				});
		}

		$scope.check_previous_details=function(party_id)
		{
			var data_link=BaseUrl+"check_previous_details/"+party_id+"/"+$scope.invoice_no;					
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope.invid=value.id; 				
				});
			});

			$scope.VIEWALLVALUE($scope.invid);

		}

		

		$scope.clearcokie=function()
		{
			$cookies.remove('username');
			$cookies.remove('username');
			$cookies.remove('username');
		}

		$scope.mainOperation=function(event,element_name)
		{	
				console.log('element_name '+element_name);
				if(element_name===19)
				{
					$scope.get_set_value('','','DRCRCHECKING');
				  document.getElementById(7).focus();			
				}			

				if(event.keyCode === 13)
				{	
					if(element_name===10)
					{document.getElementById('exp_monyr').focus();}		

					if(element_name===11)
					{document.getElementById('mfg_monyr').focus();}			

					element_name=Number(element_name+1);			
					document.getElementById(element_name).focus();		
				}						
		 }
		 
			$rootScope.partylist= [];
			$rootScope.doctorlist= [];
			$rootScope.productlist=[];			
			//var data_link=domain_name+'product_master.json';	

			var data_link=BaseUrl+"product_id_name/";							
			console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope.savemsg=value.name; 
					$rootScope.productlist.push({id: value.id,name:value.productname,available_qnty:value.available_qnty});
				});
			});
		
			var data_link=query_result_link+"32/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.doctorlist.push({id:value.id,name:value.acc_name});
				});
			});

			var data_link=query_result_link+"35/";
			console.log(data_link);
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){					
					$rootScope.partylist.push({id:value.id,name:value.acc_name});
				});
			});

		$rootScope.search = function(searchelement)
		{
		
			$scope.SEARCHTYPE='PRODUCT';
			$rootScope.searchelement=searchelement;
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];
			//console.log($rootScope.searchelement);
			/*
			PurchaseEntry.list_items($rootScope.searchelement,$scope.trantype);
			$rootScope.searchItems.sort();	
			var myMaxSuggestionListLength = 0;
			for(var i=0; i<$rootScope.searchItems.length; i++){
				var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i]);
				var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
				if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) !== -1){
					$rootScope.suggestions.push(searchItemsSmallLetters);
					myMaxSuggestionListLength += 1;
					if(myMaxSuggestionListLength === 400){
						break;
					}
				}
			}*/


			if($rootScope.searchelement=='product_id_name')
			{
				//Sale_test.list_items($rootScope.searchelement,$scope.product_id_name);
				$rootScope.searchItems=$rootScope.productlist;
			}		
			else if($rootScope.searchelement=='tbl_party_id_name')
			{$rootScope.searchItems=$rootScope.partylist; 	}	
			else
			{//Sale_test.list_items($rootScope.searchelement,$scope.product_id);}
			}
	
		//	console.log($rootScope.searchItems);
		// DIS AGROPYRUM - 10M/10000 (1 X 1 PCS)
					
			$rootScope.searchItems.sort();	
			var myMaxSuggestionListLength = 0;
			for(var i=0; i<$rootScope.searchItems.length; i++)
			{
				
					var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].name);
					var searchTextSmallLetters = angular.uppercase($scope[$rootScope.searchelement]);
					if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) >=0){
	
						if($rootScope.searchelement=='product_id_name' )
						{	$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name,available_qnty:$rootScope.searchItems[i].available_qnty} );}
						else
						{$rootScope.suggestions.push({id: $rootScope.searchItems[i].id,name:$rootScope.searchItems[i].name} );}
	
						myMaxSuggestionListLength += 1;
						if(myMaxSuggestionListLength === 1500)
						{break;}
					}
					
			}



		};
	
	$rootScope.$watch('selectedIndex',function(val){		
		if(val !== -1) {
		//	$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex];
			$scope[$rootScope.searchelement] =$rootScope.suggestions[$rootScope.selectedIndex]['name'];	
		}
	});		
	$rootScope.checkKeyDown = function(event){
		console.log(event.keyCode);

		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
				$rootScope.selectedIndex++;
			}else{
				$rootScope.selectedIndex = 0;
			}
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex-1 >= 0){
				$rootScope.selectedIndex--;
			}else{
				$rootScope.selectedIndex = $rootScope.suggestions.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			//console.log($rootScope.selectedIndex);
		//	event.preventDefault();			
		//	$rootScope.suggestions = [];
		//	$rootScope.searchItems=[];
		//	$rootScope.selectedIndex = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex>-1){
				$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide($rootScope.selectedIndex);
			console.log($rootScope.selectedIndex);
			event.preventDefault();
			$rootScope.suggestions = [];
			$rootScope.searchItems=[];
			$rootScope.selectedIndex = -1;
		}else{
			$rootScope.search();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.searchelement);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions = [];
			$rootScope.selectedIndex = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];
				$rootScope.selectedIndex = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide = function(index)
	{
	$scope[$rootScope.searchelement]= $rootScope.suggestions[index];

		if($rootScope.searchelement=='tbl_party_id_name')
		{
			//var str=$scope.tbl_party_id_name;
		//	var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));
			var id=	$rootScope.suggestions[index]['id'];	
			var data_link=BaseUrl+"tbl_party_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['tbl_party_id']=value.id;  //ACTUAL ID
					$scope['tbl_party_id_name']=value.name; // NAME 		
					$scope.check_previous_details(value.id);					
				});
			});
		}
		if($rootScope.searchelement=='product_id_name')
		{
			//var str=$scope.product_id_name;
			//var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
			var id=	$rootScope.suggestions[index]['id'];	
			var data_link=BaseUrl+"product_id/"+id;					
			//console.log(data_link);					
			$http.get(data_link).then(function(response){
				angular.forEach(response.data,function(value,key){
					$scope['product_id']=value.id;  //ACTUAL ID
					$scope['product_id_name']=value.name; // NAME 	
					$scope['tax_ledger_id']=value.tax_ledger_id; // NAME 	
					$scope['tax_per']=value.tax_per; // NAME 
					$scope.previous_transaction_details(value.id);															
				});
			});

			
		}
		console.log('data_link');
			
		 $rootScope.suggestions=[];
		 $rootScope.searchItems=[];
		 $rootScope.selectedIndex = -1;
	};
	//===================END SEARCH SECTION =========================================

	//=========batch wise search=====================

	$rootScope.search_batch = function(searchelement){	
		$scope.SEARCHTYPE='BATCH';		

		$rootScope.searchelement=searchelement;
		$rootScope.suggestions_batch = [];
		$rootScope.searchItems=[];

		var data_link=BaseUrl+"batchno/"+$scope.product_id+'/'+$scope.tbl_party_id;
		console.log(data_link);			
		$http.get(data_link)
		.then(function(response) {
		$rootScope.suggestions_batch=response.data	;
		});			

	};
	
	$rootScope.$watch('selectedIndex_batch',function(val){		
		if(val !== -1) {	
			$scope['batchno'] =
			$rootScope.suggestions_batch[$rootScope.selectedIndex_batch].batchno;		
		}
	});		

	$rootScope.checkKeyDown_batch = function(event){
		if(event.keyCode === 40){//down key, increment selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch+1 < $rootScope.suggestions_batch.length){
				$rootScope.selectedIndex_batch++;
			}else{
				$rootScope.selectedIndex_batch = 0;
			}
		
		}else if(event.keyCode === 38){ //up key, decrement selectedIndex
			event.preventDefault();
			if($rootScope.selectedIndex_batch-1 >= 0){
				$rootScope.selectedIndex_batch--;
			}else{
				$rootScope.selectedIndex_batch = $rootScope.suggestions_batch.length-1;
			}
		}
		else if(event.keyCode === 13){ //enter key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			//console.log($rootScope.selectedIndex);
			// event.preventDefault();			
			// $rootScope.suggestions_batch = [];
			// $rootScope.searchItems=[];		
			// $rootScope.selectedIndex_batch = -1;
		}
		else if(event.keyCode === 9){ //enter tab key
			//console.log($rootScope.selectedIndex);
			if($rootScope.selectedIndex_batch>-1){
				$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);
			}			

		}else if(event.keyCode === 27){ //ESC key, empty suggestions array
			$rootScope.AssignValueAndHide_batch($rootScope.selectedIndex_batch);			
			event.preventDefault();
			$rootScope.suggestions_batch = [];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex_batch = -1;
		}else{
			$rootScope.search_batch();	
		}
	};
	
	//ClickOutSide
	var exclude1 = document.getElementById($rootScope.batchno);
	$rootScope.hideMenu = function($event){
		$rootScope.search();
		//make a condition for every object you wat to exclude
		if($event.target !== exclude1) {
			$rootScope.searchItems=[];
			$rootScope.suggestions_batch = [];			
			$rootScope.selectedIndex_batch = -1;
		}
	};
	//======================================
	
	//Function To Call on ng-keyup
	$rootScope.checkKeyUp_batch = function(event){ 
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
			if($scope[$rootScope.searchelement] === ""){
				$rootScope.suggestions_batch = [];
				$rootScope.searchItems=[];			
				$rootScope.selectedIndex_batch = -1;
			}
		}
	};
	//======================================
	//List Item Events
	//Function To Call on ng-click
	$rootScope.AssignValueAndHide_batch = function(index)
	{

		$scope[$rootScope.searchelement]= $rootScope.suggestions_batch[index].batchno;
			//console.log($rootScope.suggestions_batch[index].exp_monyr);
		
		//	$scope.previous_transaction_details();
			
		$scope['exp_monyr']=$rootScope.suggestions_batch[index].exp_monyr;  
		$scope['mfg_monyr']=$rootScope.suggestions_batch[index].mfg_monyr; 
		$scope['rackno']=$rootScope.suggestions_batch[index].rackno; 
		$scope['rate']=$rootScope.suggestions_batch[index].rate; 
		$scope['srate']=$rootScope.suggestions_batch[index].srate; 
		$scope['mrp']=$rootScope.suggestions_batch[index].mrp; 
		$scope['ptr']=$rootScope.suggestions_batch[index].ptr; 
		$scope['AVAILABLE_QTY']=$rootScope.suggestions_batch[index].AVAILABLE_QTY; 
		
		$rootScope.suggestions_batch=[];
			$rootScope.searchItems=[];		
			$rootScope.selectedIndex = -1;
	};
	//===================END batch SEARCH SECTION =========================================



	


	$scope.savedata=function()
	{
		var data_link=BaseUrl+"SAVE";
		var success={};
		$scope.spiner='ON';
		console.log('$scope.id_detail'+$scope.id_detail)
		var data_save = 
		{
			'id_header': $scope.get_set_value($scope.id_header,'num','SETVALUE'),
			'id_detail': $scope.get_set_value($scope.id_detail,'num','SETVALUE'),
			'product_id': $scope.get_set_value($scope.product_id,'num','SETVALUE'),
			'tbl_party_id': $scope.get_set_value($scope.tbl_party_id,'num','SETVALUE'),
			'invoice_no': $scope.get_set_value($scope.invoice_no,'str','SETVALUE'),
			'invoice_date': $scope.get_set_value($scope.invoice_date,'str','SETVALUE'),
			'challan_no': $scope.get_set_value($scope.challan_no,'str','SETVALUE'),
			'challan_date': $scope.get_set_value($scope.challan_date,'str','SETVALUE'),
			'tbl_party_id_name': $scope.get_set_value($scope.tbl_party_id_name,'str','SETVALUE'),
			'comment': $scope.get_set_value($scope.comment,'str','SETVALUE'),
			'product_id_name': $scope.get_set_value($scope.product_id_name,'str','SETVALUE'),
			'batchno': $scope.get_set_value($scope.batchno,'str','SETVALUE'),
			'qnty': $scope.get_set_value($scope.qnty,'num','SETVALUE'),
			'exp_monyr': $scope.get_set_value($scope.exp_monyr,'str','SETVALUE'),
			'mfg_monyr': $scope.get_set_value($scope.mfg_monyr,'str','SETVALUE'),
			'rate': $scope.get_set_value($scope.rate,'num','SETVALUE'),
			'mrp': $scope.get_set_value($scope.mrp,'num','SETVALUE'),
			'ptr': $scope.get_set_value($scope.ptr,'num','SETVALUE'),
			'srate': $scope.get_set_value($scope.srate,'num','SETVALUE'),
			'tax_per': $scope.get_set_value($scope.tax_per,'num','SETVALUE'),	
			'disc_per': $scope.get_set_value($scope.disc_per,'num','SETVALUE'),	
			'tax_ledger_id': $scope.get_set_value($scope.tax_ledger_id,'num','SETVALUE'),
			'disc_per2': $scope.get_set_value($scope.disc_per2,'num','SETVALUE'),
			'tot_cash_discount': $scope.get_set_value($scope.tot_cash_discount,'num','SETVALUE'),
			'rackno': $scope.get_set_value($scope.rackno,'num','SETVALUE')
		};	
	
		var config = {headers : 
			{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
		}
		//$http.post(data_link, data,config)
		$http.post(data_link,data_save,config)
		.then (function success(response){
			console.log('ID HEADER '+response.data.id_header);
			$scope.get_set_value(response.data,'','REFRESH');
			document.getElementById('product_id_name').focus();
			
		},
		function error(response){
			$scope.errorMessage = 'Error adding user!';
			$scope.message = '';
		});
		

	}
	
	$scope.get_set_value=function(datavalue,datatype,operation)
	{
		if(operation=='SETVALUE')
		{
			if(angular.isUndefined(datavalue)==true)
			{
				if(datatype=='num')
				{return 0;}
				if(datatype=='str')
				{return '';}		
			}
			else
			{return datavalue;}
		}
		if(operation=='DRCRCHECKING')
		{
			var savestatus='OK';
						
			if($scope.invoice_date == null || $scope.invoice_date === "")			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER INVOICE DATE';
			document.getElementById('invoice_date').focus();
			}
			if($scope.tbl_party_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PARTY';
			document.getElementById('tbl_party_id_name').focus();
			}

			if($scope.product_id == '0')			
			{
			savestatus='NOTOK';$scope.savemsg='ENTER PRODUCT';
			document.getElementById('product_id_name').focus();
			}
		
			if(savestatus=='OK')
			{
				$scope.savedata();
				$scope.savemsg='Receord Has been saved Successfully!';
			}
		}

		if(operation=='REFRESH')
		{		
			//HEADER SECTION
			$scope.id_header=datavalue.id_header;
			$scope.invoice_no=datavalue.invoice_no;
			$scope.invoice_date=datavalue.invoice_date;
			$scope.challan_no=datavalue.challan_no;
			$scope.challan_date=datavalue.challan_date;
			$scope.tbl_party_id_name=datavalue.tbl_party_id_name;
			$scope.tbl_party_id=datavalue.tbl_party_id;
			$scope.comment=datavalue.comment;

			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.disc_per2=$scope.rackno='';

			//data list
			 var data_link=BaseUrl+"DTLLIST/"+$scope.id_header;
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});
			
			 $scope.VIEWALLVALUE($scope.id_header);
			 $scope.spiner='OFF';

			$cookies.remove('product_id_name');
			$cookies.remove('batchno');
		

			//$scope.consignment_value();
			//$scope.GetAllConsignment($scope.startdate,$scope.enddate);

		}

		if(operation=='NEWENTRY')
		{		
			
			//HEADER SECTION
			$scope.id_header='';
			$scope.invoice_no='';
			$scope.invoice_date='';
			$scope.challan_no='';
			$scope.challan_date='';
			$scope.tbl_party_id_name='';
			$scope.tbl_party_id='';
			$scope.comment='';
			
			//DETAIL SECTION
			$scope.id_detail='';	
			$scope.product_id_name='';			
			$scope.product_id=$scope.batchno=$scope.qnty='';
			$scope.exp_monyr=$scope.mfg_monyr=$scope.rate='';
			$scope.mrp=$scope.ptr=$scope.srate=$scope.tax_per='';
			$scope.tax_ledger_id=$scope.disc_per='';
			$scope.disc_per2=$scope.rackno=$scope.tot_cash_discount='';

			//data list
			 var data_link=BaseUrl+"DTLLIST/"+0;
			 $http.get(data_link).then(function(response) 
			 {$scope.listOfDetails=response.data;});

			 document.getElementById('invoice_date').focus();
		}

		if(operation=='VIEWDTL')
		{	
			var data_link=BaseUrl+"VIEWDTL/"+datavalue;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){
					$scope['id_detail']=value.id;  
					$scope['product_id_name']=value.product_id_name;  
					$scope['product_id']=value.product_id;  					
					$scope['batchno']=value.batchno;  
					$scope['qnty']=value.qnty;  
					$scope['exp_monyr']=value.exp_monyr;  
					$scope['mfg_monyr']=value.mfg_monyr; 
					$scope['rate']=value.rate;
					$scope['mrp']=value.mrp;	
					$scope['ptr']=value.ptr;
					$scope['srate']=value.srate;
					$scope['tax_per']=value.tax_per;
					$scope['tax_ledger_id']=value.tax_ledger_id;
					$scope['disc_per']=value.disc_per;
					$scope['disc_per2']=value.disc_per2;
					$scope['rackno']=value.rackno;
				});			
				
			});
		}

		if(operation=='VIEWALLVALUE')
		{	
			var data_link=BaseUrl+"DTLLIST/"+datavalue;
			$http.get(data_link).then(function(response) 
			{$scope.listOfDetails=response.data;});

			$scope.VIEWALLVALUE(datavalue);
	
		}

	}

	$scope.view_dtl=function(dtl_id,type)
	{
		var data_link=BaseUrl+"VIEWDTL/"+dtl_id;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{
			angular.forEach(response.data,function(value,key){
				$scope['id_detail']=value.id;  
				$scope['product_id_name']=value.product_id_name;  
				$scope['product_id']=value.product_id;  					
				$scope['batchno']=value.batchno;  
				$scope['qnty']=value.qnty;  
				$scope['exp_monyr']=value.exp_monyr;  
				$scope['mfg_monyr']=value.mfg_monyr; 
				$scope['rate']=value.rate;
				$scope['mrp']=value.mrp;	
				$scope['ptr']=value.ptr;
				$scope['srate']=value.srate;
				$scope['tax_per']=value.tax_per;
				$scope['tax_ledger_id']=value.tax_ledger_id;
				$scope['disc_per']=value.disc_per;
				$scope['disc_per2']=value.disc_per2;
				$scope['rackno']=value.rackno;
			});			
			
		});
	}


	$scope.VIEWALLVALUE=function(invoice_id)
	{

		var data_link=BaseUrl+"VIEWALLVALUE/"+invoice_id;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				angular.forEach(response.data,function(value,key){

					$scope['id_header']=value.id_header;  					
					$scope['invoice_no']=value.invoice_no;  
					$scope['invoice_date']=value.invoice_date;  
					$scope['challan_no']=value.challan_no;  
					$scope['challan_date']=value.challan_date;  
					$scope['tbl_party_id_name']=value.tbl_party_id_name;  
					$scope['tbl_party_id']=value.tbl_party_id;								
					$scope['comment']=value.comment;
					$scope['tot_cash_discount']=value.tot_cash_discount;

					$scope['total_amt']=value.total_amt;  
					$scope['tot_discount']=value.tot_discount;	
					$scope['tot_taxable_amt']=$scope['total_amt']-$scope['tot_discount'];								
					$scope['totvatamt']=value.totvatamt;
					$scope['grandtot']=value.grandtot;
				});	
				
			});		

	}


	$scope.GetAllList=function(fromdate,todate)
	{
		var data_link=BaseUrl+"GRANDTOTAL/"+datavalue;
		console.log(data_link);
		$http.get(data_link).then(function(response) 
		{
			angular.forEach(response.data,function(value,key){

				$scope['id_header']=value.id_header;  					
				$scope['invoice_no']=value.invoice_no;  
				$scope['invoice_date']=value.invoice_date;  
				$scope['challan_no']=value.challan_no;  
				$scope['challan_date']=value.challan_date;  
				$scope['tbl_party_id_name']=value.tbl_party_id_name;  
				$scope['tbl_party_id']=value.tbl_party_id;								
				$scope['comment']=value.comment;
				$scope['tot_cash_discount']=value.tot_cash_discount;					
			});	
		});	

	}

	$scope.GetAllList=function(fromdate,todate){
			//var BaseUrl=domain_name+"Primary_sale_controller/ConsignmentList/";
			//data list GetAllConsignment			
			var data_link=BaseUrl+'GetAllList/PAYMENT/-/-/'+fromdate+'/'+todate;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{$scope.ListOfTransactions=response.data;});
	}
	
	$scope.print_barcode = function(id_header) 
	{ 
		var BaseUrl=domain_name+"Project_controller/print_all/";
		var data_link=BaseUrl+id_header;
		window.popup(data_link); 
	};

}]);

//************************ACCOUNT PURCHASE RETURN END*****************************************//


//************************ACCOUNT -RECEIVE,PAYMENT,JOURNAL,CONTRA START*****************************************//

app.directive('selectOnClick', ['$window', function ($window) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            element.on('blur', function () {
                if (!$window.getSelection().toString()) {
                    // Required for mobile Safari
                    this.setSelectionRange(0, this.value.length)
                }
            });
        }
    };
}]);

app.filter('toRange', function(){
	return function(input) {
	  var lowBound, highBound;
	  if (input.length == 1) {       
		lowBound = 0;
		highBound = +input[0] - 1;      
	  } else if (input.length == 2) {      
		lowBound = +input[0];
		highBound = +input[1];
	  }
	  var i = lowBound;
	  var result = [];
	  while (i <= highBound) {
		result.push(i);
		i++;
	  }
	  return result;
	}
	});
	
	


	app.run(['$anchorScroll', function($anchorScroll) {
		$anchorScroll.yOffset =1000;   // always scroll by 50 extra pixels
	}]);


  //************************ACCOUNT NEW RECEIVE  START*****************************************//
app.controller('AccountsTransaction',['$scope','$window','$rootScope','$http','$location','AccountsTransaction',
function($scope,$window,$rootScope,$http,$location,AccountsTransaction)
{
	"use strict";
	
	//var domain_name="http://localhost/abir_das_unitedlab/SATNAM/";	
	var BaseUrl=domain_name+"Accounts_controller/AccountsTransactions/";
	var AcTranType;
	var CRDR_TYPE;

	//CRDR_TYPE='CR';
	$scope.initarray=function(trantype){		
		BaseUrl=BaseUrl+trantype+'/';	
		AcTranType=trantype;
		if(AcTranType=='RECEIVE'){CRDR_TYPE='DR';}
		else
		{CRDR_TYPE='CR';}
	}
	console.log(BaseUrl);
	//ARRAY EXPERIMENT
	//$scope.FormInputArray={};
	$scope.accounts_id={};
	$scope.accounts_name={};
	$scope.accounts_amount={};
	$rootScope.FormInputArray=[];	
	document.getElementById('tran_date').focus();
	
	var CurrentDate=new Date();
	var year = CurrentDate.getFullYear();
	var month = CurrentDate.getMonth()+1;
	var dt = CurrentDate.getDate();
  
	if (dt < 10) {	dt = '0' + dt;}
	if (month < 10) {month = '0' + month;}
	$scope.fromdate=year+'-' + month + '-'+dt;
	$scope.todate=year+'-' + month + '-'+dt;


	$scope.delete_transaction=function(id)
	{	
			var data_link=BaseUrl+"DELETE_TRANSACTION";		
			console.log(data_link);	
			var success={};		
			var data_save = {'id': $scope.get_set_value(id,'num','SETVALUE')	};	
			console.log(data_save);	
			var config = {headers : 
				{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
			}
			//$http.post(data_link, data,config)
			$http.post(data_link,data_save,config).then (function success(response){
				$scope.GetAllList($scope.startdate,$scope.enddate);
			},
			function error(response){
				$scope.errorMessage = 'Error adding user!';
				$scope.message = '';
			});
	}
	
	
	//CALL GLOBAL FUNCTION
	//https://stackoverflow.com/questions/15025979/can-i-make-a-function-available-in-every-controller-in-angular

			$rootScope.FormInputArray[0] =
			{	
			id_header:'',
			id_detail:'',
			trantype:'',
			truck_id:'',
			truck_no:'',
			employee_id:'',
			employee_name:'',
			tran_code:'',	
			tran_date:$scope.todate,
			CRDR_TYPE:'CR',
			ledger_account_id:'',
			ledger_account_name:'',
			ledger_amount:'',				
			transaction_details:'',
			detailtype:'NA',//BANK,BILL/NA/TT_FUEL_EXP/TT_OTHER_EXP
			details:[{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',EMPLOYEE_NAME:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''}]
		};
		
	$rootScope.FormInputArray[0].trantype=AcTranType;
	console.log('AcTranType '+AcTranType+$rootScope.FormInputArray[0].trantype);
	$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;

	//$rootScope.max_bill_count=$rootScope.FormInputArray[1]['billdetail'].length;	
	//console.log('BANK DETAILS '+$rootScope.FormInputArray[0]['bankdetail'][0].BRANCH);

	// console.log('BANK DETAILS '+$rootScope.FormInputArray[1]['bankdetail'][0].BRANCH);
	// console.log('arraindx billdetail '+
	// $rootScope.FormInputArray[0]['billdetail'][2]['testarray'][0].val);
	// console.log('$rootScope.max_bill_count '+$rootScope.max_bill_count);


	//https://stackoverflow.com/questions/18086865/angularjs-move-focus-to-next-control-on-enter

		$scope.mainOperation=function(event,element_name,index_value,child_index,searchItem,frmrpt_simple_query_builder_id)
		{	
					
			$rootScope.TotalDrAmt=0;
			$rootScope.TotalCrAmt=0;
			$rootScope.TotalBalanceAmt=0;
						
			if(searchItem=='searchItem')
			{$scope.search(element_name,index_value,child_index,$scope.FormInputArray[index_value].detailtype,frmrpt_simple_query_builder_id);}
			
			var CRDR_TYPE=angular.uppercase($scope.FormInputArray[index_value].CRDR_TYPE);

			if(CRDR_TYPE=='D' || CRDR_TYPE=='DR')
			{$scope.FormInputArray[index_value].CRDR_TYPE='DR';}
			else if(CRDR_TYPE=='C' || CRDR_TYPE=='CR')
			{$scope.FormInputArray[index_value].CRDR_TYPE='CR';}
			else
			{$scope.FormInputArray[index_value].CRDR_TYPE='';}

			//TOTAL DEBIT,CREDIT AND BALANCE AMOUNT CALCULATION
			for(var i=0; i<=$scope.maxloopvalue;i++)
			{			
				if($scope.FormInputArray[i].CRDR_TYPE=='CR')
				{
					$rootScope.TotalCrAmt=
					Number($rootScope.TotalCrAmt || 0)+Number($rootScope.FormInputArray[i].ledger_amount || 0);
				}
				if($scope.FormInputArray[i].CRDR_TYPE=='DR')
				{
					$rootScope.TotalDrAmt=
					Number($rootScope.TotalDrAmt || 0)+Number($rootScope.FormInputArray[i].ledger_amount || 0);
				}
			}

			$rootScope.TotalBalanceAmt=
			Number($rootScope.TotalDrAmt || 0)-Number($rootScope.TotalCrAmt || 0);
			//TOTAL DEBIT,CREDIT AND BALANCE AMOUNT CALCULATION



				if(event.keyCode === 13)
				{
					//OK
					if(element_name=='tran_date')
					{document.getElementById('CRDR_TYPE-0').focus();}
					//OK				
					if(element_name=='CRDR_TYPE')
					{document.getElementById('ledger_account_name-'+index_value).focus();}

					//OK
					if(element_name=='ledger_account_name')
					{document.getElementById('ledger_amount-'+index_value).focus();}
					//OK
					if(element_name=='ledger_amount')
					{						
						var indx=Number(index_value || 0)+1;
						var crtextamt=$rootScope.FormInputArray[index_value].ledger_amount;

						if($rootScope.TotalBalanceAmt!=0 && Number(crtextamt || 0)>0)
						{	
							var CRDR_TYPE='';
							var diffamt='';

							if($rootScope.TotalDrAmt>$rootScope.TotalCrAmt)
							{CRDR_TYPE='CR';}

							if($rootScope.TotalDrAmt<$rootScope.TotalCrAmt)
							{CRDR_TYPE='DR';}

						
							$rootScope.FormInputArray[$rootScope.FormInputArray.length] =
							{	
								id_header:'',
								id_detail:'',
								trantype:'',
								truck_id:'',
								truck_no:'',
								employee_id:'',
								employee_name:'',
								tran_code:'',	
								tran_date:$scope.todate,
								CRDR_TYPE:'DR',
								ledger_account_id:'',
								ledger_account_name:'',
								ledger_amount:'',				
								transaction_details:'',
								detailtype:'NA',//BANK,BILL/NA/TT_FUEL_EXP/TT_OTHER_EXP
								details:[{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',EMPLOYEE_NAME:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''}]
							};

							$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
						}

						//DELETE BLANK ELEMENT OF ARRAY
						
						for(var i=1; i<=$rootScope.maxloopvalue-1;i++)
						{							
							if(Number($scope.FormInputArray[i].ledger_amount || 0)<=0 || 
							Number($scope.FormInputArray[i].ledger_account_id || 0)==0)
							{$scope.FormInputArray.splice(i, 1);}
							$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
						}


						if($scope.FormInputArray[index_value].detailtype=='NA')
						{document.getElementById('CRDR_TYPE-'+indx).focus();}						
						else if($scope.FormInputArray[index_value].detailtype!='NA')
						{document.getElementById('BILL_INSTRUMENT_NO-'+index_value+0).focus();}
						else($rootScope.TotalBalanceAmt==0)
						{document.getElementById('transaction_details').focus();}


					}

					//ARRAY DETAILS SECTION

					//BANK DETAILS
					if($scope.FormInputArray[index_value].detailtype=='BANK')
					{

						if(element_name=='BILL_INSTRUMENT_NO')
						{document.getElementById('CHQDATE-'+index_value+child_index).focus();}
						if(element_name=='CHQDATE')
						{document.getElementById('BANKNAME-'+index_value+child_index).focus();}
						if(element_name=='BANKNAME')
						{document.getElementById('BRANCH-'+index_value+child_index).focus();}
						if(element_name=='BRANCH')
						{document.getElementById('AMOUNT-'+index_value+child_index).focus();}

						if(element_name=='AMOUNT')
						{
							var crtextamt=$rootScope.FormInputArray[index_value].ledger_amount;
							var bank_amt=0;
							var bankdetail_length=$scope.FormInputArray[index_value].details.length;
							for(var i=0; i<=bankdetail_length-1;i++)
							{			
								bank_amt=bank_amt+
								Number($scope.FormInputArray[index_value].details[i].AMOUNT |0);
							}
							if(crtextamt>bank_amt && 
								Number($scope.FormInputArray[index_value].details[child_index].AMOUNT | 0)>0)
							{													
								$scope.FormInputArray[index_value].details[bankdetail_length]=
								{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',EMPLOYEE_NAME:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''};	
							}
							

							if(crtextamt>bank_amt && 
								Number($scope.FormInputArray[index_value].details[child_index].AMOUNT | 0)>0)
							{							
								document.getElementById('BILL_INSTRUMENT_NO-'+index_value+Number(child_index+1 |0)).focus();
							}
							else
							{document.getElementById('CRDR_TYPE-'+Number(index_value+1)).focus();}

							for(var i=1; i<=bankdetail_length-1;i++)
							{							
								if(Number($scope.FormInputArray[index_value].details[i].AMOUNT || 0)<=0 )
								{$scope.FormInputArray[index_value].details.splice(i, 1);}
							}
						
						}
					}
					//BANK DETAILS END

					//BILL DETAILS
					if($scope.FormInputArray[index_value].detailtype=='SALE_BILL' || 
					$scope.FormInputArray[index_value].detailtype=='PURCHASE_BILL' || 
					$scope.FormInputArray[index_value].detailtype=='STAFF_DRIVERS')	
					{
						if(element_name=='BILL_INSTRUMENT_NO')
						{document.getElementById('AMOUNT-'+index_value+child_index).focus();}
						
						if(element_name=='AMOUNT')
						{
							var crtextamt=$rootScope.FormInputArray[index_value].ledger_amount;
							var total_bill_amt=AccountsTransaction.bill_summary(index_value);
							var current_bill_amt=Number($scope.FormInputArray[index_value].details[child_index].AMOUNT | 0);
							var billdetail_length=$rootScope.FormInputArray[index_value].details.length;
	
							if(crtextamt>total_bill_amt && current_bill_amt>0)
							{							
								$scope.FormInputArray[index_value].details[billdetail_length]=
								{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',EMPLOYEE_NAME:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''};							
							
							}						
							
							if(crtextamt>total_bill_amt && Number($scope.FormInputArray[index_value].details[child_index].AMOUNT | 0)>0)
							{document.getElementById('BILL_INSTRUMENT_NO-'+index_value+Number(child_index+1 |0)).focus();}
							else
							{document.getElementById('CRDR_TYPE-'+Number(index_value+1)).focus();}
	
							for(var i=1; i<=billdetail_length-1;i++)
							{							
								if(Number($scope.FormInputArray[index_value].details[i].AMOUNT || 0)<=0 )
								{$scope.FormInputArray[index_value].details.splice(i, 1);}
							}
						
						}


					}				
					//BILL DETAILS END 	






				}
				//ENTER EVENT END 




		}//mainOperation END


	
	//===================SEARCH STRAT===============================================	
	

	//SEARCH SECTION
    $rootScope.search = function(element_name,index_value,child_index,detailtype,frmrpt_simple_query_builder_id)
    {
        
		$rootScope.element_name=element_name;
		$rootScope.element_value='';
		$rootScope.index_value=index_value;
		$rootScope.child_index=child_index;	
		$rootScope.detailtype=detailtype;	

		var CRDR_TYPE=$scope.FormInputArray[index_value].CRDR_TYPE;
		var parent_element_id=0;
		
		
		if(element_name=='ledger_account_name'){	
			$rootScope.element_value= $scope.FormInputArray[index_value].ledger_account_name;	
		}
		
		if(element_name=='BILL_INSTRUMENT_NO')
		{
			parent_element_id=$scope.FormInputArray[index_value].ledger_account_id;
			$rootScope.element_value= 
			$scope.FormInputArray[index_value]['details'][child_index].BILL_INSTRUMENT_NO;
		}
		

		$rootScope.suggestions = [];
		$rootScope.searchItems=[];		
		console.log(detailtype);

		AccountsTransaction.list_items(element_name,parent_element_id,CRDR_TYPE,AcTranType,detailtype);	
       	
        $rootScope.searchItems.sort();	
        var myMaxSuggestionListLength = 0;
        for(var i=0; i<$rootScope.searchItems.length; i++){
            var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i]);
            var searchTextSmallLetters =angular.uppercase($rootScope.element_value);
            if( searchItemsSmallLetters.indexOf(searchTextSmallLetters) !== -1){
                $rootScope.suggestions.push(searchItemsSmallLetters);
                myMaxSuggestionListLength += 1;
                if(myMaxSuggestionListLength === 12){
                    break;
                }
            }
        }
    };

	$rootScope.$watch('selectedIndex',function(val)
	{			
		if(val !== -1) {    
			if($rootScope.element_name=='ledger_account_name'){	
				$scope.FormInputArray[$rootScope.index_value].ledger_account_name =
				$rootScope.suggestions[$rootScope.selectedIndex];	
			}	

			if($rootScope.element_name=='BILL_INSTRUMENT_NO')
			{
				$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].BILL_INSTRUMENT_NO=
				$rootScope.suggestions[$rootScope.selectedIndex];	
			}
			
		}
	});		

		$rootScope.checkKeyDown = function(event)
		{
			if(event.keyCode === 40){//down key, increment selectedIndex
				event.preventDefault();
				if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
					$rootScope.selectedIndex++;
				}else{
					$rootScope.selectedIndex = 0;
				}
				
			}else if(event.keyCode === 38){ //up key, decrement selectedIndex
				event.preventDefault();
				if($rootScope.selectedIndex-1 >= 0){
					$rootScope.selectedIndex--;
				}else{
					$rootScope.selectedIndex = $rootScope.suggestions.length-1;
				}
			}
			else if(event.keyCode === 13){ //enter key, empty suggestions array

				if($rootScope.selectedIndex>-1){
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				}	
			}
			else if(event.keyCode === 9){ //enter tab key
				if($rootScope.selectedIndex>-1){
					$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				}			

			}else if(event.keyCode === 27){ //ESC key, empty suggestions array
				$rootScope.AssignValueAndHide($rootScope.selectedIndex);
				console.log($rootScope.selectedIndex);
				event.preventDefault();
				$rootScope.suggestions = [];
				$rootScope.searchItems=[];
				$rootScope.selectedIndex = -1;
			}else{
				$rootScope.search();	
			}
		};

		//ClickOutSide
		if($rootScope.element_name=='ledger_account_name'){			
			var exclude1 = document.getElementById($rootScope.element_name+'-'+$rootScope.index_value);
		}

		if($rootScope.element_name=='BILL_INSTRUMENT_NO')
		{	
			var exclude1 = document.getElementById($rootScope.element_name+'-'+$rootScope.index_value+
			$rootScope.child_index);
		}

	

		$rootScope.hideMenu = function($event){
			$rootScope.search();
			//make a condition for every object you wat to exclude
			if($event.target !== exclude1) {
				$rootScope.searchItems=[];
				$rootScope.suggestions = [];
				$rootScope.selectedIndex = -1;
			}
		};


	$rootScope.checkKeyUp = function(event){ 		
		if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace

			if($rootScope.element_name=='ledger_account_name'){					
				if($scope.FormInputArray[$rootScope.index_value].ledger_account_name === ""){
					$rootScope.suggestions = [];
					$rootScope.searchItems=[];
					$rootScope.selectedIndex = -1;
				}
			}

			if($rootScope.element_name=='BILL_INSTRUMENT_NO'){		
						
				if($scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].BILL_INSTRUMENT_NO === "")
				{
					$rootScope.suggestions = [];
					$rootScope.searchItems=[];
					$rootScope.selectedIndex = -1;
				}
			}  
			
		
		}
	};	
	//======================================
	//List Item Events
	//Function To Call on ng-click
		$rootScope.AssignValueAndHide = function(index)
		{

			if($rootScope.element_name=='ledger_account_name')
			{		
				$scope.FormInputArray[$rootScope.index_value].ledger_account_name =
				$rootScope.suggestions[index];	
				var str=$scope.FormInputArray[$rootScope.index_value].ledger_account_name;			
				var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	
				var data_link=BaseUrl+"ledger_account_id/"+id;					
				//console.log(data_link);					
				$http.get(data_link).then(function(response){
					angular.forEach(response.data,function(value,key){
						$scope.FormInputArray[$rootScope.index_value].ledger_account_id=value.id;
						$scope.FormInputArray[$rootScope.index_value].ledger_account_name=value.name;
						$scope.FormInputArray[$rootScope.index_value].detailtype=value.COST_CENTER;

						if(value.COST_CENTER=='BANK')
						{
							$scope.FormInputArray[$rootScope.index_value]['details'][0].BANKNAME=
							$scope.FormInputArray[$rootScope.index_value].ledger_account_name;

							$scope.FormInputArray[$rootScope.index_value]['details'][0].BANKNAME=
							$scope.FormInputArray[$rootScope.index_value].ledger_account_name;

						}
						
					});
				});
			}

			if($rootScope.element_name=='BILL_INSTRUMENT_NO')
			{		
				var str=$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].BILL_INSTRUMENT_NO =
				$rootScope.suggestions[index];	
				
			//	var str=$scope.FormInputArray[$rootScope.index_value].ledger_account_name;			
				var id=str.substring(str.lastIndexOf("#")+1,str.lastIndexOf(")"));	

				if($rootScope.detailtype=='STAFF_DRIVERS')
				{var data_link=BaseUrl+"employee_id/"+id;}

				if($rootScope.detailtype=='SALE_BILL' || $rootScope.detailtype=='PURCHASE_BILL')
				{var data_link=BaseUrl+"BILLID/"+id;}
					
				$http.get(data_link).then(function(response){
					angular.forEach(response.data,function(value,key){

						$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].TABLE_NAME =value.TABLE_NAME;
						$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].TABLE_ID =value.id;
						$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].BILL_INSTRUMENT_NO =value.name;
						$scope.FormInputArray[$rootScope.index_value]['details'][$rootScope.child_index].AMOUNT =value.bill_due_amt;											
											
					});
				});
				
			}
			

			$rootScope.suggestions=[];
			$rootScope.searchItems=[];
			$rootScope.selectedIndex = -1;
		};
	
	//===================END SEARCH SECTION =========================================
	

	$scope.savedata=function(tran_type)
	{
		var data_link=BaseUrl+"SAVE";
		var success={};		
		sessionStorage.setItem("selecteddate", $rootScope.FormInputArray[0].tran_date);

		var data_save = JSON.stringify($rootScope.FormInputArray);	
		console.log(data_save);

		var config = {headers : 
			{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
		}		
		$http.post(data_link,data_save,config)
		.then (function success(response){	
			$scope.savemsg='Receord Has been saved Successfully!';
			$scope.print_consignment(response.data.id_header,tran_type);
			$scope.get_set_value(response.data,'','RE_INITIAL_INPUT_ARRAY');
		},
		function error(response){
			$scope.errorMessage = 'Error - Receord Not Saved!';
			$scope.message = '';
	  });

	}

	$scope.get_set_value=function(datavalue,datatype,operation)
	{
		if(operation=='SETVALUE')
		{
			if(angular.isUndefined(datavalue)==true)
			{
				if(datatype=='num')
				{return 0;}
				if(datatype=='str')
				{return '';}		
			}
			else
			{return datavalue;}
		}
		if(operation=='DRCRCHECKING')
		{	
			var operation_status='OK';
			if(Number($rootScope.TotalDrAmt)!=Number($rootScope.TotalCrAmt))
			{ 
				operation_status='NOTOK'
				$scope.savemsg='Debit and Credit Amount must be same';
			}		
			if(operation_status=='OK')
			{$scope.savedata(datatype);}

		}
		
		if(operation=='RE_INITIAL_INPUT_ARRAY')
		{		
			$rootScope.FormInputArray.length=0;		

			$rootScope.FormInputArray[0] =
			{	
			id_header:'',
			id_detail:'',
			trantype:'',
			truck_id:'',
			truck_no:'',
			employee_id:'',
			employee_name:'',
			tran_code:'',	
			tran_date:sessionStorage.getItem("selecteddate"),
			CRDR_TYPE:'CR',
			ledger_account_id:'',
			ledger_account_name:'',
			ledger_amount:'',				
			transaction_details:'',
			detailtype:'NA',//BANK,BILL/NA/TT_FUEL_EXP/TT_OTHER_EXP
			details:[{TABLE_NAME:'',TABLE_ID:'',BILL_INSTRUMENT_NO:'',dsl_qnty:''
			,dsl_rate:'',trip_cashamt:'',AMOUNT:'',CHQDATE:'',BANKNAME:'',BRANCH:''}]
		};

			$rootScope.TotalDrAmt=0;
			$rootScope.TotalCrAmt=0;
			$rootScope.TotalBalanceAmt=0;
			$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
			document.getElementById('tran_date').focus();
		}

		if(operation=='VIEWALLVALUE')
		{	
			
			var data_link=BaseUrl+"VIEWALLVALUE/"+datavalue;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{
				//console.log(response.data);
				angular.forEach(response.data,function(value,key){
					
						$rootScope.FormInputArray[key] =
						{	
							id_header:value.id_header,
							id_detail:value.id_detail,
							trantype:value.trantype,
							truck_id:value.truck_id,
							truck_no:value.truck_no,
							employee_id:value.employee_id,
							employee_name:value.employee_name,
							tran_code:value.tran_code,	
							tran_date:value.tran_date,
							CRDR_TYPE:value.CRDR_TYPE,
							ledger_account_id:value.ledger_account_id,
							ledger_account_name:value.ledger_account_name,
							ledger_amount:value.ledger_amount,				
							transaction_details:value.transaction_details,						
							detailtype:value.detailtype,//BANK,BILL/NA						
							details:value.details						
						};
						//console.log('Array key'+key+'cr_ledger_account_name '+value.ledger_account_name);
						
				});	
				for(var i=1; i<=$rootScope.maxloopvalue-1;i++)
				{							
					if(Number($scope.FormInputArray[i].cr_ledger_account || 0)==0)
					{$scope.FormInputArray.splice(i, 1);}					
				}
				$rootScope.maxloopvalue=$rootScope.FormInputArray.length-1;
				console.log('Array length'+$rootScope.FormInputArray.length);

			});		
	
		//	FormInputArray
		}


	}
	
	
	$scope.GetAllList=function(fromdate,todate){
			//var BaseUrl=domain_name+"Primary_sale_controller/ConsignmentList/";
			//data list GetAllConsignment			
			var data_link=BaseUrl+'GetAllList/'+AcTranType+'/-/-/'+fromdate+'/'+todate;
			console.log(data_link);
			$http.get(data_link).then(function(response) 
			{$scope.ListOfTransactions=response.data;});
	}
	
	$scope.print_consignment = function(id_header,tran_type) 
	{ 
		var data_link=domain_name+"Project_controller/print_all/"+id_header+'/'+tran_type;		
		window.popup(data_link); 
	
	};

	

}]);





app.controller('main_transaction_controller',['$scope','$rootScope','$http','$window','general_functions','$location',
		function($scope,$rootScope,$http,$window,general_functions,$location)
		{
			"use strict";


				var CurrentDate=new Date();
				var year = CurrentDate.getFullYear();
				var month = CurrentDate.getMonth()+1;
				var dt = CurrentDate.getDate();
				if (dt < 10) {	dt = '0' + dt;}
				if (month < 10) {month = '0' + month;}
				$scope.tran_date=year+'-' + month + '-'+dt;

				//$scope.barcodeimg=domain_name+'uploads/BILL-2.png';


			//	$cash_total


				//$scope.input_id_index=0;
				$rootScope.fld_arr=[];	

				$rootScope.sec_indx0=0;	
				$rootScope.sec_indx1=1;	
				$rootScope.sec_indx2=2;	
				$rootScope.sec_indx3=3;	
				$rootScope.sec_indx4=4;	

			//	$rootScope.final_array=[];
				$rootScope.FormInputArray_template=[];	
				$rootScope.FormInputArray=[];	
				//$rootScope.ResourceArray=[];
				$rootScope.main_grid_array=[];
				$rootScope.dtlist_array=[];
				$rootScope.dtlist_total_array=[];
				
				$rootScope.savedone_status='SAVE_NOT_DONE';
				$rootScope.grandtotal=0;
				$scope.formname='';
				$rootScope.array_name='';

				var BaseUrl=domain_name+"Project_controller/experimental_form/";
				$rootScope.search_div_display='none';
				//block none

				//$scope.codestructure1_id=0;
				$scope.server_msg='Enter Form';
				//$rootScope.current_form_report='grn_entry';

				 $rootScope.current_form_report=localStorage.getItem("TranPageName");
				 console.log('page name :'+$rootScope.current_form_report);

				 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
				 console.log(data_save);
			
					//GENERAL FUNCTIONS START
					$scope.main_grid=function(id)
					{	
						$rootScope.grandtotal=0;
						general_functions.main_grid($rootScope.current_form_report,'main_grid',BaseUrl,id,$scope.startdate,$scope.enddate);
					}

					$scope.main_grid(1);

					$scope.setTotals = function(item)
					{
						
						angular.forEach(item, function (values, key) 
							{ 			
									
								if(key=='Grand Total')
								{
									console.log(key+' '+values)	;		
									$rootScope.grandtotal =Number($rootScope.grandtotal) +Number(values);
								
								}								
							}); 
				  }
					

					$scope.dtlist=function(id)
					{general_functions.dtlist(BaseUrl,id);}

					$scope.dtlist_total=function(id)
					{general_functions.dtlist_total(BaseUrl,id);}
					
					$scope.dtlist_view=function(indx)
					{

						//general_functions.dtlist_view(BaseUrl,id);		
						
						//id,product_id,batchno,qnty,rate,subtotal,disc_per,disc_per2,disc_amt,
						//taxable_amt,mrp,srate,exp_monyr,mfg_monyr,tax_ledger_id,taxamt,net_amt

						
						if($rootScope.current_form_report=='purchase_entry')
						{
						var field_list=['id','product_id','batchno','qnty','rate','batchno','disc_per','disc_per2','mrp',
						,'exp_monyr','mfg_monyr','tax_ledger_id'];				

						}

						//id,product_id,batchno,qnty,rate,subtotal,disc_per,disc_per2,disc_amt,taxable_amt,mrp,
						//exp_monyr,mfg_monyr,tax_ledger_id,taxamt,net_amt,Synonym,label_print

						//product_id,batchno,qnty,rate,disc_per,disc_per2,Synonym,label_print,mrp,exp_monyr,mfg_monyr,tax_ledger_id

						if($rootScope.current_form_report=='invoice_entry')
						{
							var field_list=['id','invoice_summary_id','PURCHASEID','product_id','batchno','qnty','rate',
							'batchno','disc_per','disc_per2','mrp', 'potency_id','pack_id','no_of_dose','main_group_id','product_group_id'
							,'exp_monyr','mfg_monyr','tax_ledger_id','Synonym','label_print'];		
						}

						angular.forEach(field_list, function (values, key) 
						{ 

							console.log('values: '+values +' KEY :'+key);
							console.log(	$rootScope.dtlist_array[0]['tax_ledger_id']['Inputvalue'])

							$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['Inputvalue']=
							$rootScope.dtlist_array[indx][values]['Inputvalue'];

							$rootScope.FormInputArray[0]['header'][1]['fields'][0][values]['Inputvalue_id']=
							$rootScope.dtlist_array[indx][values]['Inputvalue_id'];
							
						}); 

						if($rootScope.current_form_report=='purchase_entry'|| $rootScope.current_form_report=='invoice_entry')
						{document.getElementById(8).focus(); }

											
									
					}

					$scope.other_search=function(id,subtype,header_index,field_index,searchelement)
					{
						general_functions.other_search($rootScope.current_form_report,'other_search',BaseUrl,id);										
					}

					$scope.view_detail=function(id,subtype,header_index,field_index,searchelement)
					{
						general_functions.view_detail($rootScope.current_form_report,BaseUrl,id);										
					}
					
							
					$scope.view_list=function(id)
					{
						//console.log('view list id :'+id);
						//$scope.server_msg="Data Loading....Please Wait";
						general_functions.list_items($rootScope.current_form_report,'view_list',BaseUrl,id);	
						$scope.dtlist(id);
						$scope.dtlist_total(id);		
						//$scope.server_msg="All Data Uploaded.";		

					}
					$scope.view_list(0);

					$scope.get_field_name=function(header_index,field_param,field_name)
					{return $rootScope.FormInputArray[0]['header'][header_index]['fields'][0][field_name][field_param];}

					$scope.create_date_field=function(inputid)
					{	
						//console.log('inputid :'+inputid);

						$( function() {			
							$("#"+inputid ).datepicker({changeMonth: true,dateFormat: 'yy-mm-dd',changeYear: true});
							$("#"+inputid).change(function() {var  trandate = $("#"+inputid).val();
							$("#"+inputid).val(trandate);});
						} );		
					}
					$scope.create_date_field('startdate');
					$scope.create_date_field('enddate');
					

					$scope.gotoAnchor = function(x) 
					{
					 var newHash = 'innerAnchor' + x;

					 if ($location.hash() !== newHash) {							
						 $location.hash('innerAnchor' + x);
					 } else {							
						 $anchorScroll();
					 }

				 };

				 $scope.test=function()
				 {
					 //console.log('searchelement '+$rootScope.searchelement+' header index '+$rootScope.indx1+' Field Index '+$rootScope.index2);
					 var data_save = domain_name+'Project_controller/experimental_form_grid/'+$rootScope.searchelement+'/'+$rootScope.indx1+'/'+$rootScope.index2;
					 console.log(data_save);

					 for(var indx=0;indx<2;indx++)
					 {
							angular.forEach($rootScope.FormInputArray[0]['header'][indx]['fields'][0], function (values, key) 
							{ 
								if($rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['InputType']=='datefield')
								{
									var inputid=$rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['input_id_index'];
									$scope.create_date_field(inputid);

									$rootScope.FormInputArray[0]['header'][indx]['fields'][0][key]['Inputvalue']=$scope.tran_date;
									
								}							
							}); 
					 }

					 if($rootScope.current_form_report=='invoice_entry')
					 {document.getElementById(2).focus();}

					 if($rootScope.current_form_report=='purchase_entry')
					 {document.getElementById(0).focus();}
					 
				 }

				 $scope.new_entry=function()
					{	
					
						angular.forEach($rootScope.FormInputArray[0]['header'][0]['fields'][0], function (values, key) 
						{ 
							$rootScope.FormInputArray[0]['header'][0]['fields'][0][key]['Inputvalue']='';
							$rootScope.FormInputArray[0]['header'][0]['fields'][0][key]['Inputvalue_id']='';	
						}); 

						angular.forEach($rootScope.FormInputArray[0]['header'][1]['fields'][0], function (values, key) 
						{ 								
								$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue']='';
								$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue_id']='';	
						}); 

						$rootScope.FormInputArray[0]['header'][0]['fields'][0]['invoice_date']['Inputvalue']=$scope.tran_date;
												
						$scope.dtlist(0);
						$scope.test();

					}

			//GENERAL FUNCTIONS END 


			// $scope.batch_search=function(id)
			// {
			// 	general_functions.batch_search($rootScope.current_form_report,'batch_search',BaseUrl,id);										
			// }
 

				$scope.mainOperation=function(event,header_index,field_index,Index2,index3,input_id_index)
				{	
					
					if(event.keyCode === 13)
						{						
														
								//CHANGES HERE FORM BASIS
								if($rootScope.current_form_report=='invoice_entry')
								{
									
									// if(input_id_index==20)
									// {$scope.save_check();}

								
									if($rootScope.searchelement=='qnty')	
									{$scope.save_check();}	

									if($rootScope.searchelement=='product_id')	
									{
										
												$rootScope.final_array=[];
												$rootScope.final_array = JSON.parse(JSON.stringify($rootScope.FormInputArray));
											
												for(var i=0;i<2;i++)
												{
													angular.forEach($rootScope.final_array[0]['header'][i]['fields'][0], function (values, key) 
													{ 
														$rootScope.final_array[0]['header'][i]['fields'][0][key]['datafields']='';													
													}); 
												}		
											
											$rootScope.FormInputArray[0]['header'][1]['fields'][0]['batchno']['datafields']='';
											general_functions.batch_search($rootScope.current_form_report,'batch_search',BaseUrl,1);	
																				
									}

									 if($rootScope.searchelement=='tbl_party_id'  || $rootScope.searchelement=='batchno'|| 
									 $rootScope.searchelement=='rate' ||  $rootScope.searchelement=='disc_per'	 ||
									 $rootScope.searchelement=='disc_per2' ||  $rootScope.searchelement=='main_group_id' 
									 ||  $rootScope.searchelement=='potency_id' ||  $rootScope.searchelement=='pack_id' 
									 ||  $rootScope.searchelement=='no_of_dose')	
									 {$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);}

								}	

								if($rootScope.current_form_report=='purchase_entry')
								{
								
									if(input_id_index==17)
									{$scope.save_check();}

									//|| $rootScope.searchelement=='batchno'
									 if($rootScope.searchelement=='tbl_party_id' || $rootScope.searchelement=='product_id')	
									 {$scope.other_search(1,'other_search',$rootScope.indx1,$rootScope.index2,$rootScope.searchelement);}

								}	
							
								input_id_index=Number(input_id_index+1);			
								document.getElementById(input_id_index).focus();

						}		
						if(event.keyCode === 39)
						{	
							input_id_index=Number(input_id_index+1);			
							document.getElementById(input_id_index).focus();		
						}		
						if(event.keyCode === 37)
						{	
							input_id_index=Number(input_id_index-1);			
							document.getElementById(input_id_index).focus();		
						}					
						

				}

				
				$rootScope.search = function(searchelement,indx1,index2,index3,index4,array_name)
				{		
						// console.log('searchelement '+searchelement+' indx1 '+indx1+' index2 '+index2+
						// ' index3 '+index3+' index4 '+index4+' array name '+array_name);


						$rootScope.search_div_display='none';
						$rootScope.searchelement=searchelement;
						$rootScope.array_name=array_name;							
						$rootScope.indx1=indx1;
						$rootScope.index2=index2;
						$rootScope.index3=index3;
						$rootScope.index3=index4;

							console.log('Input Type :'+$rootScope.FormInputArray[0]['header'][indx1]['fields'][0][searchelement]['InputType']);
						

						$rootScope.suggestions = [];
						$rootScope.searchItems=[];
						$rootScope.searchTextSmallLetters='';
						$rootScope.selectedIndex =0;
						
						angular.forEach($rootScope.FormInputArray[0]['header'][indx1]['fields'][$rootScope.index2][$rootScope.searchelement], function (values, key) 
						{ 
							
							if(key=='Inputvalue')
							{	$rootScope.searchTextSmallLetters = angular.uppercase(values);}
						
							if(values!='' && key=='datafields')
							{
									var array_length=values.length;
									if(array_length>0)
									{
										$rootScope.searchItems=values;
										$rootScope.search_div_display='block';
										//block none											
									}
							}
						}); 

					
						//console.log('$rootScope.searchItems'+$rootScope.searchItems);
						//$rootScope.suggestions=$rootScope.searchItems;

						$rootScope.searchItems.sort();	
						var myMaxSuggestionListLength = 0;
						for(var i=0; i<$rootScope.searchItems.length; i++)
						{					
								var searchItemsSmallLetters = angular.uppercase($rootScope.searchItems[i].FieldVal);							
								if( searchItemsSmallLetters.indexOf($rootScope.searchTextSmallLetters) >=0)
								{									
									
									$rootScope.suggestions.push($rootScope.searchItems[i]);
									myMaxSuggestionListLength += 1;
									if(myMaxSuggestionListLength === 50)
									{break;}
									
								}						
						}

				};

					
					$rootScope.$watch('selectedIndex',function(val)
					{		
						if(val !== -1) 
						{
							
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[$rootScope.selectedIndex]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[$rootScope.selectedIndex]['FieldID'];

							//inner div $anchorScroll
							//http://plnkr.co/edit/yFj9fL3sOhDqjhMawI72?p=preview&preview
							
							$scope.gotoAnchor(val);

						
						}
					});		
					
					$rootScope.checkKeyDown = function(event,header_index,field_index,Index2,index3,input_id_index)
					{
						
						console.log(event.keyCode);
							if(event.keyCode === 40){//down key, increment selectedIndex
							event.preventDefault();
							if($rootScope.selectedIndex+1 < $rootScope.suggestions.length){
								$rootScope.selectedIndex++;
							}else{
								$rootScope.selectedIndex = 0;
							}

							//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
							//console.log('event.keyCode:'+event.keyCode+' header_index:'+header_index+' field_index:'+field_index);
							//console.log('Index2:'+Index2+' index3:'+index3+' input_id_index:'+input_id_index);
						
						}else if(event.keyCode === 38){ //up key, decrement selectedIndex
							event.preventDefault();
							if($rootScope.selectedIndex-1 >= 0){
								$rootScope.selectedIndex--;
							}else{
								$rootScope.selectedIndex = $rootScope.suggestions.length-1;
							}
						}
						else if(event.keyCode === 13){ //enter key, empty suggestions array
							$rootScope.AssignValueAndHide($rootScope.selectedIndex);
							//$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
						}
						else if(event.keyCode === 9){ //enter tab key
							//console.log($rootScope.selectedIndex);
							if($rootScope.selectedIndex>-1){
								$rootScope.AssignValueAndHide($rootScope.selectedIndex);
							}			
				
						}
						else if(event.keyCode === 27){ //ESC key, empty suggestions array
							$rootScope.AssignValueAndHide($rootScope.selectedIndex);
						}
						else if(event.keyCode === 39){ //right key
							//$rootScope.AssignValueAndHide($rootScope.selectedIndex);
							$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
						}
						else if(event.keyCode === 37){ //left key
						//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
						$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
						}
						else if(event.keyCode === 113){ //F2 KEY FOR ADD
							//	$rootScope.AssignValueAndHide($rootScope.selectedIndex);
							$scope.mainOperation(event,header_index,field_index,Index2,index3,input_id_index);
							}
						
						else{
							$rootScope.search();	
						}
					};
					
					//ClickOutSide
					var exclude1 = document.getElementById($rootScope.searchelement);
					$rootScope.hideMenu = function($event){
						$rootScope.search();
						//make a condition for every object you wat to exclude
						if($event.target !== exclude1) {
						
						}
					};
					//======================================
					
					//Function To Call on ng-keyup
					$rootScope.checkKeyUp = function(event)
					{ 
						if(event.keyCode !== 8 || event.keyCode !== 46){//delete or backspace
							if($scope[$rootScope.searchelement] === ""){
								$rootScope.suggestions = [];
								$rootScope.searchItems=[];			
								$rootScope.selectedIndex = -1;
							}
						}
					};
					//======================================
					//List Item Events
					//Function To Call on ng-click
						$rootScope.AssignValueAndHide = function(index)
						{

							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue']=
							$rootScope.suggestions[index]['FieldVal'];
							$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2][$rootScope.searchelement]['Inputvalue_id']=
							$rootScope.suggestions[index]['FieldID'];

							//console.log('mrp mrp' + $rootScope.searchelement + $rootScope.current_form_report);


							if( $rootScope.current_form_report=='invoice_entry')
							{	
								if($rootScope.searchelement=='batchno' )
								{			
															
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['rate']['Inputvalue']
									= $rootScope.suggestions[index]['Rate'];
								
									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['mrp']['Inputvalue']
									= $rootScope.suggestions[index]['MRP'];

									$rootScope.FormInputArray[0]['header'][$rootScope.indx1]['fields'][$rootScope.index2]['PURCHASEID']['Inputvalue']
									= $rootScope.suggestions[index]['PID'];
																
								}
							}


							
						$rootScope.suggestions=[];
						$rootScope.searchItems=[];		
						$rootScope.selectedIndex = -1;
					};
	


				$scope.final_submit=function()
				{					
					
						$scope.save_check();
						
						var data_link=BaseUrl;
						var success={};		
						var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
						
						var data_save = {'form_name':$rootScope.current_form_report,'subtype':'FINAL_SUBMIT','id':id};
						console.log(data_save);	
						var config = {headers :{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'}}				
						$http.post(data_link,data_save,config).then (function success(response){

							//$scope.new_entry();
							console.log('header id :'+response.data.id_header);

							$scope.server_msg=response.data.server_msg;
							$scope.dtlist(response.data.id_header);
							$scope.dtlist_total(response.data.id_header);

							 if( $rootScope.current_form_report=='invoice_entry')
							 {$scope.print_documents('POS_INVOICE',response.data.id_header);}

							
						},
						function error(response){
							$scope.errorMessage = 'Error adding user!';
							$scope.message = '';
						});						
						
				}

				$scope.save_check=function()
				{	

					$rootScope.final_array=[];
					$rootScope.final_array = JSON.parse(JSON.stringify($rootScope.FormInputArray));
					$rootScope.save_status='OK';

					for(var i=0;i<2;i++)
					{
						angular.forEach($rootScope.final_array[0]['header'][i]['fields'][0], function (values, key) 
						{ 
							$rootScope.final_array[0]['header'][i]['fields'][0][key]['datafields']='';	

							if($rootScope.final_array[0]['header'][i]['fields'][0][key]['validation_type']=='NOT_OK' 
							&& $rootScope.save_status=='OK')
							{ 
								 $rootScope.save_status='NOT_OK'; 
								 $scope.server_msg='Record Can Not Be Save! Please Rectify the '+
								 $rootScope.final_array[0]['header'][i]['fields'][0][key]['LabelName'] +' Related data';
							}
							
						}); 
					}		

					//console.log('$rootScope.save_status :'+$rootScope.save_status);

					if($rootScope.save_status=='OK' && $rootScope.savedone_status=='SAVE_NOT_DONE')
					{$rootScope.savedone_status='SAVE_DONE'; $scope.savedata();}
					
				}	

				//SAVE SECTION...
				$scope.savedata=function()
				{
					$scope.showarray='YES';
					$scope.spiner='ON';
					//	$scope.savemsg='Please Wait data saving....';
					//var data_link=BaseUrl+"SAVE";
					var data_link=BaseUrl;
					var success={};	
					var data = JSON.stringify($rootScope.final_array);	
					var data_save = {'form_name':$scope.current_form_report,'subtype':'SAVE_DATA','raw_data':data};

					//console.log('data_save final : '+data);
					
					var config = {headers : 
						{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
					}
					//$http.post(data_link, data,config)
					$http.post(data_link,data_save,config)
					.then (function success(response){

							$scope.server_msg=response.data.server_msg;
							$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue']=response.data.id_header;
							$rootScope.FormInputArray[0]['header'][0]['fields'][0]['invoice_no']['Inputvalue']=response.data.invoice_no;

							$scope.dtlist(response.data.id_header);
							$scope.dtlist_total(response.data.id_header);
							

							angular.forEach($rootScope.FormInputArray[0]['header'][1]['fields'][0], function (values, key) 
							{ 
								$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue']='';
								$rootScope.FormInputArray[0]['header'][1]['fields'][0][key]['Inputvalue_id']='';	
							}); 


						//CHANGES HERE FORM BASIS

						if($rootScope.current_form_report=='invoice_entry' )
						{document.getElementById(4).focus();}

						if($rootScope.current_form_report=='purchase_entry' )
						{document.getElementById(8).focus();}

						$rootScope.savedone_status='SAVE_NOT_DONE';
				
					},
					function error(response){
						$scope.errorMessage = 'Error adding user!';
						$scope.message = '';
					});

					


				}

				//SAVE SECTION...



				$scope.print_documents = function(printtype) 
				{ 
					//var BaseUrl=domain_name+"Project_controller/experimental_form/";
					var id=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
					var data_link=domain_name+"Project_controller/print_documents/"+printtype+'/'+id;
					console.log(data_link);
					//	$http.get(data_link).then(function(response){});
					window.popup(data_link); 
					
				};
			

				$scope.print_label = function(PRINTTYPE) 
				{ 
					var PRINTTYPE='LABEL';
					var id_header=$rootScope.FormInputArray[0]['header'][0]['fields'][0]['id']['Inputvalue'];
					var BaseUrl=domain_name+"Project_controller/print_all/";
					var data_link=BaseUrl+id_header+'/'+PRINTTYPE;
					window.popup(data_link); 
				};
			

		
}]);



app.controller('product_rate_master',['$scope','$rootScope','general_functions','$http',
function($scope,$rootScope,general_functions,$http){
	"use strict";
	
			$scope.products={};
			$scope.ledgers={};
			$rootScope.FormInputArray=[];	
			$rootScope.rate_list_array=[];

			var BaseUrl=domain_name+"Project_controller/product_rate_master/";
			var AcTranType;
			
			// var data_link=query_result_link+"39/";$http.get(data_link).then(function(response) {$scope.master_lovs=response.data;});
			// console.log(data_link);

			// var data_link=query_result_link+"33/";$http.get(data_link).then(function(response) {$scope.active_inactive_list=response.data;});

			general_functions.get_master(BaseUrl,0,'PRODUCT_GROUP');


			$scope.rate_list=function(id)
			{	
				general_functions.rate_list(BaseUrl,id);
			}

			$scope.rate_list(1);
		
							
			$scope.savedata=function()
			{
				var data_link=BaseUrl;
				console.log(data_link);
				var success={};		
							
				var data = JSON.stringify($rootScope.rate_list_array['body']);	
				var data_save = {'subtype':'SAVE_DATA','raw_data':data};
			 	console.log(data_save);

				var config = {headers : 
					{'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'	}
				}		
				$http.post(data_link,data_save,config)
				.then (function success(response){	
					// $scope.savemsg=response.data.server_msg;
					// $scope.id_header=response.data.id_header;
					// console.log($scope.savemsg);
					// $scope.new_entry();

				},
				function error(response){
					$scope.errorMessage = 'Error - Receord Not Saved!';
					$scope.message = '';
				});

			}
	
}]);
