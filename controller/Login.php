<?php

namespace Controller;


class Login {

	private $view;
	private $db;
	private $sessionHandler;

	public function __construct(\Model\UsersDb $db, \View\LoginView $view, \Model\SessionHandler $session) {
		$this->db = $db;
		$this->view = $view;
		$this->sessionHandler = $session;
	}

	public function loginAttempt()  {
		if ($this->view->userPressLogin()) {
			try {
				$userInput= $this->view->getExistingUser();
				$userExists = $this->db->checkUserExists($userInput->getUsername(), $userInput->getPassword());
				
				if($userExists && !$this->view->keepUserLoggedIn()) {
					$this->view->welcomeMessage();
					$this->sessionHandler->setSessionVariable($userInput->getUsername());
				} else if ($userExists  && $this->view->keepUserLoggedIn()){
					$this->sessionHandler->setSessionVariable($userInput->getUsername());
					$this->view->setCookieForUser();
					$this->view->cookieRememberUserWelcome();
				}
			} catch (\Exception $e) {
				$this->view->errorMessage($e->getMessage());
			}
		}
	}

	// TODO fix message bug when logging out with cookie
	public function cookieRemembersUser() {
		if(!$this->sessionHandler->userIsLoggedIn() && $this->view->cookieIsSet()) {
				$this->view->cookieWelcomeBackMessage();
		}
	}

	public function logout() {
		if($this->view->userPressLogout()) {
			if($this->sessionHandler->userIsLoggedIn()) {
				$this->view->logout();
				$this->sessionHandler->removeSession();
				$this->view->removeCookie();
				$this->view->ShowByeMessage();
				$this->view->reloadPage();
			} else {
				$this->view->removeCookie();
			}
		}
	}
}
