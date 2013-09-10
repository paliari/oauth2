<?php
/**
 * User: marcos
 * Date: 09/09/13
 * Time: 17:12
 */

namespace Paliari\Oauth2;

use OAuth2\GrantType\ClientCredentials;
use OAuth2\Request;
use OAuth2\Response;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\Server;
use OAuth2\Storage\Pdo;

class Oauth2Facade
{

    /**
     * @var Pdo
     */
    protected $storage;

    /**
     * @var Server
     */
    protected $server;

    /**
     *
     * @param array|\PDO $connection instance PDO or array( 'dsn' => 'mysql:dbname=test;host=localhost',
     * 'username' => 'root',
     * 'password' => '',
     * )
     * @param array $storage_config default array(
     * 'client_table' => 'oauth_clients',
     * 'access_token_table' => 'oauth_access_tokens',
     * 'refresh_token_table' => 'oauth_refresh_tokens',
     * 'code_table' => 'oauth_authorization_codes',
     * 'user_table' => 'oauth_users',
     * 'jwt_table' => 'oauth_jwt',
     * )
     *
     * @param array $server_config default array(
     * 'access_lifetime'          => 3600,
     * 'www_realm'                => 'Service',
     * 'token_param_name'         => 'access_token',
     * 'token_bearer_header_name' => 'Bearer',
     * 'enforce_state'            => true,
     * 'require_exact_redirect_uri' => true,
     * 'allow_implicit'           => false,
     * 'allow_credentials_in_request_body' => true,
     * ).
     */
    public function __construct($connection = array(), array $storage_config = array(), $server_config = array())
    {
        if (is_array($connection)) {
            $connection = array_merge(array(
                'dsn' => 'mysql:dbname=test;host=127.0.0.1',
                'username' => 'root',
                'password' => '',
            ), $connection);
        }

        $server_config = array_merge(array(
            'enforce_state' => false,
        ), $server_config);

        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        $storage = new Pdo($connection, $storage_config);

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new Server($storage, $server_config);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new AuthorizationCode($storage));

        $this->server = $server;
        $this->storage = $storage;

    }

    public static function frontController()
    {
        $path = new Path();

        $facade = new static;

        switch ($path) {
            case "authorize":
                $facade->authorize();
            break;

            case "token":
                $facade->token();
            break;

            case "resource":
                $facade->resource();
            break;
        }
    }

    public function authorize()
    {

        $request = Request::createFromGlobals();
        $response = new Response();

        // validate the authorize request
        if (!$this->server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        $client_id = $request->query("client_id");
        $client = $this->storage->getClientDetails($client_id);
        extract((array)$client);
        $user_id = 'user1';

        // display an authorization form
        if (empty($_POST)) {
            $html = Tpl::authorize($client_label);
            exit($html);
        }

        // print the authorization code if the user has authorized your client
        $is_authorized = ($_POST['authorized'] === 'Autorizar');
        $this->server->handleAuthorizeRequest($request, $response, $is_authorized, $user_id);

        if ($is_authorized) {
            // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
            $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
            exit("SUCCESS! Authorization Code: $code");
            $response->send();
        }
        $response->send();

    }

    public function token()
    {
        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        $this->server->handleTokenRequest(Request::createFromGlobals())->send();
    }

    public function resource()
    {
        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (!$this->server->verifyResourceRequest(Request::createFromGlobals())) {
            $this->server->getResponse()->send();
            die;
        }
        $token = $this->server->getAccessTokenData(Request::createFromGlobals());
        $ret = array(
            'success' => true,
            'message' => 'You accessed my APIs!',
            'token' => $token,
        );

        echo json_encode($ret);
    }

}
