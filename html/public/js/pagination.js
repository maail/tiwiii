$(document).ready(function()
{
	/*function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}*/
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
	
	function loadData(page,genre)
	{
		loading_show();
		
		$.ajax
		({
			type: "POST",
			url: "tv/shows/"+genre+"/"+page+"",
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
	loadData(1,"all"); // For first time page load default results
	$('.pagination li').live('click',function(){
		var page = $(this).attr('page');
		var genre = $(this).attr('genre');
		loadData(page,genre);
	});
	
	/*$('#filter-genre ul li a').click(function(){
		$('#filter-genre ul li a').removeClass('selected');
		$(this).addClass('selected');
		
		var url  = $(this).attr('href');
		var hash = url.substring(url.indexOf('#'));
		var genre = hash.replace('#', '');
		
		loadData(1,genre);
	});*/
	
	$(window).hashchange( function(){
		
		
				
		$('#filter-genre ul li a').removeClass('selected');
		var url  = $(this).attr('href');
		
		var genre_hash = location.hash;
		var genre =(genre_hash.replace(/\/?#/, ""));
		
		if(genre == ""){genre="all";$('#all').addClass('selected');}
		
		$(genre_hash).addClass('selected');
		
		var genre_name = $(genre_hash).text();
		if(genre_name != '')
		{
			document.title= pagetitle + " / " + genre_name;
		}
		
		loadData(1,genre);
	});
	
	$(window).hashchange();
	

});

	