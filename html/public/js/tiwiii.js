/*-----------------------------------------(feedback)--------------------------------------------*/
$(document).ready(function() {
	
	var urlpath = "http://tiwiii2.local";
	jQuery("#q").liveSearch({url: (''+urlpath+'/show/livesearch/?q=')}); // live search
   
   /* attach a submit handler to the form */
  $("#search_tvdb").submit(function(event) {
	
		/* stop form from submitting normally */
		event.preventDefault(); 
		$('#search_tvdb :input').attr('disabled', true);
		$("#stvdb").hide(); 
		$("#result-div" ).hide();
		$("#msg-div").show();
	   
	   $("#msg-div").ajaxStart(function(){     
		   $("#msg-div").empty().append("Crawling the interwebs for your query..");
		   setTimeout(function(){ $("#msg-div").empty().append("Almost there..."); }, 1500);
		   setTimeout(function(){ $("#msg-div").empty().append("Almost.."); }, 4000);
		   setTimeout(function(){ $("#msg-div").empty().append("Just a little more.."); }, 8000);
	   });
			   
		/* get some values from elements on the page: */
		var $form = $( this ),
			term = $form.find( 'input[name="search_shows"]' ).val(),
			url  = ""+urlpath+"/show/query/";
	
		/* Send the data using post and put the results in a div */
		$.post( url, { s: term },
		  function( data ) {
			  var content = $( data ).find( '#content-div' );
			  $("#result-div").empty().append( data );
			  $("#result-div").show();
			  $("#msg-div").hide();
			  $("#stvdb").show();
			  $('#search_tvdb :input').attr('disabled', false);
		  }
	   );
  });
  
   $("#update-show").click(function(){
		  $(this).css("background","url("+urlpath+"/public/images/loader-small.gif)");
		  var pathname = window.location.pathname;		  
		  var pattern = /[0-9]+/;
		  var showid = pathname.match(pattern);
		  $.getJSON(""+urlpath+"/show/update/"+showid+"",{ajax: 'true'}, function(data){
			window.location.reload(true);
		  })
		  	return false;
		});
		
		 $(function(){   
	    	$('.coming-soon').tipTip({defaultPosition:'top'});
	    });
		
		 $('#show-desc').expander({
			slicePoint:       950,  // default is 100
			expandPrefix:     ' ', // default is '... '
			expandText:       '[...]', // default is 'read more'
			collapseTimer:    0, // re-collapses after 5 seconds; default is 0, so no re-collapsing
			userCollapseText: '[^]'  // default is 'read less'
		  });
	
	$(document).on("click", "li a#fave", function(e){  
		e.preventDefault();
		
		var element     = this;
		var showid_hash = $(this).attr('href');
		var showid      = (showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
			
			var showname    = $(this).attr("alt");
			var feedback     = $('#feedback').text();
			if(feedback == ""){
				$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
			}
			$('#feedback').text(""+showname+" has been added to your favorites.");
			
				 $.ajax({
					  url: ""+urlpath+"/show/fave/"+showid+"",
					  success: function(msg){
							$(element).attr("id","faveselected");
							$(element).attr("title","Remove from your favourites");
							$(function(){   
								$('.remote-buttons').tipTip({defaultPosition:'top'});
							});	
							var faveno = Number($('#fave-no').text())+1;
							$('#fave-no').text(faveno);
							alert(ok);
					  }
				  });
			
		}
		
	});
	
	$(document).on("click", "li a#faveselected", function(e){  
		e.preventDefault();
	
		var element = this;
		var showid_hash = $(this).attr('href');
		var showid =(showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
			
		var showname    = $(this).attr("alt"); 
		var feedback     = $('#feedback').text();
		if(feedback == ""){
			$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
		}
		$('#feedback').text(""+showname+" has been removed from your favorites.");
		
			 $.ajax({
				  url: ""+urlpath+"/show/unfave/"+showid+"",
				  success: function(msg){
						$(element).attr("id","fave");
						$(element).attr("title","Add to your favourites");
						$(function(){   
							$('.remote-buttons').tipTip({defaultPosition:'top'});
						});	
						var faveno = Number($('#fave-no').text())-1;
						$('#fave-no').text(faveno);
						alert(ok);
				  }
			  });
		
		}
	});
	
	$(document).on("click", "a#heart-show", function(e){  
		e.preventDefault();
	
		var element = this;
		var showid_hash = $(this).attr('href');
		var showid =(showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
		var showname    = $(this).attr("alt"); 
		var feedback     = $('#feedback').text();
		if(feedback == ""){
			$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
		}
		$('#feedback').text(""+showname+" has been added to your favorites.");
		/*$('#feedback').slideDown().delay(5000).slideUp();*/
		
		
			 $.ajax({
				  url: ""+urlpath+"/show/fave/"+showid+"",
				  success: function(msg){
						$(element).attr("id","heart-show-selected");
						$(element).attr("title","Remove from your favourites");
						$(function(){   
							$('.remote-buttons').tipTip({defaultPosition:'top'});
						});	
						var faveno = Number($('#fave-no').text())+1;
						$('#fave-no').text(faveno);
						var fave_count = Number($("#fave_count_n").text())+1;
						$('#fave_count_n').text(fave_count);
						alert(ok);
				  }
			  });
		
		}
	});
	
	$(document).on("click", "a#heart-show-selected", function(e){  
		e.preventDefault();
	
		var element = this;
		var showid_hash = $(this).attr('href');
		var showid =(showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
		var showname    = $(this).attr("alt");
		var feedback     = $('#feedback').text();
		if(feedback == ""){
			$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
		} 
		$('#feedback').text(""+showname+" has been removed from your favorites.");
		
		
			 $.ajax({
				  url: ""+urlpath+"/show/unfave/"+showid+"",
				  success: function(msg){
						$(element).attr("id","heart-show");
						$(element).attr("title","Add to your favourites");
						$(function(){   
							$('.remote-buttons').tipTip({defaultPosition:'top'});
						});	
						var faveno = Number($('#fave-no').text())-1;
						$('#fave-no').text(faveno);
						var fave_count = Number($("#fave_count_n").text())-1;
						$('#fave_count_n').text(fave_count);
						alert(ok);
				  }
			  });
		
		}
	});
	
	$(document).on("click", "li a#watching", function(e){  
		e.preventDefault();
	
		var element = this;
		var showid_hash = $(this).attr('href');
		var showid =(showid_hash.replace(/\/?#/, ""));
		
		
		
		if(showid != "" && showid != urlpath ){
		var showname    = $(this).attr("alt"); 
		var feedback     = $('#feedback').text();
		if(feedback == ""){
			$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
		} 
		$('#feedback').text(""+showname+" has been added to your currently watching.");
		
			 $.ajax({
				  url: ""+urlpath+"/show/watch/"+showid+"",
				  success: function(msg){
						$(element).attr("id","watchselected");
						$(element).attr("title","Remove from currently watching");
						$(function(){   
							$('.remote-buttons').tipTip({defaultPosition:'top'});
						});	
						var watchno = Number($('#watch-no').text())+1;
						$('#watch-no').text(watchno);
						
						alert(ok);
				  }
			  });
		
		}
	});
	
	$(document).on("click", "li a#watchselected", function(e){  
		e.preventDefault();
	
		var element = this;
		var showid_hash = $(this).attr('href');
		var showid =(showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
		var showname    = $(this).attr("alt"); 
		var feedback     = $('#feedback').text();
		if(feedback == ""){
			$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
		} 
		$('#feedback').text(""+showname+" has been removed from your currently watching.");
				
		
			 $.ajax({
				  url: ""+urlpath+"/show/unwatch/"+showid+"",
				  success: function(msg){
						$(element).attr("id","watching");
						$(element).attr("title","Add to currently watching");
						$(function(){   
							$('.remote-buttons').tipTip({defaultPosition:'top'});
						});	
						var watchno = Number($('#watch-no').text())-1;
						$('#watch-no').text(watchno);
						alert(ok);
				  }
			  });
		
		}
	});
	
	$(document).on("click", "a#eye-show", function(e){  
		e.preventDefault();
	
		var element = this;
		var showid_hash = $(this).attr('href');
		var showid =(showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
		var showname    = $(this).attr("alt"); 
		var feedback     = $('#feedback').text();
		if(feedback == ""){
			$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
		} 
		$('#feedback').text(""+showname+" has been added to your currently watching.");
	
		
			 $.ajax({
				  url: ""+urlpath+"/show/watch/"+showid+"",
				  success: function(msg){
						$(element).attr("id","eye-show-selected");
						$(element).attr("title","Remove from currently watching");
						$(function(){   
							$('.remote-buttons').tipTip({defaultPosition:'top'});
						});	
						var watchno = Number($('#watch-no').text())+1;
						$('#watch-no').text(watchno);
						var watch_count = Number($("#watch_count_n").text())+1;
						$('#watch_count_n').text(watch_count);
						alert(ok);
				  }
			  });
		
		}
	});
	
	$(document).on("click", "a#eye-show-selected", function(e){  
		e.preventDefault();
	
		var element = this;
		var showid_hash = $(this).attr('href');
		var showid =(showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
		var showname    = $(this).attr("alt"); 
		var feedback     = $('#feedback').text();
		if(feedback == ""){
			$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
		} 
		$('#feedback').text(""+showname+" has been removed from your currently watching.");
		
		
			 $.ajax({
				  url: ""+urlpath+"/show/unwatch/"+showid+"",
				  success: function(msg){
						$(element).attr("id","eye-show");
						$(element).attr("title","Add to currently watching");
						$(function(){   
							$('.remote-buttons').tipTip({defaultPosition:'top'});
						});	
						var watchno = Number($('#watch-no').text())-1;
						$('#watch-no').text(watchno);
						var watch_count = Number($("#watch_count_n").text())-1;
						$('#watch_count_n').text(watch_count);
						alert(ok);
				  }
			  });
		
		}
	});
	
	$(document).on("click", "a#like", function(e){  
		e.preventDefault();
	
		var element       = this;
		var showid_hash   = $(this).attr('href');
		var showid_rating = $(this).closest('.imgwrap').find('#circle p').text();
		var new_rating    = Number(showid_rating) + Number(1);
		var ptext         = $(this).closest('.imgwrap').find('#circle p');
		var showid 		  = (showid_hash.replace(/\/?#/, ""));
		
		
		if(showid != "" && showid != urlpath ){
			ptext.hide();
			$(this).closest('.imgwrap').find('#circle p').text(new_rating);
			ptext.slideDown();	
				
			
			var showname    = $(this).attr("alt"); 
			var feedback     = $('#feedback').text();
			if(feedback == ""){
				$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
			} 
			$('#feedback').text("You have just voted up "+showname+"");
				
				$.ajax({
					url: ""+urlpath+"/show/vote/"+showid+"",
					success: function(msg){
						  $(element).attr("id","dislike");
						  $(element).attr("title","Don't like it anymore?");
						  $(function(){   
							  $('.remote-buttons').tipTip({defaultPosition:'top'});
						  });
						   alert(ok);
					}
				});
		}
	});
	
	$(document).on("click", "a#dislike", function(e){  
		e.preventDefault();
	
		var element       = this;
		var showid_hash   = $(this).attr('href');
		var showid_rating = $(this).closest('.imgwrap').find('#circle p').text();
		var new_rating    = Number(showid_rating) - Number(1);
		var ptext         = $(this).closest('.imgwrap').find('#circle p');
		var showid 		  = (showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
			ptext.hide();
			$(this).closest('.imgwrap').find('#circle p').text(new_rating);
			ptext.slideDown();	
				
			var showname    = $(this).attr("alt"); 
			var feedback     = $('#feedback').text();
			if(feedback == ""){
				$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
			}
			$('#feedback').text("You have just voted down "+showname+"");
				
				$.ajax({
					url: ""+urlpath+"/show/unvote/"+showid+"",
					success: function(msg){
						  $(element).attr("id","like");
						  $(element).attr("title","Like it?");
						  $(function(){   
							  $('.remote-buttons').tipTip({defaultPosition:'top'});
						  });
						 
						 alert(ok);
					}
				});
		}
	});
	
	$(document).on("click", "a#like-g", function(e){  
		e.preventDefault();
	
		var element       = this;
		var showid_hash   = $(this).attr('href');
		var showid_rating = $(this).closest('.imgwrap').find('#circle p').text();
		var new_rating    = Number(showid_rating) + Number(1);
		var ptext         = $(this).closest('.imgwrap').find('#circle p');
		var showid 		  = (showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
				
			
			var showname    = $(this).attr("alt"); 
			var feedback     = $('#feedback').text();
			if(feedback == ""){
				$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
			}
			$('#feedback').text("You have just voted up "+showname+"");
				
				$.ajax({
					url: ""+urlpath+"/show/vote/"+showid+"",
					success: function(msg){
						  $(element).attr("id","dislike-g");
						  $(element).attr("title","Don't like it anymore?");
						  $(function(){   
							  $('.remote-buttons').tipTip({defaultPosition:'top'});
						  });
						  var vote_count = Number($("#vote_count_n").text())+1;
						  $('#vote_count_n').text(vote_count);
						  alert(ok);
					}
				});
		}
	});
	
	$(document).on("click", "a#dislike-g", function(e){  
		e.preventDefault();
	
		var element       = this;
		var showid_hash   = $(this).attr('href');
		var showid_rating = $(this).closest('.imgwrap').find('#circle p').text();
		var new_rating    = Number(showid_rating) - Number(1);
		var ptext         = $(this).closest('.imgwrap').find('#circle p');
		var showid 		  = (showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
			
			var showname    = $(this).attr("alt"); 
			var feedback     = $('#feedback').text();
			if(feedback == ""){
				$('#feedback').fadeIn().delay(5000).fadeOut(function() { $(this).delay(2000).fadeOut; $(this).text("");  });
			}
			$('#feedback').text("You have just voted down "+showname+"");
				
				$.ajax({
					url: ""+urlpath+"/show/unvote/"+showid+"",
					success: function(msg){
						  $(element).attr("id","like-g");
						  $(element).attr("title","Like it?");
						  $(function(){   
							  $('.remote-buttons').tipTip({defaultPosition:'top'});
						  });
						 var vote_count = Number($("#vote_count_n").text())-1;
						  $('#vote_count_n').text(vote_count);
						 alert(ok);
					}
				});
		}
	});
	
	$(document).on("click", "a#like-users", function(e){  
		e.preventDefault();
	
		var element       = this;
		var showid_hash   = $(this).attr('href');
		var showid 		  = (showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
			
				$.ajax({
					url: ""+urlpath+"/show/show_options/"+showid+"/likes",
					success: function(msg){
						
						$("#myModal").html(msg);
						$('#myModal').reveal({
								 animation: 'fade',                   //fade, fadeAndPop, none
								 animationspeed: 300,                       //how fast animtions are
								 closeonbackgroundclick: true,              //if you click background will modal close?
								 dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
							});
						$('#lion-bar-box').lionbars();
						//alert(ok);
					}
				});
		}
	});
	
	$(document).on("click", "a#fave-users", function(e){  
		e.preventDefault();
	
		var element       = this;
		var showid_hash   = $(this).attr('href');
		var showid 		  = (showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
			
				$.ajax({
					url: ""+urlpath+"/show/show_options/"+showid+"/faves",
					success: function(msg){
						$("#myModal").html(msg);
						$('#myModal').reveal({
								 animation: 'fade',                   //fade, fadeAndPop, none
								 animationspeed: 300,                       //how fast animtions are
								 closeonbackgroundclick: true,              //if you click background will modal close?
								 dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
							});
						$('#lion-bar-box').lionbars();
						//alert(ok);
					}
				});
		}
	});
	
	$(document).on("click", "a#watch-users", function(e){  
		e.preventDefault();
	
		var element       = this;
		var showid_hash   = $(this).attr('href');
		var showid 		  = (showid_hash.replace(/\/?#/, ""));
		
		if(showid != "" && showid != urlpath ){
			
				$.ajax({
					url: ""+urlpath+"/show/show_options/"+showid+"/watching",
					success: function(msg){
						$("#myModal").html(msg);
						$('#myModal').reveal({
								 animation: 'fade',                   //fade, fadeAndPop, none
								 animationspeed: 300,                       //how fast animtions are
								 closeonbackgroundclick: true,              //if you click background will modal close?
								 dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
							});
						$('#lion-bar-box').lionbars();
					 //alert(ok);
					}
				});
		}
	});
	
	/*function loadDataUsers(page)
	{
		$.ajax
		({
			type: "POST",
			url: ""+urlpath+"/user/all/"+page+"",
			success: function(msg)
			{
				$("#users-page").ajaxComplete(function(event, request, settings)
				{
					$("#users-page").html(msg);
				});
			}
		});
	}
	loadDataUsers(1);
	$('.pagination-u li').live('click',function(){
		var page   = $(this).attr('page');
		loadDataUsers(page);
		
	});*/
			 
 	
	$("#tour").click(function(){
		$('#tModal').reveal();
    });
	
	$("#nav ul li a").click(function(){
		$('#nav ul li a').addClass('selected');
		$('#nav ul li a').removeClass('selected');
    });
 });
 
 /* $(function(){$('#contactable').contactable({subject: 'feedback URL:'+location.href});});*/
  
  
 





