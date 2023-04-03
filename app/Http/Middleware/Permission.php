<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\Game;
use Illuminate\Http\Request;
use App\Helpers\ApiResponceHandler;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Permissions\CheckAccess;
use App\Exceptions\ErorrExeptionsHandler;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Permissions\UserPermissionHandler;
use App\Helpers\Permissions\RequestPermissionHandler;
use App\Http\Interfaces\Repository\UserRepositoryInterface;

class Permission
{
    use ApiResponceHandler;
    use ErorrExeptionsHandler;
    use RequestPermissionHandler;
    use UserPermissionHandler;
    use CheckAccess;

    protected $userRepository;
    protected $user_id;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->user_id = $this->userRepository->getAuthUser()->id;
    }


    public function handle(Request $request, Closure $next): Response
    {
        try {

            $user = $this->userRepository->getAuthUser();

            $data['user'] = $user->name;
            $data['rule'] = $user->rule->name;
            $data['p_request'] = $this->requestResult($request);
            $data['p_access'] = $this->userPermissions($this->user_id);


            if (!$this->AccessVerified($data)) {
                $r_model_methode = $request->route()->getAction()['as']; // return : "mpdel.metohe"

                // filter model frome "mpdel.metohe"
                $r_model = explode('.', $r_model_methode)[0];

                // filter methode frome "mpdel.metohe"

                $r_method = explode('.', $r_model_methode)[1];

                $message = "You don't have permission to access this page for : [ " . $r_method . "  the " . $r_model . " ]";

                return $this->apiResponse($data , false, $message , Response::HTTP_FORBIDDEN);
            }

            if(!$this->findByVirefied($data)){
                $message = "You are not a developer to show your games";
                return $this->apiResponse($data , false, $message , Response::HTTP_FORBIDDEN);
            }

            return $next($request);
            







        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

}
