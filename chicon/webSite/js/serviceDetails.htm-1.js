var ledDescriptionTab = new  Array();

function initUI_detailed(id){
	$(".product-details").html(""); //Vidage du contenu
	$.getJSON("/chicon/webServices/serviceRelatedWebService.php",{cmd: "getServiceDescription",srvId : id},
			function(data){
				if(data.result['code']==200){
					var str =  "<div class='img-box'>";
						str += "    <div class='box-frame'>&nbsp;</div>";
						str += "	<img src='"+data.result['data'].icon+"' height=178 width=145 alt='Product Image' />";
						str += "	<div class='basic' data-average=12 data-id=1></div>";
						str += "</div>";
						
						str +=  "<div class='pr-entry-details'>";
						str +=  "	<h4>"+data.result['data'].name+"</h4>";
						str +=  "	<p>"+data.result['data'].description+"</p>";
						str +=  "	<div class='additional'>"
						str +=  "		<div class='pr-entry-rate'>Rate it:";
						str +=  "			<div class='basic' data-average=12 data-id=2></div>";
						str +=  "		</div>";
						str +=  "		<div class='pr-entry-rate'>";
						str +=  "			<a href='#' class='addToCartBtn'><img src='css/images/add_to_cart.png' height='30px' width='120px' /></a>";
						str +=  "		</div>";
						$("img.ledIcon").css("cursor","pointer");
						
						
						for(var j in data.result['data'].ledList){
						
							str +=  "		<div class='pr-entry-rate'>";
							str +=  "			<img src='"+data.result['data'].ledList[j].icon+"' ledId="+data.result['data'].ledList[j].id+" height='50px' width='50px' class='ledIcon'/>";
							str +=  "		</div>";
							ledDescriptionTab[data.result['data'].ledList[j].id] = data.result['data'].ledList[j].description;
							
						}
						
						str +=  "	</div>";
						str +=  "</div>";
						
						str += "<div  class='reviews'>";
						str += "	<div id='ledDescription'></div>";
						str += "</div>";
					
					
						$(".product-details").append(str);
						$("img.ledIcon").css("cursor","pointer");
						$("img.ledIcon").mouseover(
							function(){
									$(document).find("#ledDescription").html("<h4>Ligth Description</h4><p>"+ledDescriptionTab[this.getAttribute('ledId')]+"</p>");
							});	
							$("img.ledIcon").mouseout(
								function(){
									$("#ledDescription").html("");
							});
						$(".basic").jRating();
						$("a.addToCartBtn").click(
							function(){
								srvId = this.getAttribute("srvId");
								//Vérifier si l'utilisateur est loggé
								if($("#myAccountTab").length){
									addToCart(srvId);
									
								}else{
									window.alert("Please log in first!");
								}
							}
						);
				}
			}
		);	
	}
		