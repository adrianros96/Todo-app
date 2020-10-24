<?php

namespace Model;

require_once("Exceptions.php");

class UserName {
	private static $minNameLength = 3;
	private $username = null;

	public function __construct(string $inputUsername)  {
		$this->username = $inputUsername;
		
		if (strlen($this->username) < self::$minNameLength) {
			throw new TooShortNameException("Username has too few characters, at least 3 characters.");
		}
			if(!preg_match('/^[A-Za-z][A-Za-z0-9]{1,31}$/', $this->username)) {
				throw new InvalidUsernameException("Username contains invalid characters.");
			}
	}

	public function getNewUsername() : string {
		return $this->username;
	}

}