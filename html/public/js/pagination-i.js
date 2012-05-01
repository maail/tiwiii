/*$(document).ready(function()
{
	var urlpath = "http://tiwiii2.local";	
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
});*/


(function(window,undefined){
	var
		History   = window.History,
		State     = History.getState(),
		$log      = $('#log'),
		urlpath   = "http://tiwiii2.local",	
	    pagetitle = document.title;
		
	//History.log('initial:', State.data, State.title, State.url);
	History.Adapter.bind(window,'statechange',function(){
		//History.log('statechange:', State.data, State.title, State.url);
		var State  = History.getState();
		var page   = State.data.page;
		var type   = State.data.type;
		var filter = State.data.filter;
		
		loading_show();
		document.title= pagetitle + " / " + ucwords(type);
	    $('#filter-genre ul li a').removeClass('selected');
		if(type == ""){type="fall";$('#fall').addClass('selected');}
		$("#"+type).addClass('selected');
		
		$.ajax
		({
			type: "POST",
			url: "index/home/"+type+"/"+page+"/"+filter+"",
			success: function(msg)
			{
				$("#container").ajaxComplete(function(event, request, settings)
				{
					$("#container").html(msg).show();
					loading_hide();
				});
			}
		});
	});

	 $("#filter-genre ul li a").click(function(){	
		var type  = $(this).attr('href');
		History.pushState({page:1,type:type,filter:"All"}, type, "/"+type+"");
		return false;
	});
	
	$('.pagination li').live('click',function(){
		var page     = $(this).attr('page');
		var type     = $(this).attr('type');
		var filter   = $(this).attr('filter');
		var theclass = $(this).attr('class');
		
		if(theclass == "filters"){
			History.pushState({page:1,type:type,filter:filter}, type, "/"+type+"");
			document.title= pagetitle + " / " + ucwords(type) + " / " + ucwords(filter);
		}else{
			History.pushState({page:page,type:type,filter:filter}, type, "/"+type+"");
		}
	});
	
	History.pushState({page:1,type:"fall",filter:"All"}, "fall", "http://tiwiii2.local/fall"); 
	
	
	
})(window);

function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function loading_show()
{
	$('#wrapper #loading').html("<img src='public/images/loader.gif'/>").fadeIn('fast');
	$(".show-thumb").html('<img src="public/images/loader.gif" />').fadeIn('fast');
}
	
function loading_hide()
{
	$('#wrapper #loading').fadeOut();
}



