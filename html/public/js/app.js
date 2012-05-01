(function($){
  var pagetitle = document.title;
  var pathname  = window.location.pathname;
 
  Backbone.sync = function(method, model, success, error){ 
    		success();
  }
  
  var Remote = Backbone.View.extend({
	  el: $('body'),
	  
	  initialize: function(){
		_.bindAll(this, 'render', 'filterItem', 'paginateItem', 'loading', 'unloading', 'ucwords'); 
		
	/*	switch(pathname)
		{
			case "/":
				this.type = "fall";
				this.page = 1;
				this.filter = "All";
			break;
			
			case "/tv":
				this.type = "all";
				this.page = 1;
				this.filter = "";
			break;
		
		}*/
	 },
	  
	  events: {
		'click #filter-genre ul li a': 'filterItem',
		'click .pagination li': 'paginateItem',
	  },
  		
	  render: function(){	
	  	/*if(pathname == "/tv")
		{
		   this.query('#container','tv/shows/');
		}
		else
		{
			 this.query('#container','index/home/');
		}
		return this;*/
	  },
	  
	  query: function(el,location){
		$('#filter-genre ul li a').removeClass('selected');
		$("#"+this.type).addClass('selected'); 
		this.loading();
		var type_uc = this.ucwords(this.type);
		if(this.filter != ""){
			var filter_uc = this.ucwords(this.filter);
		}else{
			var filter_uc = "";
		}
		$.ajax
		({
			type: "POST",
			url:location+this.type+"/"+this.page+"/"+this.filter+"",
			success: function(msg)
			{
				if(filter_uc != "" && filter_uc != "Undefined" && filter_uc != "All"){
					document.title= pagetitle + " / " + type_uc + " / " + filter_uc ;
				}
				else
				{
					document.title= pagetitle + " / " + type_uc ;
				}
				
				$(el).html(msg).show();
				
			}
		});
		this.unloading();
		
		
		
		
		if(Remote.button == "tv")
		{
			
			RemoteControl.navigate("tv/"+this.type+"/", true);
			this.button = "tv";
		}
		else
		{
			RemoteControl.navigate(this.type, true);
			this.button = "home";
		}
		
	  },
	  
	  filterItem: function(e)
	  {
		  
		  var clickedEl = $(e.currentTarget);
		  this.type     = clickedEl.attr("id");
		  this.page     = 1;
		  this.filter   = "All";
		
			RemoteControl.navigate(this.type, true);
		  e.preventDefault(); 
	  },
	  
	  paginateItem: function(e)
	  {
		  var clickedEl = $(e.currentTarget);
		  var theclass  = clickedEl.attr("class");
		  if(theclass == "filters")
		  { 
		  	  this.page = 1;
		  }
		  else
		  {
			  this.page = clickedEl.attr("page");
		  }
		  this.type     = clickedEl.attr("type");	
		  this.filter   = clickedEl.attr("filter");		
		  
		
			this.query('#container','index/home/');
		 
		  
		   
		  e.preventDefault(); 
	  },
	  
	  ucwords: function(str) {
		  return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
			  return $1.toUpperCase();
		  });
	  },

	  loading:function()
	  {
		  $('#wrapper #loading').html("<img src='public/images/loader.gif'/>").fadeIn('fast');
		  $(".show-thumb").html('<img src="public/images/loader.gif" />').fadeIn('fast');
	  },
		  
	  unloading:function()
	  {
		  $('#wrapper #loading').fadeOut();
	  }
});
	var ContactsRouter = Backbone.Router.extend({
		initialize: function() {
			this.bind( "all", this.change )
		},
		routes: {
			":type": "urlFilter",
			"":"index",
			"tv/:type": "genreFilter",
		},
	 
		urlFilter: function (type) {
		 
			  Remote.button = "home";
			  Remote.type = type;
			  Remote.page = 1;
			  Remote.filter = "All";
			  Remote.query('#container','index/home/');
		},
		genreFilter: function (type) {
			  Remote.button = "tv";
			  Remote.type = type;
			  Remote.page = 1;
			  Remote.filter = "";
			  Remote.query('#container','http://tiwiii2.local/tv/');
		 
		},
		index: function(){
			this.navigate("fall", true);
		},
		shows: function(){
			//alert("ok");
		}
	});
   
 // var Remote = new Remote(); 
  //var RemoteControl = new ContactsRouter(); 
   
 /* RemoteControl.bind('all', function(route) {
    document.write('triggered: ' + route + '<br/>');
	});*/

  //Backbone.history.start({pushState: true});
 
	     
})(jQuery);