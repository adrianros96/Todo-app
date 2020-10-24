<?php

namespace Controller;

class Todo {

	private $view;
	private $db;

	public function __construct(\Model\TodosDb $db, \View\TodoListView $view) {
		$this->db = $db;
		$this->view = $view;
		
	}

	public function addNewTodo()  {
		if ($this->view->userAddNewTask()) {
			try {
				$taskInput = $this->view->getTodoInput();
				$post = $this->db->createTask($taskInput->getTodo());
				if ($post) {
					$this->view->todoHasBeenAdded();
				}
			} catch (\Exception $e) {
				error_log($e);
				$this->view->errorMessage($e->getMessage());
			}
		}
	}

	public function getAllTodos() {
		$allTodos = $this->db->getAllTodos();
		$this->view->showAllTodos($allTodos);
	}

	public function editTodo() {
		if ($this->view->userClickEditTodo()) {
			$id = $this->view->getTodoIdToEdit();
			$todoFromDb = $this->db->editTodo($id);
			$this->view->getTodoText($todoFromDb);
		}
	}

	public function updateTodo() {
		if ($this->view->userClickUpdateTodo()) {
			try {
				$todoInput = $this->view->getTodoInput();
				$id = $this->view->userUpdatesTodo();
				$idStringToInt = (int)$id;
				$updateSucceeded = $this->db->updateTodo($todoInput->getTodo(), $idStringToInt);
	
				if ($updateSucceeded) {
					$this->view->reloadPage();
				}
			} catch (\Exception $e) {
				error_log($e);
				$this->view->errorMessage($e->getMessage());
			}
		}
	}

	public function deleteTodo() {
		if($this->view->userClickDeleteTodo()) {
			$id = $this->view->getTodoIdToRemove();
			$result = $this->db->deleteTodo($id);
			
			if ($result) {
				$this->view->reloadPage();
			}
		}
	}
}