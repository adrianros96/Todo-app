<?php

namespace Model;

require_once("Exceptions.php");

class Password {
    private $password = null;
    private $repeatedPassword;
    private static $minPasswordLength = 6;

    public function __construct(string $inputPassword, string $inputRepeatedPassword) {
        $this->password = $inputPassword;
        $this->repeatedPassword = $inputRepeatedPassword;
        
        if (strlen($this->password) < self::$minPasswordLength) {
			throw new TooShortPasswordException("Password has too few characters, at least 6 characters.");
        }
        if($this->password != $this->repeatedPassword) {
            throw new PasswordDontMatchException("Passwords do not match.");
        }
    }

    public function getPassword() : string {
        return $this->password;
    }
}