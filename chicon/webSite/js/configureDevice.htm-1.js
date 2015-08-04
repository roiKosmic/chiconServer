var mySerial = window.location.hash.substring(1);
var trash_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Delete this service' class='ui-icon ui-icon-trash'></a>";
var gear_icon = "<a href='link/to/trash/script/when/we/have/js/off' title='Configure this service' class='ui-icon ui-icon-gear'></a>";
/*var a = 11;
var b = 1;
var c = 4;

var result = a&b;
alert("1st result : "+result);
result = a&c;
alert("2nd result : "+result);
*/

function initUI(){
	var curSrv;
    var ele   = $('#scroll');
    var speed = 25, scroll = 5, scrolling;
	
	
	
		$( "#ledDialog" ).dialog({      
		autoOpen: false,
		show: {        
			effect: "blind",
			duration: 1000
		},      
		hide: {
			effect: "blind",
			duration: 1000      
			
		},
		close: function( event, ui ) {
			closeLedDialog();
		}
		
		}
	
	);
	
	
	$( "#configDialog" ).dialog({      
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
			of: window	
		},
		width: "auto",
		height:"auto"
	});
	
	$( "#serviceDetailsDialog" ).dialog({      
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
	
	
	  $(".boxm").droppable({
		accept: "li.drag",
		activeClass: "ui-state-highlight",
		drop: function( event, ui ) { 
			var $div = $("#content");
			$( "#ledDialog" ).dialog( "option" ,"position",{
				my:"left top",
				at:"left top",
				of:$div
			});
			//srv global ID
			var srvRef = ui.draggable.find(".serviceList").attr("globalId");
			//Assign service to Hardware and get local srv ID
			assignService(mySerial,srvRef,ui.draggable);
	
		}    
			  
	});
	

	
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "getServices"},
			function(data){
				if(data.result['code']==200){
					for( var i in data.result['data'].srvList){
						$("#availableServiceList").append("<li class='drag'><div class='serviceList' globalId='"+data.result['data'].srvList[i].id+"'</div><img  src='"+data.result['data'].srvList[i].icon+"' height='50' width='50'/><div class='serviceName'>"+data.result['data'].srvList[i].name+"</div><a href='link/to/trash/script/when/we/have/js/off' title='Service Details' class='ui-icon ui-icon-zoomin'></a></li>");
					 }
				
						$(".drag").draggable({
							revert: "invalid",
							containment: "document",
							appendTo:"#header",
							helper:"clone",
							cursor:"move"
						});
						

				}
			}
	);
	
	
	
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "getAssociatedServices",hdwSerial: mySerial},
			function(data){
				$("#associatedServiceList").empty();
				if(data.result['code']==200){
					for( var i in data.result['data'].srvList){
						$("#associatedServiceList").append("<li class='drag'><div class='serviceList' globalId='"+data.result['data'].srvList[i].globalId+"' localId='"+data.result['data'].srvList[i].localId+"'</div><img src='"+data.result['data'].srvList[i].icon+"' height='50' width='50'/><div class='serviceName'>"+data.result['data'].srvList[i].name+"</div><a href='link/to/trash/script/when/we/have/js/off' title='Service Details' class='ui-icon ui-icon-zoomin'></a><a href='link/to/trash/script/when/we/have/js/off' title='Delete this service' class='ui-icon ui-icon-trash'></a><a href='link/to/trash/script/when/we/have/js/off' title='Configure this service' class='ui-icon ui-icon-gear'></a></li>");
					 }
				
				}
			}
	);
		
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "getHdwConfig",hdwSerial: mySerial},
			function(data){
				$(".boxm .dragToLed").remove();
				var htmlCode='';
				if(data.result['code']==200){
					for( var i in data.result['data'].hdw.ledList){
						htmlCode = "<div class='dragToLed' capability="+data.result['data'].hdw.ledList[i].capability+" ledId="+data.result['data'].hdw.ledList[i].id+">";
						if(data.result['data'].hdw.ledList[i].icon){
							htmlCode +="<div style='position:absolute;z-index:0;'>Drag light here</div>";
							htmlCode +="<div class='srvContainer' style='position:absolute;z-index:10;'>";
							htmlCode += "<img  globalId="+data.result['data'].hdw.ledList[i].srv.globalId+" localId="+data.result['data'].hdw.ledList[i].srv.localId+" src='"+data.result['data'].hdw.ledList[i].srv.icon+"' height='50' width='50'/>";						
							htmlCode += "<img  ledSrvId="+data.result['data'].hdw.ledList[i].srv.ledId+" globalId="+data.result['data'].hdw.ledList[i].srv.globalId+" localId="+data.result['data'].hdw.ledList[i].srv.localId+" src='"+data.result['data'].hdw.ledList[i].icon+"' height='50' width='50'/>";
							htmlCode +="</div></div>";
						}else{
							htmlCode +="<div style='position:absolute;z-index:0;'>Drag light here</div>";
							htmlCode +="<div class='srvContainer' style='position:absolute;z-index:10;'>";
							htmlCode +="</div></div>";
						}
						
						$(".boxm").append(htmlCode);
						
						
					 }
				
					$(".dragToLed").droppable({
						accept:function(d){
							if($(this).find("img").length != 0) return false;
							if($(d).is("img.drag")){
								var ledType = $(d).attr("type");
								var  hdwCapability = $(this).attr("capability");
								var mask = ledType & hdwCapability;
								if(mask != 0) return true;
								return false;	
							}
							return false;
						},
						activeClass:"ui-state-highlight",
						drop: function(event,ui){
							deleteLight(ui.draggable,this);
						}
					});
				}
			}
	);
	
  
	
	$(".ui-icon-grip-dotted-horizontal").click(function(event){
		var cur = $(this).parent().find(".ledDescription");
		if(cur.css("overflow") =="visible"){
			cur.css("overflow","hidden")
			cur.css("height","50px");
		}else{
			cur.css("overflow","visible")
			cur.css("height","auto");
		}
		
	}
	);

	
	$(".dragToLed").droppable({
		accept:"img.drag",
		activeClass:"ui-state-highlight",
		drop: function(event,ui){
			$(this).find("span").html("");
			deleteLight(ui.draggable,this);
		}
	});
	
	
	
	
    $('#scroll-up').mouseenter(function() {
        // Scroll the element up
        scrolling = window.setInterval(function() {
            ele.scrollTop( ele.scrollTop() - scroll );
        }, speed);
    });
    
    $('#scroll-down').mouseenter(function() {
        // Scroll the element down
        scrolling = window.setInterval(function() {
            ele.scrollTop( ele.scrollTop() + scroll );
        }, speed);
    });
    
    $('#scroll-up, #scroll-down').bind({
        click: function(e) {
            // Prevent the default click action
            e.preventDefault();
        },
        mouseleave: function() {
            if (scrolling) {
                window.clearInterval(scrolling);
                scrolling = false;
            }
        }
    });
	
	
	
}

