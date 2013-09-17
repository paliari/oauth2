<?php
/**
 * Basico para views, podera estenter outras classe adicionando novos metodos.
 *
 * User: marcos
 * Date: 09/09/13
 * Time: 18:37
 */
namespace Paliari\Oauth2ServerFacade;

class Tpl
{

    public static $app_title = 'Oauth2 Server de Paliari';

    public static function header()
    {
        $ret = <<<S
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ISS-e Oauth2ServerFacade</title>
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
    public static function authorize($client)
    {
        $client = array_merge(array(
                'redirect_uri' => 'http://paliari.com.br',
                'client_label' => 'APP Client Paliari',
                'client_image' => '//paliari.com.br/assets/img/timbre_paliari.png',
                'client_description' => 'Desenvolvido por Paliari',
            ), (array)$client
        );
        extract($client);
        $app_title = self::$app_title;
        $content = <<<S
<div class="container">
    <div class="page-header">
        <div class="alert alert-info" style="color: #000">
            <h4>ATENÇÃO!</h4>
            <h4>Um aplicativo de terceiros gostaria de autenticar-se usando sua conta do $app_title.</h4>
            <p>Por favor, leia com atenção! Só permitir o acesso a aplicativos que você conhece e confia.</p>
        </div>
        <div class="row">
          <div class="col-md-2">
              <a class="box" href="$redirect_uri">
                <img src="$client_image" />
              </a>
          </div>
          <div class="col-md-10">
            <div class="description">
              <h3><a href="$redirect_uri">$client_label</a></h3>
              <p class="text-muted">– $client_description</p>
            </div>
          </div>
        </div>
    </div>
    <div class="page-header">
      <p>
        <strong>Saiba o que significa isso</strong>
      </p>
      <p>
        <strong>$client_label</strong> quer usar OAuth para identificá-lo com sua conta do <strong>$app_title</strong>.
      </p>
      <p>
        OAuth permite que aplicativos de terceiros use a sua identidade no $app_title, poupando-o de criar um novo nome de usuário e senha em <a href="$redirect_uri">$redirect_uri</a>.
      </p>
      <p>
        Esse aplicativo não terá accesso a sua senha do $app_title, mas terá acesso as suas informações pessoais, como: e-mail, telefone, enderço, etc.
      </p>
    </div>
    <div class="">
        <form method="post">
            <input type="submit" name="authorized" class="btn btn-success btn-lg" value="Autorizar acesso">
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" name="authorized" class="btn btn-warning btn-lg" value="Recusar">
        </form>
    </div>
</div>
S;

        return self::page($content);
    }
}