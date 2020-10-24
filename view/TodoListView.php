<?php

namespace View;


class TodoListView {
    private static $task = 'TodoListView::Task';
    private static $addTodo = 'TodoListView::AddTodo';
    private static $messageId = 'TodoListView::Message';
    private static $update = "TodoListView::Update";
    private static $todoId = "TodoListView:TodoID";
    private static $editTodo = 'edit_task';
    private static $deleteTodo = 'delete_task';
    private $message = "";
    private $todoListItem;
    private static $todoEdit= '';


	public function generateTodoListHTML() {
    return '
    <div class="todoContainer">
      <div class="formDiv">
         <form class="todoForm" method="post">
         <h2 class="todoHeader2">Add todo</h2>
          <input type="hidden" name="' .self::$todoId . '" value="' . $this->getTodoIdToEdit() . '" />
            <p id="' . self::$messageId . '">' . $this->message .  '</p>
            <input type="text" id="' . self::$task . '" name="' . self::$task . '" value="'. self::$todoEdit .'" placeholder="Enter todo here"/>
            ' . $this->editOrAddButton() . '
          </form>
        </div>
        <div class="tableDiv">
          <table>
            <thead>
              <tr>
                <th class="todoHeader">Tasks</th>
                <th style="width: 60px;">Edit</th>
                <th style="width: 60px;">Remove</th>
              </tr>
            </thead>
          <tbody>
          ' . $this->todoListItem . '  
          </tbody>
         </table>
        </div>
    </div>    
                    ';
    }
    
    public function showAllTodos($todos) {
      $this->todoListItem = $todos;
    }

    public function userAddNewTask() : bool {
      return isset($_POST[self::$addTodo]);
    }


    public function userClickUpdateTodo() {
      return isset($_POST[self::$update]);
    }

    public function userUpdatesTodo() {
      return  $_POST[self::$todoId];
    }

    public function getTodoInput() {
		  return new \Model\TodoTask($this->getTodo());
    }
    
    public function getTodo() : string {
	  	return $_POST[self::$task];
    }
    
    public function errorMessage($error) {
		  if($this->userAddNewTask() || $this->userClickUpdateTodo()) {
			    $this->message = $error;
        }
    }

    public function todoHasBeenAdded() {
      $this->message = "Todo added";
    }

    public function editOrAddButton() {
      $buttonValue = "";
      if($this->userClickEditTodo()) {
        $buttonValue = '<input type="submit" name="' . self::$update . '" value="Update" />';
      } else {
        $buttonValue = '<input type="submit" name="' . self::$addTodo . '" value="Add todo" />';
      }

      return $buttonValue;
    }

    public function getTodoIdToEdit() {
      return $_GET[self::$editTodo] ?? null;
    }
    
    public function userClickEditTodo() {
      return isset($_GET[self::$editTodo]);
    }

    public function getTodoText($todo) {
      self::$todoEdit = $todo;
    }


    public function getTodoIdToRemove() {
      return $_GET[self::$deleteTodo];
    }
  
    public function userClickDeleteTodo() {
      return isset($_GET[self::$deleteTodo]);
    }

    public function reloadPage() {
      header('location: /');
    } 
}
