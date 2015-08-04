function initUI(){
	$("#products").html(""); //vide le contenu statique
	
	
	$.getJSON("/chicon/webServices/serviceRelatedWebService.php",{cmd: "getAllServices"},
			function(data){
				if(data.result['code']==200){
					var j = 0;
					for( var i in data.result['data'].srvList){	
						if(j==3){j=0};
						if(j==0){
							var myRow = $("<div class=row></div>").appendTo("#products");
							var myPr = $("<div class='product'></div>").appendTo(myRow);
						}else if(j==2){
							var myPr = $("<div class='product pr-last'></div>").appendTo(myRow);
						}else{
							var myPr = $("<div class='product'></div>").appendTo(myRow);
						}
						myPr.append("<div class='img-box'><div class='box-frame'>&nbsp;</div><img src='"+data.result['data'].srvList[i].icon+"' height=178 width=145 alt='Product Image' /><a href='#' srvId='"+data.result['data'].srvList[i].id+"' class='more' title='Buy'>Buy</a></div><div class='pr-entry'><h4>"+data.result['data'].srvList[i].name+"</h4><p>"+data.result['data'].srvList[i].description+"</p><span class='pr-price'><span>€</span>0<sup>.00</sup></span><a class='addToCartBtn' srvId='"+data.result['data'].srvList[i].id+"' href='#'><img src='css/images/add_to_cart.png' height='30px' width='120px' /></a></div></div>");
						
						j++;		
					 }
				$("a.addToCartBtn").click(
					function(event){
						event.preventDefault();
						srvId = $(this).attr("srvId");
						disabled = $(this).attr("deadLink");
						
						//Vérifier si l'utilisateur est loggé
						if($("#myAccountTab").length){
							//Vérifier si le bouton est actif
							if(disabled!=1){
								addToCart(srvId);
								$(this).find("img").attr("src",'css/images/add_to_cart_disabled.png');
								$(this).css("cursor","default");
								$(this).attr("deadLink","1");
								
							}
						}else{
							window.alert("Please log in first!");
						}
						return false;
					}
				
				);
				$("a.more").click(
					function(event){
						event.preventDefault();
						showDialog(this.getAttribute("srvId"));
						return false;
					});
				}
			}
	);
	
	
	
	
	
	
	
	

$("#detailedServiceDialog").dialog({  //create dialog, but keep it closed
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
			my: "center",
			at: "center",
			of: "#main",
		},
		width: "470",
		height:"500",
});




}



function showDialog(id){  //load content and open dialog
	 $("#detailedServiceDialog").load("serviceDetails.html",function(){
		initUI_detailed(id);
		});
    $("#detailedServiceDialog").dialog("open");         
}