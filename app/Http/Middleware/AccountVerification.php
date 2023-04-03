<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use App\Helpers\ApiResponceHandler;
use App\Exceptions\ErorrExeptionsHandler;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Permissions\RequestPermissionHandler;
use App\Http\Interfaces\Repository\UserRepositoryInterface;

class AccountVerification
{
    use ApiResponceHandler;
    use ErorrExeptionsHandler;


    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function handle(Request $request, Closure $next): Response
    {
        try {

            $verified = $this->userRepository->accountVerified();

            if (!$verified) {
                $message = "You must verify your account first";
                return $this->apiResponse(null , false, $message , Response::HTTP_FORBIDDEN);
            }

            return $next($request);

        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

}
