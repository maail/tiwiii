<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Tiwiii - <?php echo $this->pagetitle; ?></title><?php if(isset($this->pagedesc)){?> 
<meta name="description" content="<?php echo $this->pagedesc; ?>" />
<meta name="keywords" content="<?php echo $this->pagetitle; ?>, <?php echo $this->pagetitle." TV Show"; ?>, <?php echo $this->pagetitle." TV Series"; ?>, <?php echo $this->pagetitle." Episode Information"; ?>,Tiwiii, TV, TV Series, TV Shows, Episodes Information, Share Favourite Tv Shows" />
<?php } else { ?>
<meta name="description" content="View your favourite television show information and share your favourites and watching habits with your friends." />
<meta name="keywords" content="Tiwiii, TV, TV Series, TV Shows, Episodes Information, Share Favourite Tv Shows" />
<?php } ?>
<meta name="author" content="Maail" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link href="<?php echo URL; ?>public/css/tiwiii.css" rel="stylesheet" type="text/css" />
<script src="<?php echo URL; ?>public/js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.liveSearch.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.tipTip.minified.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/accordian.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.expander.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.elastislide.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.reveal.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.lionbars.0.3.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/moment.min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/jquery.history.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/underscore-min.js" type="text/javascript"></script>
<script src="<?php echo URL; ?>public/js/backbone-min.js" type="text/javascript"></script>
<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
<script src="<?php echo URL; ?>public/js/tiwiii.js" type="text/javascript"></script>
<script type="text/javascript">//var _sf_startpt=(new Date()).getTime()</script>
<script type="text/javascript">

 /* var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-26490120-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();*/



</script>
<!-- start Mixpanel -->
<script type="text/javascript">
/*(function(d,c){var a,b,g,e;a=d.createElement("script");a.type="text/javascript";a.async=!0;a.src=("https:"===d.location.protocol?"https:":"http:")+'//api.mixpanel.com/site_media/js/api/mixpanel.2.js';b=d.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b);c._i=[];c.init=function(a,d,f){var b=c;"undefined"!==typeof f?b=c[f]=[]:f="mixpanel";g="disable track track_links track_forms register register_once unregister identify name_tag set_config".split(" ");for(e=0;e<
g.length;e++)(function(a){b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,0)))}})(g[e]);c._i.push([a,d,f])};window.mixpanel=c})(document,[]);
mixpanel.init("1c71898342c46f744da9e1533c127021");*/
</script>
<!-- end Mixpanel -->
</head>
<body>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=293237607401840";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<div id="tModal" class="reveal-modal" <?php if(!isset($_SESSION['tiwiii_uids8565'])){ echo "style='height:433px;'"; }  ?>>
   <a class="close-reveal-modal" style="margin:10px 0 0 -5px;">&#215;</a>
   <?php
   if(!isset($_SESSION['tiwiii_uids8565'])){
        $url = "".URL."twitter/callback";
		$url_facebook = "".URL."fb/callback";
		echo "<div style='float:left; position:absolute; margin:20px 0 0 0; margin: 393px 0 0 228px;'>";
		echo "<a href=".$url." id='sign_in' style='margin:0;'>Sign in with twitter</a>";
		echo "<a href=".$url_facebook." id='sign_in_f'>Sign in with facebook</a>";
		echo "</div>";
   }
   ?>
</div>
    