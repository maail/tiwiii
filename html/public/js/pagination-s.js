$(document).ready(function()
{
	var pagetitle = document.title;
	var urlpath = "http://tiwiii2.local";
	function loading_show()
	{
		$('#wrapper #loading').html("<img src='"+urlpath+"/public/images/loader.gif'/>").fadeIn('fast');
	}
	
	function loading_hide()
	{
		$('#wrapper #loading').fadeOut();
	}
	
	function loadData(type, page)
	{
		loading_show();
		
		$.ajax
		({
			type: "POST",
			url: ""+urlpath+"/settings/view/"+type+"",
			success: function(msg)
			{
				$("#container").ajaxComplete(function(event, request, settings)
				{
					loading_hide();
					$("#container").html(msg);
					
				});
			}
		});
	}
	var type = 'Basic';
	$('.pagination li').live('click',function(){
		var page   = $(this).attr('page');
		var type = $(this).attr('type');
		loadData(type, page);
		
	});
	
	$(window).hashchange( function(){
		
		$('#filter-genre ul li a').removeClass('selected');
		var url  = $(this).attr('href');
		
		var type_hash = location.hash;
		var type =(type_hash.replace(/\/?#/, ""));
		
		if(type == ""){type="basic";$('#basic').addClass('selected');}
		
		$(type_hash).addClass('selected');
		
		var type_name = $(type_hash).text();
		if(type_name != '')
		{
			document.title= pagetitle + " / " + type_name;
		}
				
		loadData(type,1);
	});
	
	$(window).hashchange();
	
	
	
	
});