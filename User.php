<?php

class User
{
    static public function hasIdentity()
    {
        if (isset($_SESSION['authUser'])) {
            return true;
        } else {
            return false;
        }
    }
}
?>