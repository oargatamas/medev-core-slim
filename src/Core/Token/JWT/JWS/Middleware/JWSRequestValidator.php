<?php
/**
 * Created by PhpStorm.
 * User: Oarga-Tamas
 * Date: 2018. 08. 09.
 * Time: 9:50
 */

namespace MedevSuite\Application\Auth\OAuth\Token\JWT\Middleware;

use Exception;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token\Parser;
use MedevSlim\Core\APIService\Exceptions\UnauthorizedException;
use MedevSlim\Core\Token\JWT\JWS\JWSConfiguration;
use MedevSlim\Core\Token\JWT\JWS\JWSValidator;
use MedevSuite\Application\Auth\OAuth\Token\JWT\JWS\JWS;
use MedevSuite\Application\Auth\OAuth\Token\JWT\JWS\JWSRepository;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;


class JWSRequestValidator
{
    private $jwsRepo;

    public function __construct(JWSRepository $jwsRepository)
    {
        $this->jwsRepo = $jwsRepository;
    }


    public function __invoke(Request $request, Response $response, callable $next)
    {
        try {
            if (!$request->hasHeader("Authorization")) {
                throw new Exception("Authorization header missing");
            }

            $tokenString = substr($request->getHeader("Authorization"), strlen("Bearer "));


            $jws = $this->jwsRepo->validateToken($tokenString);

            $request->withAttribute("token", $jws);
            $request->withAttribute("scopes", $jws->getClaim("scopes"));

            $response = $next($request, $response);
            return $response;


        } catch (\Exception $e) {
            //Todo log error message before printing out the response with 401
            throw new UnauthorizedException();
        }
    }
}