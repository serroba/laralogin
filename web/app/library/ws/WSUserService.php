<?php

namespace WS\Domain\User\Service\WSUserService;

use Illuminate\Auth\AuthServiceProvider;

/**
 * Description of WSUserService
 *
 * @author Sebastián Salazar Molina <ssalazar@orangepeople.cl>
 */
class WSUserService extends AuthServiceProvider {

    public function findUserByUserIdentifier($identificador) {
        $user = new \WS\Domain\User\Entity\User();
        $user->rut = $identificador;
        return $user;
    }

    public function findUserByUserName($username) {
        $user = new \WS\Domain\User\Entity\User();
        $user->rut = $username;
        return $user;
    }

    public function validateUserCredentials($username, $password) {
        $resultado = false;

        $parametros = array();
        $parametros['rut'] = $username;
        $parametros['password'] = hash('sha256', strtoupper($password));

        $opciones = array('login' => "isw", 'password' => "8c18f4e50e8e65a6d19e14a9c90437d67bb05e5a");

        $objClienteSOAP = new SoapClient("http://informatica.utem.cl:8011/dirdoc-auth/ws/auth?wsdl", $opciones);
        $objRespuesta = $objClienteSOAP->autenticar($parametros);
        $codigo = (int) $objRespuesta->return->codigo;
        $descripcion = (string) $objRespuesta->return->descripcion;

        if ($codigo > 0) {
            $resultado = true;
        } else {
            error_log("Servicio WEB respondió: $descripcion ($codigo)");
        }

        return $resultado;
    }

}

?>
