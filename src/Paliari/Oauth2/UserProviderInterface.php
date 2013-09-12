<?php
/**
 * User: marcos
 * Date: 11/09/13
 * Time: 09:39
 */

namespace Paliari\Oauth2;


interface UserProviderInterface
{

    /**
     * @param Storage $storage
     */
    public function __construct($storage);

    /**
     * verifica se o usuario esta logado e redireciona para tela de login.
     *
     * @return void
     */
    public function verifyUser();

    /**
     * Obtem o id do usuario logado.
     *
     * @return mixed
     */
    public function getUserId();

    /**
     * Obtem usuario logado.
     *
     * @return mixed
     */
    public function getUserDetails();

}