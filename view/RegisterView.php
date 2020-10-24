<?php

namespace View;

class RegisterView {
    private static $username = 'RegisterView::UserName';
	private static $messageId = 'RegisterView::Message';
	private static $password = 'RegisterView::Password';
	private static $passwordCheck = 'RegisterView::PasswordRepeat';
	private static $register = 'RegisterView::Register';
	private static $newUsername = '';
	private static $messageSessionIndex = '';
	private $message = "";


    public function response($isLoggedIn) {
		if(!$isLoggedIn) {
			$response = $this->generateRegistrationFormHTML();
		}
       return $response;
	}

	
	
	public function getNewUserCredentials() {
		if(strlen($this->getUsernameInput()) < 3 && strlen($this->getPasswordInput()) < 6) {
			throw new \Exception("Username has too few characters, at least 3 characters.<br>Password has too few characters, at least 6 characters.");
		} 
		return new \model\UserCredentials($this->getUsernameInput(), $this->getPasswordInput(), $this->getRepeatedPasswordInput());
	}
	
	public function getUsernameInput() : string {
		return $_POST[self::$username];
	}
	
	public function getPasswordInput() : string {
		return $_POST[self::$password];
	}
	
	public function getRepeatedPasswordInput() : string {
		return $_POST[self::$passwordCheck];
	}
	
	public function registerAttempt() : bool {
		return isset($_POST[self::$register]);
	}
	
	public function userAlreadyExistsMessage() {
		throw new \Exception("User exists, pick another username.");
	}
	
	public function errorMessage($error) {
		if($this->registerAttempt()) {
			$this->message = $error;
			self::$newUsername = strip_tags($_POST[self::$username]);
        }
	}
	
	public function showRegistrationMessage() {
		$messageToShow =  "Registered new user.";
		
		$_SESSION[self::$messageSessionIndex] = $messageToShow;
		$_SESSION['newUserCreated'] = $this->getUsernameInput();
	}
	
	public function reloadPage() {
		header('location: /');
	}

	private function generateRegistrationFormHTML() {
		return '
			<div class="regFormContainer">
			<form action="?register" method="post" enctype="multipart/formdata">
				<h1>Register new user</h1>
				<a href="/">Back to login</a>
					<p id="' . self::$messageId . '">' . $this->message .  '</p>
					<label for="' . self::$username . '" >Username :</label>
					<input type="text" size="20" name="'. self::$username .'" id="'. self::$username .'" value="' . self::$newUsername . '" />
					<br>
					<label for="' . self::$password . '">Password :</label>
					<input type="password" size="20" name="'. self::$password .'" id="' . self::$password . '" value="" />
					<br>
					<label for="' . self::$passwordCheck .'" >Repeat password :</label>
					<input type="password" size="20" name="' . self::$passwordCheck . '" id="' . self::$passwordCheck . '" value="" />
					<br>
					<input id="' . self::$register . '" type="submit" name="' . self::$register . '"  value="Register" />
					<br>
				</form>
			</div>
					';
	}
}