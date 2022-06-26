<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Services\UserService;
use App\Traits\OutputTrait;
use Exception;

class UserController extends Controller
{
    use OutputTrait;

    public function __construct(
        private UserService $userService
    ){}

    /**
     * Function will be used to login user into the panel
     *
     * @param  UserLoginRequest $request
     * @return Response
     */
    public function login(UserLoginRequest $request) {
        try {
            $objUser = $this->userService->login($request->validated());
            return $this->sendSuccess(__("message.loginSuccess"), $objUser);
        } catch (Exception $exp) {
            return $this->sendError($exp);
        }
    }
}
