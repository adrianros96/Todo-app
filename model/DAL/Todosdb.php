<?php

namespace Model;

class TodosDb {
    private static $rowTodo = 'todo'; 
    private static $rowID = 'id';

    public function __construct() {
        $this->dbConnection = new \Model\Database();
    }

    public function createTask($task) : bool {
        $sql = "INSERT INTO task (todo) VALUES ('$task')";

        $statement = $this->dbConnection->connect()->prepare($sql);
        $statement->execute();

        return true;
   }

   public function getAllTodos() {
        $sql = "SELECT * FROM task";

        $statement = $this->dbConnection->connect()->query($sql);
        $listItem = "";

        while ($row = $statement->fetch()) {
            $listItem .= "<tr>
                            <td>"
                                . $row[self::$rowTodo] .
                            "</td>
                            <td>
                                <a href=?edit_task=" . $row[self::$rowID] . "><span class='material-icons'>edit</span></a> 
                            </td>
                            <td>
                                <a href=?delete_task=" . $row[self::$rowID] . "><span class='material-icons'>delete_forever</span></a>
                            </td>
                        </tr>";
        }
        return $listItem;
    }

    public function editTodo($id) {
        $sql = "SELECT todo FROM task WHERE id='$id'";

        $statement = $this->dbConnection->connect()->query($sql);
        $row = $statement->fetch();
        $todo = $row[self::$rowTodo];
        return $todo;
    }

    public function updateTodo($todo, $id) : bool {
        $sql = "UPDATE task SET todo='$todo' WHERE id=$id";

        $statement = $this->dbConnection->connect()->query($sql);
        $statement->execute();
        
        return true;
    }

    public function deleteTodo($id) : bool {
        $sql = "DELETE FROM task WHERE id='$id'";

        $this->dbConnection->connect()->query($sql);

        return true;
    }
}