<?php

namespace App\Http\Controllers;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function login() {
        if(is_user_logged_in()) {
            return response([
                "success" => false,
                "error_code" => "already_logged_in",
                "error_message" => ""
            ]);
        }
        
        $user_login = request()->get('user_login', '');
        $user_password = request()->get('user_password', '');
        $remember_me = request()->get('remember_me', false);

        $result = wp_signon([
            "user_login" => $user_login,
            "user_password" => $user_password,
            "remember_me" => $remember_me
        ]);

        if(is_wp_error($result)) {
            return response([
                "success" => false,
                "error_code" => $result->get_error_code(),
                "error_message" => $result->get_error_message()
            ]);
        }

        return response([
            "success" => true
        ]);
    }
}
