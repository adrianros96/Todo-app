<?php

#View
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once("view/RegisterView.php");
require_once('view/TodoListView.php');

# Model
require_once('model/DAL/Database.php');
require_once('model/DAL/Todosdb.php');
require_once('model/DAL/Usersdb.php');
require_once('model/SessionHandler.php');
require_once('model/UserCredentials.php');
require_once('model/UserName.php');
require_once('model/TodoTask.php');
require_once('model/Password.php');
require_once('model/ExistingUser.php');

#Controller
require_once('controller/Register.php');
require_once('controller/Login.php');
require_once('controller/Todo.php');

class Application {
	private $todosdb;
	private $usersdb;
	private $sessionHandler;

	private $layoutView; 
	private $registerView;
	private $loginView;
	private $todoListView;
	private $daytimeview;

	private $todoController;
	private $regController;
	private $logController;

	public function __construct() {
		$this->todosdb = new \Model\TodosDb();
		$this->usersdb = new \Model\UsersDb();
		$this->sessionHandler = new \Model\SessionHandler();

		$this->registerView = new \View\RegisterView();
		$this->layoutView = new \View\LayoutView();
		$this->loginView = new \View\LoginView();
		$this->todoListView = new \View\TodoListView();
		$this->daytimeView = new \View\DateTimeView();

		$this->regController = new \Controller\Register($this->usersdb, $this->registerView);
		$this->logController = new \Controller\Login($this->usersdb, $this->loginView, $this->sessionHandler);
		$this->todoController =	new \Controller\Todo($this->todosdb, $this->todoListView);
	}

	public function run() {
		$this->changeState();
		$this->generateOutput();
	}

	private function changeState() {
		$this->todoController->addNewTodo();
		$this->todoController->getAllTodos();
		$this->todoController->deleteTodo();
		$this->todoController->editTodo();
		$this->todoController->updateTodo();
		
		$this->regController->registerNewUser();

		
			if ($this->loginView->cookieIsSet() || $this->sessionHandler->userIsLoggedIn()) {
				$this->logController->cookieRemembersUser();
				$this->logController->logout();
			} else {
				$this->logController->loginAttempt();
			}
	}

	private function isLoggedin() : bool {
		return true;
	}
	
	// TODO 
	// break out the authentication to a seperate module and also for the todo-application
	private function generateOutput() {
        if($this->layoutView->userSelectsRegisterForm()){
            $this->layoutView->render(!$this->isLoggedin(), $this->registerView, $this->daytimeView, !$this->todoListView->generateTodoListHTML());
        } else if ($this->loginView->getCookieName() || $this->sessionHandler->userIsLoggedIn()){
			if($this->sessionHandler->userIsLoggedIn()) {
				// ↓ check Session = same browser ↓ 
				if($this->sessionHandler->sessionIsNotTheSameBrowser()) {
					$this->layoutView->render(!$this->isLoggedin(), $this->loginView, $this->daytimeView, !$this->todoListView->generateTodoListHTML());
				} else {
					$this->layoutView->render($this->isLoggedin(), $this->loginView, $this->daytimeView, $this->todoListView->generateTodoListHTML());
				}
			} else if($this->sessionHandler->userIsLoggedIn() && $this->loginView->userPressLogout() || $this->loginView->cookieIsSet() && $this->loginView->userPressLogout()){
				$this->layoutView->render(!$this->isLoggedin(), $this->loginView, $this->daytimeView, !$this->todoListView->generateTodoListHTML());
			} else {
				$this->layoutView->render($this->isLoggedin(), $this->loginView, $this->daytimeView, $this->todoListView->generateTodoListHTML());
			}
		 } else {
			$this->layoutView->render(!$this->isLoggedin(), $this->loginView, $this->daytimeView, !$this->todoListView->generateTodoListHTML());
		}
	}
}