<?php

namespace Controller;

class Register {

	private $view;
	private $db;

	public function __construct(\Model\UsersDb $db, \View\RegisterView $view) {
		$this->db = $db;
		$this->view = $view;
		
	}

	public function registerNewUser()  {
		if ($this->view->registerattempt()) {
			try {
				$userInput = $this->view->getNewUserCredentials();
				$usernameIstaken = $this->db->checkIfUsernameIsTaken($userInput->getUsername());

				if(!$usernameIstaken) {
					$this->db->createUser($userInput->getUsername(), $userInput->getPassword());
					$this->view->showRegistrationMessage();
					$this->view->reloadPage();
				}
			} catch (\Model\InvalidUsernameException $e) {
				$this->view->errorMessage($e->getMessage());
			} catch (\Exception $e) {
				$this->view->errorMessage($e->getMessage());
			}
		}
	}
}