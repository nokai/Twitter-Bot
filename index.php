<?php

// http://github.com/edwardhotchkiss/Twitter-Bot/
// http://github.com/abraham/twitteroauth/
// http://www.edwardhotchkiss.com/blog/twitter-bot-using-oauth/
// http://www.edwardhotchkiss.com/blog/building-a-twitter-rt-bot/
// edward@edwardhotchkiss.com/
// @edwardhotchkiss

session_start();

require_once("twitteroauth/twitteroauth.php");

class Twitter {

	public $consumer_key = "";
	public $consumer_secret = "";
	public $access_key = "";
	public $access_secret = "";
	public $myUsername = "";
	public $searchTerm = "";
	public $connection;
	public $JSONTweets;
	public $user;

	function __construct() { 
	
		date_default_timezone_set("GMT");
		$this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_key, $this->access_secret);
		$this->user = $this->connection->get("account/verify_credentials");

	}
	
	// How many API calls do we have left?
	public function APICallsLeft() {
	
		$content = $this->connection->get("account/rate_limit_status");
		echo '<p style="font:11px Verdana;">Current API hits remaining: <strong>' . $content->remaining_hits . "</strong><br />";
		
	}
	
	// HTTP response code statistics for calls
	public function Stats($method, $response, $http_code, $parameters = '') {
	
		echo '<p style="font:11px Verdana;">' . $method. " / <strong>" . $http_code . '</strong></p>';
	
	}

	// Single Tweet
	public function Tweet($tweet) {
	
		$parameters = array("status" => $tweet);
		$status = $this->connection->post("statuses/update", $parameters);
	
	}
	
	// Grab JSON Feed
	public function grabFeed() {
	
		$feed = "http://search.twitter.com/search.json?q=" . $this->searchTerm . "+-from%3A" . $this->myUsername . "&rpp=100";
		$file = dirname(__FILE__)."/twitter.json";
		copy($feed, $file);
		$tweets = @file_get_contents($file);
		$this->JSONTweets = json_decode($tweets);
		
	}
	
	// RT the JSON feed
	public function RT() {
		
		$this->grabFeed();
		
		for($x = 0; $x < 100; $x++) {
			
			$str = "RT @" . $this->JSONTweets->results[$x]->from_user . ": " . $this->JSONTweets->results[$x]->text;
			echo $str . "<br />";
			$this->Tweet($str);
			
		}
		
	}
	
	function __destruct() {
	
		session_destroy();
	
	}

}

// New Instatiation of Twitter
$Bot = new Twitter();

// See how many API calls we have left
$Bot->APICallsLeft();

// RT Time :P
$Bot->RT();


?>