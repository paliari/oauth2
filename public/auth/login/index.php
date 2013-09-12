<?php
require_once __DIR__ . '/../../oauth2/boot.php';

session_start();
if (!empty($_POST) && $_POST['login']=='marcos' && $_POST['password']=='123') {
    $sql = "select id, senha from usuarios where login = :login";
    $user = $storage->fetch($sql, array('login' => $_POST['login']));
    if ($user && $user['senha']===$_POST['password']) {
        $_SESSION['usuario_id'] = $user['id'];
        $location = $_REQUEST['location'];
        header("Location: $location");
        exit;
    }
}
session_destroy();
$content = <<<S
<div class="container">
    <div class="page-header">
      <h3>Autenticação </h3>
    </div>
    <form class="form-horizontal" role="form" method="post">
        <div class="form-group">
            <label for="login" class="col-lg-2 control-label">Login</label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="login" name="login" placeholder="Login">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-lg-2 control-label">Password</label>
            <div class="col-lg-10">
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-primary">Sign in</button>
            </div>
        </div>
    </form>
</div>
S;

echo \Paliari\Oauth2ServerFacade\Tpl::page($content);

?>
