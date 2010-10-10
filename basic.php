<?php

// http://github.com/edwardhotchkiss/Twitter-Bot/
// http://www.edwardhotchkiss.com/blog/twitter-bot-using-oauth/
// edward@edwardhotchkiss.com/
// @edwardhotchkiss

session_start();

require_once("twitteroauth/twitteroauth.php");

class Twitter {

	public $consumer_key = "";	
	public $consumer_secret = "";
	public $access_key = "";
	public $access_secret = "";
	public $connection;
	public $user;

	function __construct() { 
	
		date_default_timezone_set("GMT");
		$this->connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_key, $this->access_secret);
		$content = $this->connection->get("account/rate_limit_status");
		echo '<p style="font:11px Verdana;">Current API hits remaining: <strong>' . $content->remaining_hits . "</strong>";
		$this->user = $this->connection->get("account/verify_credentials");

	}
	
	public function Stats($method, $response, $http_code, $parameters = '') {
	
		echo '<p style="font:11px Verdana;">' . $method. " / <strong>" . $http_code . '</strong></p>';
	
	}

	public function Tweet($tweet) {
	
		$parameters = array("status" => $tweet);
		$status = $this->connection->post("statuses/update", $parameters);
		$this->Stats("statuses/update", $status, $this->connection->http_code, $parameters);
	
	}

}

$Bot = new Twitter();

$Bot->Tweet("http://github.com/abraham/twitteroauth/ + http://github.com/edwardhotchkiss/Twitter-Bot");

?>