function getLedDescription(idSrv,localId){
		$("#ledList").empty();
		
		$.getJSON("/chicon/webServices/serviceRelatedWebService.php",{cmd: "getLedDescription",srvId: idSrv},
		function(data){
				if(data.result['code']==200){
					for( var i in data.result['data'].ledList){
						$("#ledList").append("<li style='list-style:none'><div class='ledList'><img class='drag' ledSrvId="+data.result['data'].ledList[i].id+" localId="+localId+" type="+data.result['data'].ledList[i].type+" src='"+data.result['data'].ledList[i].icon+"' height='50' width='50'/><div class='ledDescription'><b>"+data.result['data'].ledList[i].name+"</b> "+data.result['data'].ledList[i].description+"</div><span class='ui-icon ui-icon-grip-dotted-horizontal'></span></div></li>");
					 
					 }
					 $(".drag").draggable({
							revert: "invalid",
							containment: "document",
							appendTo:"#header",
							helper:"clone",
							cursor:"move"
						});
				}
				
		}
		);
		$( "#ledDialog" ).dialog("open");
		
}

function bindClick(){
	$(document).on("click",null,function(event){
					
					$target = $( event.target );
					if ( $target.is( "a.ui-icon-trash" ) ) {
						srvRef = $target.parent().attr('localId');
						$("[localId='"+srvRef+"']").remove();
						$target.parent().remove();
						removeService(srvRef);
						return false;
					}
					
					if( $target.is("a.ui-icon-gear")){
						srvRef = $target.parent().attr('localId');
						getConfigureForm(srvRef);
						$( "#configDialog" ).dialog("open");
						return false;
					}
					
					if($target.is("a.ui-icon-zoomin")){
						showDialog($target.parent().attr("globalId"));
						return false;
					}
					
					if($target.is("input[type='submit']")){
						localId = $("input[name='srvLocalId']").attr('srvLocalId');
						getConfigureForm(localId,true);
						return false;
					}
					
					if($target.is("input[type='radio']")){
						return true;
					}
					return true;
	});
	
}

