<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function twiteeAPI(): string
    {
        return "Welcome to Twitee ".env("APP_ENV")." API Version 1";
    }

    /**
     * This returns a signed-in User Id
     * @return mixed
     */
    public function getUserId()
    {
        return auth()->id();
    }

    /**
     * This returns a signed-in User Model(instance)
     * @return mixed
     */
    public function getUser()
    {
        return auth()->user();
    }

    /**
     * This returns a signed-in User Model(instance)
     * @return mixed
     */
    public function getUserToken()
    {
        return auth()->user()->token();
    }
}
