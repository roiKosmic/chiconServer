function initUI(){
event.preventDefault();
$("#snSpin").hide();
$("#mnSpin").hide();
	$.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "getHardwares"},
			function(data){
				if(data.result['code']==200){
					 $("#hdwListTable").html('<tr><td>Model</td><td>Serial<td>Firmware</td><td>Magic Number</td><td>Config</td><td>Delete</td></tr>');
					 for( var i in data.result['data'].hdwList){
						$("#hdwListTable").append("<tr><td>"+data.result['data'].hdwList[i].model+"</td><td>"+data.result['data'].hdwList[i].serial+"</td><td>"+data.result['data'].hdwList[i].firmware+"</td><td>"+data.result['data'].hdwList[i].mn+"</td><td><div serial='"+data.result['data'].hdwList[i].serial+"' class='cfgBtn'></div></td><td><div serial='"+data.result['data'].hdwList[i].serial+"' class='delBtn'></div></td></tr>");
					 }
					 
					 $(".cfgBtn").click(
						function(event){
							event.preventDefault();
							$serial = this.getAttribute("serial");
							window.location = "configureDevice.html#"+$serial;
						}
					);
					$(".delBtn").click(
						function(event){
							event.preventDefault();
							var serial = this.getAttribute("serial");
							
							var jqxhr = $.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "delFromUser",hdwSerial: serial},
								function(data){
									if(data.result['code']==200){
										initUI();
										alert("Device deleted from your account!");
									}else{
										alert("Error - device not deleted!");
									}
								}
								);
						}
					);
				}else{
                     alert(data.result['data']['error']);
					 $("#content .innerConfigDevice").html("Error - not logged in");
                }
				
				
			});
			
	$('#addDeviceForm').submit(function(event){
		event.preventDefault();
		var _deviceSerial = $('#deviceSerial').val();
		$("#mnSpin").show();
		$('#addDeviceForm').hide();
		var jqxhr = $.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "assignToUser",hdwSerial: _deviceSerial},
			function(data){
				if(data.result['code']==200){
					initUI();
					$('#deviceSerial').val("");
					alert("Device added to your account!");
				}else{
					alert("Error - device not added!");
				}
				$('#addDeviceForm').show();
			}
		);
		
	});
	
	$('#requestDeviceForm').submit(function(event){
		event.preventDefault();
		
		var _deviceType = $('#hdwType').val();
		if(_deviceType == 0){
			alert("You have created a new type of hardware, please send us details for integration");
		}else{
			$("#snSpin").show();
			$('#requestDeviceForm').hide();
			var jqxhr = $.getJSON("/chicon/webServices/userRelatedWebService.php",{cmd: "requestSerial",hdwType: _deviceType},
				function(data){
					if(data.result['code']==200){
						initUI();
						alert("Serial requested successfully - check your e-mail!");
					}else{
						alert("Internal Error!");
					}
					$('#requestDeviceForm').show();
				}
			);
		}
		
	});
}