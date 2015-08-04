function initUI(){
	$("#answer").hide();
	$("#rspin").hide();
	$confirmed=getParameterByName("confirmed");
	if($confirmed=="true"){
		$("#answer > h2").html("Your account has been confirmed !");
		$(".registerForm").hide();
		$("#registerList").hide();
		$(".paypal").hide();
		$("#answer").show();
		$("#rspin").hide();
	}else if($confirmed=="false"){
		$("#answer > h2").html("Your account has not been confirmed ! - Please contact us.");
		$(".registerForm").hide();
		$("#registerList").hide();
		$(".paypal").hide();
		$("#answer").show();
		$("#rspin").hide();
	}
	$('#addUserForm').submit(function(event){
		var _userLogin = $("input[name='email']").val();
		var _userPassword = $("input[name='userPassword'").val();
		var _firstName = $("input[name='firstName']").val();
		var _familyName = $("input[name='familyName']").val();
		var _asm = $("input[name='asm'").val();
		event.preventDefault();
		
		if(checkRegisterForm()){
			var jqxhr = $.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "addUser",userLogin: _userLogin,userPassword: _userPassword,asm:_asm,firstName:_firstName,familyName:_familyName},
				function(data){
					if(data.result['code']==200){
						$(".registerForm").hide();
						$("#answer").show();
						$("#rspin").hide();
					}else{
						$('#formContent').html("Error - Please contact us!");
						$("#rspin").hide();
					}
			}
			);
			$(".registerForm").hide();
			$("#rspin").show();
		}
		event.preventDefault();
	}
	);

}
function checkRegisterForm(){
	var $emailAddr = $("input[name='email']").val();
	var $firstName = $("input[name='firstName']").val();
	var $familyName = $("input[name='familyName']").val();
	var $password = $("input[name='userPassword']").val();
	var $passwordConfirmation = $("input[name='userPasswordConfirmation']").val();
	$("input[name='email']").css("background-color","");
	$("input[name='firstName']").css("background-color","");
	$("input[name='familyName']").css("background-color","");
	$("input[name='userPassword']").css("background-color","");
	$("input[name='userPasswordConfirmation']").css("background-color","");
	
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var $emailError=false;
	var $firstNameError=false
	var $familyNameError=false;
	var $passwordError=false;
    
	if(emailReg.test($emailAddr)==false || $emailAddr=='') $emailError = true;
	if($firstName=='') $firstNameError=true;
	if($familyName=='') $familyNameError = true;
	if($password=='' || $password != $passwordConfirmation || $password.length < 4 ) $passwordError = true;
	
	if($emailError) $("input[name='email']").css("background-color","red");
	if($firstNameError) $("input[name='firstName']").css("background-color","red");
	if($familyNameError)  $("input[name='familyName']").css("background-color","red");
	if($passwordError){
		$("input[name='userPassword']").css("background-color","red");
		$("input[name='userPasswordConfirmation']").css("background-color","red");
	
	}
	
	
	return !($emailError || $firstNameError || $familyNameError || $passwordError);



}