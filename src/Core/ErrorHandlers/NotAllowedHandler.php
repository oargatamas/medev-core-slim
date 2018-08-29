<?php
/**
 * Created by PhpStorm.
 * User: Oarga-Tamas
 * Date: 2018. 08. 09.
 * Time: 9:31
 */

namespace MedevSlim\Core\ErrorHandlers;


use Slim\Http\Request;
use Slim\Http\Response;

class NotAllowedHandler
{
    public function __invoke(Request $request,Response $response, $methods) {
        return $response
            ->withStatus(405)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode("Method not allowed"));
    }
}