<?php

include_once 'Config.php';
include_once 'Yo.php';

class Locator {

	/**
	 * YO username
	 * @var string
	 */
	private $username;

	/**
	 * YO object
	 * @var Yo instance
	 */
	private $yo;

	/**
	 * PDO object
	 * @var PDO instance
	 */
	private $dbh;

	/**
	 * Constructor
	 * @param string $username
	 * @throws Exception
	 */
	public function __construct($username) {
		if($username == null) throw new Exception("Invalid request");

		$this->username = $username;

		$this->yo = new Yo(Config::YO_TOKEN);
		$this->dbh = new PDO('mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME, Config::DB_USER, Config::DB_PSWD);
	}

	/**
	 * Save location passed in database
	 * @param string $location
	 * @throws Exception
	 * @return void
	 */
	public function setLocation($location) {
		if($location == null) throw new Exception("Invalid location");

		$stmt = $this->dbh->prepare("SELECT username FROM Location WHERE username = :username");
		$stmt->bindParam(':username', $this->username);
		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		$stmt = null;

		if(empty($result)) {
			$stmt = $this->dbh->prepare("INSERT INTO Location (username, location) VALUES (:username, :location)");
			$stmt->bindParam(':username', $this->username);
			$stmt->bindParam(':location', $location);

		} else {

			$stmt = $this->dbh->prepare("UPDATE Location SET location = :location WHERE username = :username");
			$stmt->bindParam(':location', $location);
			$stmt->bindParam(':username', $this->username);

		}

		$stmt->execute();

	}

	/**
	 * Yo location saved in database to current user. If no location is stored, a Yo link is sent with instructions.
	 * @return void
	 */
	public function retrieveLocation() {

		$stmt = $this->dbh->prepare("SELECT location FROM Location WHERE username = :username");
		$stmt->bindParam(':username', $this->username);

		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if(empty($result)) {

			$this->yo->user($this->username, 'http://carlocator.jeremywagemans.me/how-to.html');

		} else {

			$this->yo->user($this->username, null, $result["location"]);

		}

	}

}

?>