function initUI_contactForm(){
	$("#answer").hide();
	$("#spin").hide();
	$("#contact").submit(function(e){
		e.preventDefault();
		if(checkForm()){
			console.log("sending");
			$.ajax({
				type:'POST',
				url:'/chicon/misc/contactForm.php',
				async:true,
				data:$(this).serialize(),
				success:function(res){
						console.log(res);
						$("#spin").hide();
						$("#answer").show();
				
				}
			});
			$("#form").hide();
			$("#spin").show();
		}
	
	
	});
	
	
}

function checkForm(){
	var $emailAddr = $("input[name='fromAddr']").val();
	var $subject = $("input[name='subject']").val();
	var $body = $("textarea[name='mailBody']").val();
	$("input[name='fromAddr']").css("background-color","");
	$("input[name='subject']").css("background-color","");
	$("textarea[name='mailBody']").css("background-color","");
	
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	var $emailError=false;
	var $subjectError=false;
	var $bodyError=false;
    
	if(emailReg.test($emailAddr)==false || $emailAddr=='') $emailError = true;
	if($subject=='') $subjectError=true;
	if($body=='') $bodyError = true;
	
	if($emailError) $("input[name='fromAddr']").css("background-color","red");
	if($subjectError) $("input[name='subject']").css("background-color","red");
	if($bodyError) $("textarea[name='mailBody']").css("background-color","red");
	
	
	return !($emailError || $subjectError || $bodyError);

}