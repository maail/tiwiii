$(document).ready(function()
{
	var urlpath = "http://tiwiii.com";	
	var pagetitle = document.title;
	
	function loading_show()
	{
		$('#wrapper #loading').html("<img src='public/images/loader.gif'/>").fadeIn('fast');
		$(".show-thumb").html('<img src="public/images/loader.gif" />').fadeIn('fast');
	}
	
	function loading_hide()
	{
		$('#wrapper #loading').fadeOut();
	}
	
	function loadData(page,type,filter)
	{
		loading_show();
		
		$.ajax
		({
			type: "POST",
			url: "index/home/"+type+"/"+page+"/"+filter+"",
			//data: "page="+page+"&type="+type+"",
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
	var type = 'fall';
	//loadData(1,type,"All"); // For first time page load default results
	$('.pagination li').live('click',function(){
		var page   = $(this).attr('page');
		var type   = $(this).attr('type');
		var filter = $(this).attr('filter');
		var theclass  = $(this).attr('class');
			
		if(theclass == "filters"){
			loadData(1,type,filter);
		}else{
			loadData(page,type,filter);
		}
		
	});
	
	/*$('#filter-genre ul li a').click(function(){
		$('#filter-genre ul li a').removeClass('selected');
		$(this).addClass('selected');
		
		var url  = $(this).attr('href');
		var hash = url.substring(url.indexOf('#'));
		var type = hash.replace('#', '');
		
		loadData(1,type);
	});*/
	
    $(window).hashchange( function(){
		
		
		$('#filter-genre ul li a').removeClass('selected');
		var url  = $(this).attr('href');
		
		var type_hash = location.hash;
		var type =(type_hash.replace(/\/?#/, ""));
		
		if(type == "_=_")
		{
			var feedback     = $('#feedback').text();
			window.location.hash = '';
			$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
			$('#feedback').text(feedback);
		}
		else
		{
			if(type == ""){type="fall";$('#fall').addClass('selected');}
			
			$(type_hash).addClass('selected');
			
			var type_name = $(type_hash).text();
			if(type_name != '')
			{
				document.title= pagetitle + " / " + type_name;
			}
					
			loadData(1,type,"All");
		}
	});
	
	$(window).hashchange();
});