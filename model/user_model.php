<?php

/**
 * User_Model
 *
 * Model for Users
 * 
 * @author Maail
 */
 error_reporting(E_ALL ^ E_NOTICE); 
class User_Model extends Model
{
	
	public function __construct()
	{		
				
	}
	
	public function profile($username)
	{
		
		$query = new Model;
		$sql = " SELECT   fullname, userid as uid, twitid as tids, location, username as uname, fb_id as fids, picture as pic
				 FROM     users 
				 WHERE    username = '$username';";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
				$user_info = $row;
			}
		}
		
		$sql = " SELECT tvdb_id as suf_id
				 FROM   user_fave u
				 WHERE  userid = '$uid'";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
				$user_fave[] = $suf_id;
			}
		}
		
		$sql = " SELECT tvdb_id as suw_id
				 FROM   user_watch u
				 WHERE  userid = '$uid'";
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
				$user_watch[] = $suw_id;
			}
		}
		
		$all_info = array();
		
		$all_info[] = $user_info;
		$all_info[] = $user_fave;
		$all_info[] = $user_watch;
		
		return $all_info;
		
	}
	
	public function all($page)
	{
		
	   	$query = new Model;
		$sql = " SELECT count(DISTINCT(A.userid)) as no_count FROM
			    (SELECT   u.userid, fullname, username
				 FROM     users u LEFT JOIN userhistory uh ON u.userid = uh.userid
				 ORDER BY hisdate DESC) as A";		
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
			}
		}
	   
	    $per_page = 25; 
		$start    = ($page-1)*$per_page;
		$cur_page = $page;
		$pages    = ceil($no_count/$per_page);
	    $next     = $page + 1;
		$prev     = $page - 1;
		
	    $msg   = NULL;
		
		$sql = "SELECT DISTINCT(A.userid), twitid as tids, username as unims, fullname as funim, picture, fb_id FROM
			    (SELECT   u.userid, twitid, fullname, username, picture, fb_id
				 FROM     users u LEFT JOIN userhistory uh ON u.userid = uh.userid
				 ORDER BY hisdate DESC) as A
				 LIMIT      $start,$per_page";
		
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
				
				if(!empty($tids))
				{
					$src = "https://api.twitter.com/1/users/profile_image?user_id=".$tids."&size=mini";
				}
				else
				{
					$src = $picture;
				}
			
				$msg .= "<li><a href='".URL."user/profile/$unims' title='$funim' class='remote-buttons'><img width='24px' height='24px' src='$src' onerror='ImgError(this);' /></a></li>";
			}
		}
		
		$msg .= "<div style='clear:both;'></div>";
		$msg .= "<ul class='pagination-u'>";
			 
		if($next <= $pages)$msg .= '<li  genre="'.$genre.'" page="'.$next.'" class="next-page">Next</li>';
		if($page <= $pages) $msg .= "<li class='page-info'>$page of $pages</li>";
		if($prev > 0)$msg .= '<li  genre="'.$genre.'" page="'.$prev.'" class="prev-page">Prev</li>';
		 
	    $msg .= "</ul>";
		
		$msg .= "<script>
					$(function(){   
						$('.remote-buttons').tipTip({defaultPosition:'top'});
					});	
					$('img').error(function () {
						  $(this).unbind('error').attr('src', 'http://tiwiii.com/public/images/tiwii_dp.png');
					});
						
				</script> ";
		
		echo $msg;
		
	}
	
	public function twitterupdates($type)
	{
		$userid = $_SESSION['tiwiii_uids8565'];
		$query  = new Model;
			
		if($type=='add')$type_q='add_show';
		if($type=='tune')$type_q='tuned_in';
		if($type=='fave')$type_q='fave_show';
		if($type=='watch')$type_q='watch_show';
		if($type=='like')$type_q='like_show';		
		
		$sql = "SELECT count(*) as twitopt_chk FROM user_options WHERE $type_q = '1' AND userid = '$userid'  ";		
		if($query->query($sql))
		{
			while($row = $query->get_array())
			{
				extract($row);
			}
		}
		
		if($twitopt_chk == 0){
			return false;
		}else if($twitopt_chk == 1){
			return true;
		}
		
	}
	
	public function logout()
	{
		
	   	session_unset(); 
		
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '', time()-1000);
				setcookie($name, '', time()-1000, '/');
			}
		}
		
		session_destroy();
		
		
		$_SESSION['msg'] = "You have been signed out of Tiwiii.";
		header("location:".URL);  
		
	}
}