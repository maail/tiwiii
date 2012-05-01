<?php
	/**
	 * Library that encapsulates thetvdb.com API for easy access to TV show information
	 *
	 * @author Ryan Doherty <ryan@ryandoherty.com>
	 * @version 1.0
	 * @copyright Ryan Doherty, 16 February, 2008
	 * @package PHP::TVDB
	 **/
	
	
	/**
	 * ADD YOUR API KEY HERE
	 */
	define('PHPTVDB_API_KEY', 'EC603F1CF6CEE844');
	
	
	//Include our files and we're done!
	require 'tvdb/TVDB.class.php';
	require 'tvdb/TV_Show.class.php';
	require 'tvdb/TV_Shows.class.php';
	require 'tvdb/TV_Episode.class.php';
?>