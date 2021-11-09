<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as ContractsRegisterResponse;

class RegisterResponse implements ContractsRegisterResponse
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
