$(document).ready(function()
{
	var pagetitle = document.title;
	var urlpath = "http://tiwiii.com";
	function loading_show()
	{
		$('#wrapper #loading').html("<img src='"+urlpath+"/public/images/loader.gif'/>").fadeIn('fast');
	}
	
	function loading_hide()
	{
		$('#wrapper #loading').fadeOut();
	}
	
	function loadData(filter, page)
	{
		loading_show();
		
		$.ajax
		({
			type: "POST",
			url: ""+urlpath+"/activity/feed/"+filter+"/"+page+"",
			success: function(msg)
			{
				$("#container").ajaxComplete(function(event, request, settings)
				{
					loading_hide();
					$("#container").html(msg);
					
					var db_time   = $("#time-diff").text();
					var offset    = moment().zone(); 
					var time_diff =  Number(db_time) - Number(offset);
									
					$('.days-ago').each(function(i, obj){						
						var date     = $(obj).text();										
						var mom      = moment(date).add('m',time_diff);	
						var timegone = mom.fromNow();				
						$(obj).text(timegone);
					});
				});
			}
		});
	}
	var type = 'All';
	$('.pagination li').live('click',function(){
		var page   = $(this).attr('page');
		var filter = $(this).attr('filter');
		loadData(filter, page);
		
	});
	
	$(window).hashchange( function(){
		
		$('#filter-genre ul li a').removeClass('selected');
		var url  = $(this).attr('href');
		
		var filter_hash = location.hash;
		var filter =(filter_hash.replace(/\/?#/, ""));
		
		if(filter == ""){filter="all";$('#all').addClass('selected');}
		
		$(filter_hash).addClass('selected');
		
		var filter_name = $(filter_hash).text();
		if(filter_name != '')
		{
			document.title= pagetitle + " / " + type_name;
		}
				
		loadData("All",1);
	});
	
	$(window).hashchange();
	
});