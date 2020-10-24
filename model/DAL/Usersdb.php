<?php

namespace Model;

class UsersDb {
    public function __construct() {
        $this->dbConnection = new \Model\Database();
    }

    public function checkUserExists($username, $password){
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $statement = $this->dbConnection->connect()->query($sql);
    
        $SuccessfulLogin = false;
        $row = $statement->fetch();

        $correctPassword = password_verify($password, $row['pass'] ?? 'default value');

        $usernameFromDB = $row['username'] ?? 'default value';
        $passWordFromDB = $row['pass'] ?? 'default value';
        
        if($usernameFromDB == $username && $passWordFromDB == $correctPassword) {
            $SuccessfulLogin = true;
        } else {
            throw new \Exception("Wrong name or password");
        }
    return $SuccessfulLogin;
   }

   public function checkIfUsernameIsTaken($username) : bool {
        $sql = "SELECT * FROM user WHERE username = '$username'";
        $statement = $this->dbConnection->connect()->query($sql);

        $row = $statement->fetch();
        $usernameExists = false;

        if($row['username'] == $username) {
            $usernameExists = true;
            throw new \Exception("User exists, pick another username.");
        } 
        return $usernameExists;
   }

   public function createUser($username, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO User (username, pass) VALUES ('$username', '$hashedPassword')";

        $statement = $this->dbConnection->connect()->prepare($sql);
        $statement->execute();
   }
}