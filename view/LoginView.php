<?php

namespace View;

//TODO create a module for cookie-handling

class LoginView {
	public static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private $messageWasSetAndShouldNotBeRemovedDuringThisRequest = false;
	private static $usernameInput = '';
	private static $messageSessionIndex = '';
	private $message = "";

	

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response($isLoggedIn) {
		if(!$isLoggedIn) {
			if($this->byeMessageIsSet()) {
				$this->message = $this->getSavedMessage();
			}
			$this->tryToLoginWithoutPassword();
			$response = $this->generateLoginFormHTML();
		} else {
			$response = $this->generateLogoutButtonHTML();
		}
		return $response;
	}

	
	public function getExistingUser() {
		return new \model\ExistingUser($this->loginUsernameInput(), $this->loginPasswordInput());
	}
	
	public function keepUserLoggedIn() : bool {
		return isset($_POST[self::$keep]);
	}
	
	public function removeCookie() {
		setcookie(self::$cookieName, false, 1, '/');
		setcookie(self::$cookiePassword, false, 1, '/');
	}
	
	
	public function setCookieForUser() {
		$pass = bin2hex(random_bytes(20));
		setcookie(self::$cookieName, $this->loginUsernameInput(), time()+3600, '/');
		setcookie(self::$cookiePassword, $pass, time()+3600, '/');
		$_COOKIE[self::$cookieName] = $_SESSION['username'];
		$_COOKIE[self::$cookiePassword] = $pass;
	}
	
	public function cookieIsSet() : bool {
		return isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]);
	}
	
	public function cookieRememberUserWelcome() 
	{
		$this->message = "Welcome and you will be remembered";
	}
	
	public function cookieWelcomeBackMessage() {
		$this->message = "Welcome back with cookie";
	}
	
	public function getCookieName() {
		if(isset($_COOKIE[self::$cookieName])) {
			return $_COOKIE[self::$cookieName];
		}
	}
	
	public function getCookiePassword() {
		if(isset($_COOKIE[self::$cookiePassword])) {
			return $_COOKIE[self::$cookiePassword];
		}
	}
	
	public function tryToLoginWithoutPassword() {
		if($this->userPressLogin()) {
			if (strlen($this->loginPasswordInput()) == 0) {
				self::$usernameInput = $_POST[self::$name];
			}	
		}
	}
	
	public function errorMessage($error) {
		if($this->userPressLogin()) {
			$this->message = $error;
        }
	}
	
	
	public function userPressLogin() : bool {
		return isset($_POST[self::$login]);
	}
	
	public function userPressLogout() : bool {
		return isset($_POST[self::$logout]);
	}
	
	
	public function loginUsernameInput() : string {
		return $_POST[self::$name];
	}
	
	public function loginPasswordInput() : string {
		return $_POST[self::$password];
	}
	
	
	
	public function logout() {
		unset($_COOKIE[self::$cookieName]);
		unset($_COOKIE[self::$cookiePassword]);
	}
	
	public function reloadPage() {
		header('location: /');
	} 
	
	public function removeMessageIfCloseBrowser() {
		unset($_SESSION[self::$messageSessionIndex]);
	}
	
	public function byeMessageIsSet() : bool {
		return isset($_SESSION[self::$messageSessionIndex]);
	}
	
	
	public function welcomeMessage() {
		$this->message = "Welcome";
	}
	
	// From Daniel's Code-along
	public function ShowByeMessage() {
		$messageToShow =  "Bye bye!";
		
		$_SESSION[self::$messageSessionIndex] = $messageToShow;
		
		//Make sure the message survives the first request since it is removed in getSavedMessage
		$this->messageWasSetAndShouldNotBeRemovedDuringThisRequest = true;
		
	}

	public function registredNewUsername() {
		if(isset($_SESSION['newUserCreated'])) {
			self::$usernameInput = $_SESSION['newUserCreated']; 
			unset($_SESSION['newUserCreated']);
		}
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML() {
		return '
			<form  method="post" >
				<p class="messagecss" id="' . self::$messageId . '">' . $this->message .'</p>
				<input class="navLogout" type="submit" name="' . self::$logout . '" value="Logout"/>
			</form>
		';
	}

	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML() {
		$this->registredNewUsername();
		return '
		<div class="firstPageContainer">
			<div class="loginFormContainer">
				<form method="post">
					<h1>Sign in</h1>
						<p id="' . self::$messageId . '">' . $this->message . '</p>
						<label for="' . self::$name . '">Username</label>
						<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="'. self::$usernameInput .'" />
						<br>
						<label for="' . self::$password . '">Password</label>
						<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
							<label for="' . self::$keep . '">Keep me logged in: 
								<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" /> 
							</label>
							<a href="?register">Register a new user</a>
						<input type="submit" name="' . self::$login . '" value="Login" />
				</form>
			</div>
			<div class="firstPagePicture"></div>
		</div>		
		';
	}

	/**
	* gets the saved message that was stored in reloadPageAndShowBuyMessage
	* From Daniel's Code-along
	*/
	private function getSavedMessage() : string {

		if ($this->messageWasSetAndShouldNotBeRemovedDuringThisRequest) {
			return $_SESSION[self::$messageSessionIndex];
		}

		if (isset($_SESSION[self::$messageSessionIndex])) {
			$message = $_SESSION[self::$messageSessionIndex];
			unset($_SESSION[self::$messageSessionIndex]);
			return $message;
		}
		return "";
	}
}