function showDialog(id){  //load content and open dialog
	 $("#serviceDetailsDialog").load("serviceDetails.html",function(){
		initUI_detailed(id);
		});
    $("#serviceDetailsDialog").dialog("open");         
}


function removeService(localId){
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "unAssignService",hdwSerial: mySerial,srvLocalId : localId},
	function(data){
	
	
	});

}
 function deleteLight( $item,$rec ) {
		$item.fadeOut(function(){
			$item.parent().parent().remove();
			var localId = $item.attr("localId");
			var myBox = $($rec).find("div.srvContainer");
			$("img[currentSrv='true']")
			.clone()
			.attr("localId",localId)
			.appendTo(myBox)
			.removeAttr("currentSrv")
			.fadeIn("slow");
			//$item.appendTo($rec.find("div.srvContainer")).fadeIn();
			$item.appendTo(myBox).fadeIn();
			var ledHdwId = $($rec).attr('ledId');
			var ledSrvId = $($item).attr('ledSrvId');
			mapLed(localId,ledHdwId,ledSrvId);
			if($("#ledList").find("li").length == 0){
				$("#ledDialog").dialog("close");
				$("img[currentSrv='true']").removeAttr("currentSrv");
				getConfigureForm(localId,false);
				$( "#configDialog" ).dialog("open");
				
			}
			
		});
	}
	
function mapLed($localId, $ledHdwId, $ledSrvId){
	//alert("Srv Local ID :"+$localId+"\n Led ID HDW"+$ledHdwId+"\nLedID Srv"+$ledSrvId);
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "mapLed",hdwSerial: mySerial,srvLocalId : $localId,ledHdwId : $ledHdwId, ledSrvId : $ledSrvId},
	function(data){
	
	
	});

}

function getConfigureForm(localId,flag){
	var dataObj;
	if(flag){
		dataObj = $("#configServiceForm").serialize();
	}else{
		dataObj = {};
		dataObj['cmd'] = "configureService";
		dataObj['hdwSerial'] = mySerial;
		dataObj['srvLocalId'] = localId;
	}
	
	$.get( "/chicon/webServices/userRelatedWebService.php",dataObj,
		function( html ) {
			$("#configDialog").html(html);
		}
	);
	
}
function closeLedDialog(){
	$("img[currentSrv='true']").removeAttr("currentSrv");
	//TODO Remove all services and lights assign to hardware;
}

function assignService(sHdw,globalId,el){
	var localId;
	var processed = false;
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "assignService",hdwSerial: sHdw,srvId : globalId},
			function(data){
				if(data.result['code']==200){
					localId = data.result['data'];
					getLedDescription(globalId,localId);
					//Flag logo of the current srv
					el.find("img").attr("currentSrv","true");
					el.clone()
					.appendTo($("#associatedServiceList"))
					.find(".serviceList")
					.attr('localId',localId)
					.append(trash_icon)
					.append(gear_icon)
					.find("img").removeAttr("currentSrv"); //Remove flag on the clone
					
				}else{
					//TO DO Message d'erreur et gestion de l'UI
				}
			});
	
	return localId;

}