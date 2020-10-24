<?php

namespace Model;

class TodoTask {
    private $todo;
    private static $minTodoStringLength = 1;
    
    public function __construct(string $taskInput) {
        $this->todo = $taskInput;

        if(strlen($this->todo) < self::$minTodoStringLength) {
			throw new TaskTooShortException("Todo length needs to be at least 1 characters.");
        }
        //TODO regex for tags
        if(!preg_match('/^[0-9 A-Z a-z][A-Z a-z 0-9]/', $this->todo)) {
            throw new InvalidUsernameException("Todo contains invalid characters.");
        }
    }

    public function getTodo() : string {
        return strip_tags($this->todo);
	}
}