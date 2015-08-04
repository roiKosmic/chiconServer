function initUI_cartDetail(){
	$(".cart ul").html("");
	
	$(".cart-top-info").html($("#cartLink").attr("data-notifications")+" Items");
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "getCartDescription"},
			function(data){
				if(data.result['code']==200){
					if(data.result['data'].hasOwnProperty("cart")){
						 for( var i in data.result['data'].cart){
						 var str = "<li class='cart-item'>";
							str += "<span class='cart-item-pic'><img src='"+data.result['data'].cart[i].icon+"'></span>";
							str += data.result['data'].cart[i].name;
							str +=  "<span class='cart-item-desc'>"+data.result['data'].cart[i].description+"</span>";
							str += "<span class='cart-item-price'>&euro;0.00</span>";
							str += "<span class='cart-item-del' srvId='"+data.result['data'].cart[i].id+"'><img src='./css/images/cross_circle.png'/></span>";
							str+= "</li>";
							$(".cart ul").append(str);
						 }
					 
						$(".cart-item-del").click(
							function(event){
								event.preventDefault();
								var srvId_ = this.getAttribute("srvId");
								
								var jqxhr = $.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "removeCartItems",srvId: srvId_},
									function(data){
										if(data.result['code']==200){
											updateCartBubble();
											initUI_cartDetail();
											$(".cart-top-info").html($("#cartLink").attr("data-notifications")+" Items");
										}else{
											alert("Error - service not removed!");
										}
									}
									);
							}
						);
						$(".cart-button").click(
							function(event){
								event.preventDefault();
								
								var jqxhr = $.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "confirmCartOrder"},
									function(data){
										if(data.result['code']==200){
											updateCartBubble();
											$(".cart ul").html("Your order has been confirmed");
										}else{
											alert("Error - Order not confirmed");
										}
									}
									);
							}
						);
						
					}
				}else{
                     //alert(data.result['data']['error']);
					 $(".cart ul").html("<li>Error - not logged in</li>");
                }
				
			}
		);

}