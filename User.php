<?php

class User
{
    public function hasIdentity()
    {
        if (isset($_COOKIE['authUser'])) {
            return $_COOKIE['authUser'];
        } else {
            return false;
        }
    }

    public function getUserName()
    {
        return $_SESSION['authUser']['name'];
    }

    public function getUserId()
    {
        return $_SESSION['authUser']['id'];
    }

    public function authUser($user)
    {
        setcookie("authUser", $user['token'].$user['id']);
        $_SESSION['authUser'] = $user;
        return $user;
    }
}
?>