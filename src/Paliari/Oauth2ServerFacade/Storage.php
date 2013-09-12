<?php

namespace Paliari\Oauth2ServerFacade;

use OAuth2\Storage\Pdo;

class Storage extends Pdo
{

    /**
     * @param \Doctrine\DBAL\Connection$connection
     * @param array $config
     */
    public function __construct($connection, $config = array())
    {
        $this->db = $connection;

        $this->config = array_merge(array(
            'client_table' => 'oauth_clients',
            'access_token_table' => 'oauth_access_tokens',
            'refresh_token_table' => 'oauth_refresh_tokens',
            'code_table' => 'oauth_authorization_codes',
            'user_table' => 'oauth_users',
            'jwt_table' => 'oauth_jwt',
        ), $config);
    }

    public function getUser($user_id)
    {
        $sql = 'SELECT id, nome, login, senha, cpfcgc, endereco_id, tipuser_id, usuario_pai_id, nfse, dmse, aidfe, email, abilitado, ativo from usuarios';
        $sql .= ' where id=:user_id';
        $params = array('user_id' => $user_id);

        if (!$userInfo = $this->fetch($sql, $params)) {
            return false;
        }

        return $userInfo;
    }

    public function fetch($sql, $params=array())
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        if (!$res = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return false;
        }
        return $res;
    }
}
