/**动态添加activeFormJS验证
*formId activeForm表单名称
*modelName 模型
*数组下标
*
*/
function fixFormValidaton(formId,modelName,rowsize,attribute){
	var attrInput = '#'+modelName+'-' + rowsize +'-'+attribute;
	var attrId = modelName+'-' + rowsize + '-'+attribute;
	var attrContainer = '.field-'+modelName+'-' + rowsize +'-'+attribute;
	var attrName ='['+rowsize+']'+attribute;
	formId = "#"+ formId;						
	$(formId).yiiActiveForm('add', {					
		id: attrId,
		name: attrName,
		container: attrContainer,
		input: attrInput,
		error: '.help-block',			
		enableAjaxValidation:true,
		validateOnChange:false,
	    validateOnBlur:false,
		validateOnSubmit:true
	});				
}  
/**动态添加activeFormJS验证
*formId activeForm表单名称
*modelName 模型
*数组下标
*
*/
function fixMultipleFormValidaton(formId,modelName,rowsize,column,attribute){
	var attrInput = '#'+modelName+'-' + rowsize+'-'+column+'-'+attribute;
	var attrId = modelName+'-' + rowsize +'-'+column+ '-'+attribute;
	var attrContainer = '.field-'+modelName+'-' + rowsize +'-'+column+'-'+attribute;
	var attrName ='['+rowsize+']['+column+']'+attribute;	
	formId = "#"+ formId;						
	$(formId).yiiActiveForm('add', {					
		id: attrId,
		name: attrName,
		container: attrContainer,
		input: attrInput,
		error: '.help-block',			
		enableAjaxValidation:true,
		validateOnChange:false,
	    validateOnBlur:false,
		validateOnSubmit:true
	});			
}

function fixMultipleFormValidatons(formId,modelName,rowsize,column,attributes){	
	$.each(attributes,function(){
		var attrInput = '#'+modelName+'-' + rowsize+'-'+column+'-'+this;
		var attrId = modelName+'-' + rowsize +'-'+column+ '-'+this;
		var attrContainer = '.field-'+modelName+'-' + rowsize +'-'+column+'-'+this;
		var attrName ='['+rowsize+']['+column+']'+this;	
		var newFormId = "#"+ formId;				
		$(newFormId).yiiActiveForm('add', {					
			id: attrId,
			name: attrName,
			container: attrContainer,
			input: attrInput,
			error: '.help-block',			
			enableAjaxValidation:true,
			validateOnChange:false,
		    validateOnBlur:false,
			validateOnSubmit:true
		});	
	});
}


/**动态添加activeFormJS验证
*formId activeForm表单名称
*modelName 模型
*数组下标
*
*/
function fixFormValidatons(formId,modelName,rowsize,attributes){
	
	$.each(attributes,function(){
		var attrInput = '#'+modelName+'-' + rowsize +'-'+this;
		var attrId = modelName+'-' + rowsize + '-'+this;
		var attrContainer = '.field-'+modelName+'-' + rowsize +'-'+this;
		var attrName ='['+rowsize+']'+this;
		var newFormId = "#"+ formId;
		$(newFormId).yiiActiveForm('add', {					
			id: attrId,
			name: attrName,
			container: attrContainer,
			input: attrInput,
			error: '.help-block',			
			enableAjaxValidation:true,
			validateOnChange:false,
		    validateOnBlur:false,
			validateOnSubmit:true
		});	
	});
				
}  

function renameValidations(modelName,rowsize,attributes){
	$.each(attributes,function(){
		var newsize = rowsize-1;
		var divClass = ".field"+"-"+modelName+"-"+rowsize+"-"+this;
		var div = $(divClass);
		if(div.length > 0){
			var newDivClass = "field"+"-"+modelName+"-"+newsize+"-"+this;		
			div.attr('class',newDivClass);
		}		
		var inputId = "#"+modelName+"-"+rowsize+"-"+this;
		var input = $(inputId);
		if(input.length > 0){
			var oldInputName = input.attr("name");
			var reg = "["+ rowsize + "]";		
			var newInputId = modelName+"-"+newsize+"-"+this;
			input.attr("id",newInputId);
			var newInputName = oldInputName.replace(reg,"["+ newsize + "]");
			input.attr("name",newInputName);
		}				
	});	
}
//type=1 row,type=2 column
function renameMultipleValidations(modelName,rowsize,column,attributes,type){
	
	$.each(attributes,function(){
		var newsize;
		var newColumn;
		var attrInput = '#'+modelName+'-' + rowsize+'-'+column+'-'+this;
		var attrId = modelName+'-' + rowsize +'-'+column+ '-'+this;
		var attrContainer = '.field-'+modelName+'-' + rowsize +'-'+column+'-'+this;
		var attrName ='['+rowsize+']['+column+']'+this;	
		type==1? newsize = rowsize-1: newColumn = column-1;
			
		var newsize = rowsize-1;
		var divClass = ".field"+"-"+modelName+"-"+rowsize+"-"+column+'-'+this;		
		
		var div = $(divClass);
		if(div.length > 0){
			var newDivClass;
			type==1?  newDivClass = "field"+"-"+modelName+"-"+newsize+"-"+column+'-'+this: newDivClass = "field"+"-"+modelName+"-"+rowsize+"-"+newColumn+'-'+this;
			div.attr('class',newDivClass);
		}		
		var inputId = "#"+modelName+"-"+rowsize+"-"+column+"-"+this;
		var input = $(inputId);
		if(input.length > 0){			
			var newInputId;
			type==1? newInputId = modelName+"-"+newsize+"-"+column+"-"+this:newInputId = modelName+"-"+rowsize+"-"+newColumn+"-"+this;
			input.attr("id",newInputId);
			
		}	
		var oldInputName = input.attr("name");
		var regstr = "\\[\\d\\]\\[\\d\\]*";
		var reg = new RegExp(regstr);
		var newInputName;
		var input= $("input[name='"+oldInputName+"']")
		if(input.length > 0){	
			type==1?  newInputName = oldInputName.replace(reg,"["+ newsize + "]["+column+"]"):newInputName = oldInputName.replace(reg,"["+ rowsize + "]["+newColumn+"]");
			input.attr("name",newInputName);	
		}
	});
}
/**动态添加activeFormJS验证
*formId activeForm表单名称
*modelName 模型
*数组下标
*
*/
function fixFormSingleValidatons(formId,modelName,attributes){
	
	$.each(attributes,function(){
		var attrInput = '#'+modelName+'-'+this;
		var attrId = modelName+'-' +this;
		var attrContainer = '.field-'+modelName+'-'+this;
		var attrName =this;
		var newFormId = "#"+ formId;
		$(newFormId).yiiActiveForm('add', {					
			id: attrId,
			name: attrName,
			container: attrContainer,
			input: attrInput,
			error: '.help-block',			
			enableAjaxValidation:true,
			validateOnChange:false,
		    validateOnBlur:false,
			validateOnSubmit:true
		});	
	});
				
}  