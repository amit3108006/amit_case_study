<?php

namespace App\Services;

use App\Http\Resources\UserLoginResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function login($data) {
        $objUser = User::where("email", $data['email'])->first();
        if(!$objUser || !Hash::check($data['password'], $objUser->password)) {
            throw new Exception(__("message.invalidDetails"));
        }
        return new UserLoginResource($objUser);
    }

    public function getLoggedInUser() {
        return auth("sanctum")->user();
    }
}
