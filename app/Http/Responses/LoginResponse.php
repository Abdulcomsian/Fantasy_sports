<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as ContractsLoginResponse;

class LoginResponse implements ContractsLoginResponse
{
    public function toResponse($request)
    {
        if (isset($request->key) && !empty($request->key)) {
            return redirect('league/join?key=' . $request->key . '');
        } else {
            return redirect('/home');
        }
    }
}
