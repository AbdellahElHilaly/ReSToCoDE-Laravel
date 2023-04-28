<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\Auth\TokenTrait;
use App\Helpers\ApiResponceHandler;
use App\Exceptions\ErorrExeptionsHandler;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Permissions\RequestPermissionHandler;
use App\Http\Interfaces\Repository\UserRepositoryInterface;

class DeviceTrust
{
    use ApiResponceHandler;
    use TokenTrait;
    use ErorrExeptionsHandler;


    protected $userRepository;
    private $auth_token = null;
    public function __construct(UserRepositoryInterface $userRepository)
    {

        $this->userRepository = $userRepository;
        $this->auth_token = $this->userRepository->getAuthUser()->token;

    }


    public function handle(Request $request, Closure $next): Response
    {
        try {

            $result =$this->checkToken($this->auth_token);

            if ($result !== 'ok') {
                $result = explode(' ', $result)[7];
                $message = "Action Blocked : " . $result . " is not trusted";
                return $this->apiResponse(null , false, $message , 422);
            }

            return $next($request);

        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

}
