$('document').ready(function(){	<!-- following 3 lines use css to draw a book icon -->
	$('#siteIcon').html(
		'<span style="display: block; float: left; margin:0; background: linear-gradient(to right, #2E15B0, #31AEF6); width: 15px; height: 24px;"></span>'
		+'<span style="display: block; float: left; margin:0; background: #000000; width: 1px; height: 24px;"></span>'
		+'<span style="display: block; float: left; margin:0; background: linear-gradient(to right, #31AEF6, #2E15B0); width: 15px; height: 24px; margin-right: 8px;"></span>'
	);
	showChildMenu();
});

function showChildMenu(child_id){	var i;
	for (i=1;i<6;i++){		if (i == child_id){			if ( !$('#SubMenu'+i).is(':visible') ){				$('#SubMenu'+i).show();
			}
		} else {			$('#SubMenu'+i).hide();
		}	}
}