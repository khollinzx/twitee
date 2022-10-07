<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JsonAPIResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * @var User
     */
    protected $mainModel;

    /** setting constructor
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->mainModel = $user;
    }

    /** Get User details section
     * @return JsonResponse
     */
    public function userDetails(): JsonResponse
    {
        try {
            /** get user details and related objects by user id */
            return JsonAPIResponse::sendSuccessResponse('details', $this->mainModel::getUserById($this->getUserId()));

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }
    }

    /** Log out section
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            /** get auth user token @var  $userAuthToken */
            $userAuthToken = $this->getUserToken();

            /** proceed to deleting all passport tokens */
            $this->mainModel::logoutUser($this->getUserId());

            /** destroy access token */
            $userAuthToken->revoke();

            return JsonAPIResponse::sendSuccessResponse('LOGGED_OUT');

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }
    }
}
