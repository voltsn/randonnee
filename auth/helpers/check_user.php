<?php
function check_user() {
    if (!isset($_SESSION['user'])){
        return FALSE;
    }

    return $_SESSION['user']['username'] && $_SESSION['user']['password'];
}


function logout() {
    // Free all session variables
    session_unset();
    
    // Destroy all the data registered to the current session
    return session_destroy();
}
?>