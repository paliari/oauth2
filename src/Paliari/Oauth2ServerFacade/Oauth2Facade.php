<?php
/**
 * User: marcos
 * Date: 09/09/13
 * Time: 17:12
 */

namespace Paliari\Oauth2ServerFacade;

use Analog\Analog;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Request;
use OAuth2\Response;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\Server;
use OAuth2\Storage\Pdo;

class Oauth2Facade
{

    /**
     * @var Oauth2Facade
     */
    protected static $_instance;

    /**
     * @var Pdo
     */
    protected $storage;

    /**
     * @var Server
     */
    protected $server;

    /**
     * @var UserProviderInterface
     */
    protected $userProvider;

    /**
     *
     * @param Doctrine|\PDO $storage
     *
     * @param array $config default array(
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
    public function __construct($storage, $config=array())
    {
        $this->storage = $storage;

        $config = array_merge(array(
            'enforce_state' => false,
        ), $config);

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new Server($storage, $config);

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new AuthorizationCode($storage));

        $this->server = $server;

    }

    /**
     * @param UserProviderInterface $userProvider
     */
    public function setUserProvider(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }
    /**
     * @return UserProviderInterface
     */
    public function getUserProvider()
    {
        return $this->userProvider;
    }

    /**
     * @param UserProviderInterface $userProvider
     * @param $connection
     */
    public function frontController()
    {
        $path = new Path();

        switch ($path) {
            case "authorize":
                $this->authorize();
            break;

            case "token":
                $this->token();
            break;

            case "resource":
                $this->resource();
            break;

            case "refresh":
                $this->refresh();
            break;
        }
    }

    public function authorize()
    {
        $this->getUserProvider()->verifyUser();

        $request = Request::createFromGlobals();
        $response = new Response();

        // validate the authorize request
        if (!$this->server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        $client_id = $request->query("client_id");
        $client = $this->storage->getClientDetails($client_id);
        $user_id = $this->getUserProvider()->getUserId();

        // display an authorization form
        if (empty($_POST)) {
            $html = Tpl::authorize($client);
            exit($html);
        }

        // print the authorization code if the user has authorized your client
        $is_authorized = ($_POST['authorized'] === 'yes');
        $this->server->handleAuthorizeRequest($request, $response, $is_authorized, $user_id);
        if ($is_authorized) {
            // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
            $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
            $response->send();
            //exit("SUCCESS! Authorization Code: $code");
        }
        $response->send();

    }

    public function token()
    {
        $request = Request::createFromGlobals();
        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        $tr = $this->server->handleTokenRequest($request);
        $tr->send();
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

    public function refresh()
    {
        $request = Request::createFromGlobals();
        $response = new Response();

        $refresh = new RefreshToken($this->storage);

        // validate the authorize request
        if (!$refresh->validateRequest($request, $response)) {
            $response->send();
            die;
        }

        $accessToken = new \OAuth2\ResponseType\AccessToken($this->storage);
        $token = $refresh->createAccessToken($accessToken, $refresh->getClientId(), $refresh->getUserId(), $refresh->getScope());
        header('Content-Type: application/json');
        echo json_encode($token);
    }
}
