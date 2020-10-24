<?php

namespace Model;


class ExistingUser {
    private $username = null;
    private $password = null;

	public function __construct(string $inputUsername, string $inputPassword)  {
        $this->username = $inputUsername;
        $this->password = $inputPassword;
        
        if(strlen($this->username) == 0) {
            throw new \Exception("Username is missing");
        } else if (strlen($this->password) == 0) {
            throw new \Exception("Password is missing");
        }
	}

	public function getUsername() : string {
		return $this->username;
    }
    
    public function getPassword() : string {
        return $this->password;
    }

}