<?php

namespace Model;

class UserCredentials {
    private $username;
    private $password;

    public function __construct(string $inputUsername, string $inputPassword, string $inputRepeatedPassword) {
        $this->username = new \Model\UserName($inputUsername);
        $this->password = new \Model\Password($inputPassword, $inputRepeatedPassword);
    }

    public function getUsername() {
        return $this->username->getNewUsername();
    }

    public function getPassword() {
        return $this->password->getPassword();
    }

}