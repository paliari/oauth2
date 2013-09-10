<?php
/**
 * Basico para views, podera estenter outras classe adicionando novos metodos.
 *
 * User: marcos
 * Date: 09/09/13
 * Time: 18:37
 */
namespace Paliari\Oauth2;

class Tpl
{

    protected $app_title = 'Oauth2 Server de Paliari';

    public static function header()
    {
        $ret = <<<S
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ISS-e Oauth2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/3.2.1/css/font-awesome.min.css" rel="stylesheet" media="screen">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.2/html5shiv.js"></script>
      <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
S;

        return $ret;
    }

    public static function foot()
    {
        $ret = <<<S
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.0.0/js/bootstrap.min.js"></script>
  </body>
</html>
S;

        return $ret;

    }

    public static function page($content)
    {
        $ret = self::header();
        $ret .= $content;
        $ret .= self::foot();

        return $ret;
    }

    /**
     * @param string $client_label
     * @return string
     */
    public static function authorize($client_label)
    {
        $app_title = self::$app_title;
        $content = <<<S
<div class="container">
    <div class="page-header">
        <div class="alert alert-warning">
            <b>ATENÇÃO!</b>
            <p>A aplicação <b>"$client_label"</b> quer autenticar-se usando suas credenciais do $app_title.</p>
        </div>
    </div>
    <div class="panel panel-info" >
        <div class="panel-heading">
            <h3 class="panel-title">Autorizar o acesso a esse app?</h3>
        </div>
        <div class="panel-body">
            <form method="post">
                <input type="submit" name="authorized" class="btn btn-primary btn-lg" value="Autorizar">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="submit" name="authorized" class="btn btn-warning btn-lg" value="Recusar">
            </form>
        </div>
    </div>
</div>
S;

        return self::page($content);
    }
}