<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function authorize($userId, $modelUserId)
    {
        if($userId != $modelUserId) {
            return abort(403, 'unauthorized');
        }
    }
}
