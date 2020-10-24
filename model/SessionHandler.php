<?php

namespace Model;

class SessionHandler {
    
    public function setSessionVariable($username) {
        $_SESSION['username'] = [
            'username' => $username,
            'browser' => $_SERVER['HTTP_USER_AGENT'],
        ];
    }

    public function userIsLoggedIn() {
		return isset($_SESSION['username']);
	}

    public function sessionIsNotTheSameBrowser() {
        return ($_SESSION['username']['browser'] != $_SERVER['HTTP_USER_AGENT']) ? true : false;
    }

    public function removeSession() {
        unset($_SESSION['username']);
    }
}