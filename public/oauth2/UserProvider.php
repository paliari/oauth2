<?php
/**
 * User: marcos
 * Date: 11/09/13
 * Time: 09:49
 */

class UserProvider implements \Paliari\Oauth2\UserProviderInterface
{
    public function verifyUser()
    {
        if (!$this->getUserId()) {
            $location = urlencode($_SERVER['REQUEST_URI']);
            header("Location: /auth/login/?location=$location");
        }
    }

    public function getUserId()
    {
        session_start();
        return @$_SESSION['usuario_id'];
    }

}