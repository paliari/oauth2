<?php

namespace Paliari\Oauth2ServerFacade;

class Path
{
    protected $server;

    public function __construct($server = array())
    {
        // SCRIPT_NAME is a file path on consoled
        if (PHP_SAPI == 'cli') {
            unset($_SERVER['SCRIPT_NAME']);
        }

        $default = array(
            'REQUEST_URI' => '/',
            'SCRIPT_NAME' => '/index.php'
        );

        $this->server = array_merge($default, $_SERVER, $server);
    }

    public function getPath()
    {
        $urlParts = parse_url($this->server['REQUEST_URI']);
        $path = $urlParts['path'];

        $dirname = pathinfo($this->server['SCRIPT_NAME'], PATHINFO_DIRNAME);
        $path = substr($path, strlen($dirname));
        $path = trim($path, '/');

        return $path;
    }

    public function __toString()
    {
        return (string) $this->getPath();
    }
}
