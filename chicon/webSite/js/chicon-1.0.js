function showLoginForm(){
	document.getElementById('loginform').style.visibility='visible';
	document.getElementById('loginform').style.display='inline';
	document.getElementById('username').select();
	return true;
}

function hideLoginForm(){
	document.getElementById('loginform').style.visibility='hidden';
	document.getElementById('loginform').style.display='none';
	return true;
}
function displayUser(username_){
	document.getElementById('welcomemsg').innerHTML="Welcome "+username_;
	document.getElementById('loginlink').innerHTML="Logout";
	$('#loginlink').attr('onclick','javascript:logout();');
	return true;
}

function login(){			
			$.getJSON("/chicon/webServices/loginWebService.php",{username: $("#username").val(),password: $("#password").val()},
			function(data){
				if(data.result['code']==200){
					 hideLoginForm();
					 displayUser($("#username").val());
					 $("#registerTab").remove();
					 $("#navigation  ul").append("<li id='myAccountTab'><a href='listDevice.html' title='Account'><span>My Account</span></a></li>");
			         window.location.replace("listDevice.html");
				}else{
                     alert(data.result['data']['error']);
                }
			});
			
 }
 
 function logout(){
		$.getJSON("/chicon/webServices/loginWebService.php",{logout: 1},
			function(data){
				if(data.result['code']==200){
					$("#navigation  ul").append("<li id ='registerTab'><a href='' title='Register'><span>Register</span></a></li>");
					$("#myAccountTab").remove();
					 
					 $('#welcomemsg').html("Welcome on Chic'on!");
					 $('#loginlink').html("login");
					 $('#loginlink').attr('onclick','javascript:showLoginForm();');
					 updateCartBubble();
					 var page = document.location.pathname.match(/[^\/]+$/)[0];
					 switch(page){
						case "serviceShop.html":
						break;
						default:
						window.location.replace("serviceShop.html");	
					 }
				}else{
                     alert(data.result['data']['error']);
                }
			});
 }
 
 function isLoggedIn(){
 
		$.getJSON("/chicon/webServices/loginWebService.php",{isLoggedIn: 1},
			function(data){
				if(data.result['code']==200){
					//IF Yes
					updateCartBubble();
					if(data.result['data']['user']){
						displayUser(data.result['data']['user'].username);
						$("#registerTab").remove();
						$("#navigation  ul").append("<li id ='myAccountTab'><a href='listDevice.html' title='Account'><span>My Account</span></a></li>");
						var page = document.location.pathname.match(/[^\/]+$/)[0];
						if(page=="listDevice.html" || page == "configureDevice.html"){
							$("#myAccountTab a").attr('class','active');
						}
						
						
					}
				}else{
					alert(data.result['data']['error']);
				}
			}
		);
 }
 
 function updateCartBubble(){
	var numItems = 0;
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "getCartItemsCount"},
		function(data){
			if(data.result['code']==200){
				numItems = data.result['data']['cart'];
				if(numItems != 0){
					$("#cartLink").attr("data-notifications",numItems);
				}else{
					$("#cartLink").removeAttr("data-notifications");
				}
			}
		}
		);
		
 }
 
 function addToCart(id){
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "addToCart",srvId: id},
			function(data){
				if(data.result['code']==200){
					window.alert("Service added to your cart !");
					updateCartBubble();
				}else{
					window.alert("Error");
				}
			}
		);

}

function initCommonUI(){
$("#cartLink").click(
		function(event){
			event.preventDefault();
			showCart();
		}
	);
	
$("#contactLink").click(
		function(event){
			event.preventDefault();
			showContactForm();
		}
	);
	
$("#aboutLink").click(
		function(event){
			event.preventDefault();
			showAbout();
		}
	);
	
$("#disclaimerLink").click(
		function(event){
			event.preventDefault();
			showDisclaimer();
		}
	);
	
$("#detailedCartDialog").dialog({  //create dialog, but keep it closed
   autoOpen: false,
   show: {        
			effect: "blind",
			duration: 1000
		},      
		hide: {
			effect: "blind",
			duration: 1000      
			
		},

		position: {
			my: "top",
			at: "top",
			of: "#header",
		},
		width: "525",
		height:"500",
});	

$("#disclaimerDialog").dialog({  //create dialog, but keep it closed
   autoOpen: false,
   show: {        
			effect: "blind",
			duration: 1000
		},      
		hide: {
			effect: "blind",
			duration: 1000      
			
		},

		position: {
			my: "top",
			at: "top",
			of: "#header",
		},
		width: "525",
		height:"500",
});	


$("#aboutDialog").dialog({  //create dialog, but keep it closed
   autoOpen: false,
   show: {        
			effect: "blind",
			duration: 1000
		},      
		hide: {
			effect: "blind",
			duration: 1000      
			
		},

		position: {
			my: "top",
			at: "top",
			of: "#header",
		},
		width: "450",
		height:"370",
});	

$("#contactDialog").dialog({  //create dialog, but keep it closed
   autoOpen: false,
   show: {        
			effect: "blind",
			duration: 1000
		},      
		hide: {
			effect: "blind",
			duration: 1000      
			
		},

		position: {
			my: "top",
			at: "top",
			of: "#header",
		},
		width: "450",
		height:"450",
});	

}
 
 function showContactForm(){
	$("#contactDialog").load("contactForm.html",function(){
		initUI_contactForm();
	});
    $("#contactDialog").dialog("open");       
 
 
 }
 
 function showDisclaimer(){
	$("#disclaimerDialog").load("termOfUse.html");
    $("#disclaimerDialog").dialog("open");         

}
 
 function showAbout(){
	$("#aboutDialog").load("about.html");
    $("#aboutDialog").dialog("open");
 
 }
 
 function showCart(){
	$("#detailedCartDialog").load("cartDetail.html",function(){
		initUI_cartDetail();
		});
    $("#detailedCartDialog").dialog("open");         

}

function getParameterByName( name ){
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)", 
      regex = new RegExp( regexS ),
      results = regex.exec( window.location.href );
  if( results == null ){
    return "";
  } else{
    return decodeURIComponent(results[1].replace(/\+/g, " "));
  }
}
