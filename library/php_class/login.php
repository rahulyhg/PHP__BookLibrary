<?php
  session_start();

  // encoded the session name to avoid conflict with other script using the same session data.
  $sessionLogAs = $GLOBALS["root"].'/log_as';

  function checkLogin()  {
    if ( $_SESSION[$sessionLogAs] )  {
      return $_SESSION[$sessionLogAs];
    } else {
      if ( $_POST['login'] && $_POST['password'] )  {  // if login name and password is entered, then process check login.
        if ( ( $_POST['login'] == $GLOBALS['adminLogin'] ) && ( $_POST['password'] == $GLOBALS['adminPassword'] ) )  {
          $_SESSION[$sessionLogAs] = $_POST['login'];
 	      return $_SESSION[$sessionLogAs];
        } else {
          $_SESSION[$sessionLogAs] = 0;
        }
      } else {
        $isIndexPage = strpos( $_SERVER['PHP_SELF'], 'index.php' );
        $isHelpPage = strpos( $_SERVER['PHP_SELF'], 'help_documentation.php' );
        $isAboutPage = strpos( $_SERVER['PHP_SELF'], 'about.php' );
        if ( ($isIndexPage + $isHelpPage + $isAboutPage) == 0 )  {
          header( 'Location: '.$GLOBALS['scriptDir'].'index.php' );
        }
      }
    }
  }

?>