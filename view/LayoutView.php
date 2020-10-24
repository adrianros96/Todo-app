<?php

namespace View;


class LayoutView {
  
  public function render($isLoggedIn, $viewSelection, \View\DateTimeView $dtv, $todoView) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Todo</title>
          <link rel="stylesheet" href="style.css">
          <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet"> 
        </head>
        <body>
          <div class="nav-bar">
              <h1 class="cool">ToodleDo</h1>
          </div>
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          <div class="container">
              ' . $viewSelection->response($isLoggedIn) . '
              ' . $todoView . '
          </div>
          <div class="footeri">
            ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }

  public function userSelectsRegisterForm() : bool{
      return isset($_GET['register']);
  }
  
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2 class="loggedIn">Logged in</h2>';
    }
    else {
      return '<h2 class="loggedOut">Not logged in</h2>';
    }
  }
}
