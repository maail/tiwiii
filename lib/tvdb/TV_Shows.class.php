<?php

	/**
	 * TV shows class, basic searching functionality
	 * 
	 * @package PHP::TVDB
	 * @author Ryan Doherty <ryan@ryandoherty.com>
	 */

	class TV_Shows extends TVDB {

		/**
		 * Searches for tv shows based on show name
		 * 
		 * @var string $showName the show name to search for
		 * @access public 
		 * @return array An array of TV_Show objects matching the show name
		 **/
		public static function search($showName) {
			$params = array('action' => 'search_tv_shows', 'show_name' => $showName);
			$data = self::request($params);
			
			if($data) {
				$xml = simplexml_load_string($data);
				$shows = array();
				foreach($xml->Series as $show) {
					$shows[] = new TV_Show($show);
				}
				return $shows;
			}
		}
		
		/**
		 * Searches for tv show all episodes for all seasons based on show id
		 * 
		 * @var string $showID the show id to search for
		 * @access public 
		 * @return array An array of TV_Show objects matching the show name
		 * @Dev: Maail
		 **/
		public static function search_all($showID) {
			$params = array('action' => 'get_all_info', 'id' => $showID);
			$data = self::request($params);
			
			if($data) {
				$xml = simplexml_load_string($data);
				$episodes = array();
				foreach($xml->Episode as $episode){
						$episodes[] = new TV_Episode($episode);
				}
				return $episodes;
			}
		}
		
		/**
		 * Searches for updates based on the current date
		 * 
		 * @var string $type the type of updates_day, updates_week, updates_month
		 * @access public 
		 * @return array An array of TV_Episode objects the update type
		 * @Dev: Maail
		 **/
		public static function update_episodes($type) {
			$params = array('action' => 'get_update_info_episodes', 'type' => $type);
			$data   = self::request($params);
			
			if($data) {
				$xml      = simplexml_load_string($data);
				$episodes = array();
				foreach($xml->Episode as $episode){
						$episodes[] = $episode;
				}
				return $episodes;
			}
		}
		
		/**
		 * Searches for updates based on the current date
		 * 
		 * @var string $type the type of updates_day, updates_week, updates_month
		 * @access public 
		 * @return array An array of TV_Series objects the update type
		 * @Dev: Maail
		 **/
		public static function update_series($type) {
			$params = array('action' => 'get_update_info_series', 'type' => $type);
			$data   = self::request($params);
			
			if($data) {
				$xml      = simplexml_load_string($data);
				$series   = array();
				foreach($xml->Series as $serie){
						$series[] = $serie;
				}
				return $series;
			}
		}
		
		/**
		 * Searches for episode information
		 * 
		 * @var string $epid the episode id
		 * @access public 
		 * @return array An array of TV_Episode objects the update type
		 * @Dev: Maail
		 **/
		public static function episode_info($epid) {
			$params = array('action' => 'get_episode_info', 'epid' => $epid);
			$data   = self::request($params);
			
			
			if ($data) {
				$xml = simplexml_load_string($data);
				return new TV_Episode($xml->Episode);
			} else {
				return false;
			}
			
		
		}
				
		/**
		 * Find a tv show by the id from thetvdb.com
		 *
		 * @return TV_Show|false A TV_Show object or false if not found
		 **/
		public static function findById($showId) {
			$params = array('action' => 'show_by_id', 'id' => $showId);
			$data = self::request($params);
			if ($data) {
				$xml = simplexml_load_string($data);
				$show = new TV_Show($xml->Series);
				return $show;
			} else {
				return false;
			}
		}
	}

?>