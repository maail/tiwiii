$('#activity').click(function()
{
	window.location.hash = 'activity';
	$('.show-nav li').removeClass('selected');
	$(this).addClass('selected');
	$('#the-show-ep').hide();
	$('ul.menu').hide();
	var show = $(this).attr('show');
	
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
	
	function loadData(show, page)
	{
		loading_show();
		
		$.ajax
		({
			type: "POST",
			url: ""+urlpath+"/show/activity/"+show+"/"+page+"",
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
	loadData(show,1);
	
	$('.pagination li').live('click',function(){
		var page = $(this).attr('page');
		var show = $(this).attr('show');
		loadData(show, page);
		
	});	
	
});

$('#overview').click(function()
{
	window.location.hash = '#';
	$('#container').empty();
	$('.show-nav li').removeClass('selected');
	$(this).addClass('selected');
	$('#the-show-ep').show();
	$('ul.menu').show();
	
});	
