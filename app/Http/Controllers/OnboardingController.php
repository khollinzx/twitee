<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\OauthAccessToken;
use App\Models\User;
use App\Services\Helper;
use App\Services\JsonAPIResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OnboardingController extends Controller
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

    /** Welcome section
     * @return string
     */
    public function welcome(): string
    {
        /** welcome api */
        return $this->twiteeAPI();
    }

    /** for users sign up purpose
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function signup(UserRequest $request){
        try {

            /** validates @var  $validated */
            $validated = $request->validated();

            /** proceed to account creation */
            $this->mainModel::createNewUser($validated);

            return JsonAPIResponse::sendSuccessResponse('Congratulations, account creation was successful, kindly check you mail for a verification', [], 200 );

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }
    }

    /**
     * login access for user
     * @param UserRequest $request
     * @param string $guard
     * @return JsonResponse
     */
    public function login(UserRequest $request, string $guard = 'user'){

        try {

            /** validates @var  $validated */
            $validated = $request->validated();

            $credentials = [
                'email'=> $validated['email'],
                'password'=> $validated['password']
            ];

            if(!Auth::guard($guard)->attempt($credentials)) return JsonAPIResponse::sendErrorResponse('Invalid login credentials.');

            /**
             * Get the User Account and create access token
             */
            $Account = Helper::getUserByColumnAndValue($this->mainModel, 'email', $credentials['email']);

            /** checks if account is verified */
            if(!$Account->is_verified) return JsonAPIResponse::sendErrorResponse('Your Account is yet to be verified.');

            /** set accessToken @var $accessToken */
            $accessToken = OauthAccessToken::createAccessToken($Account, $guard);

            return JsonAPIResponse::sendSuccessResponse('Login succeeded', $accessToken);

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }

    }

    /** verify a user by verification key
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyUser(Request $request): JsonResponse
    {
        try {
            /** get verification key if not added set to null
             * @var  $verification_key
             */
            $verification_key = $request->input('verification_key')?? null;

            /** check for error */
            if(is_null($verification_key)) return JsonAPIResponse::sendErrorResponse('Verification key is missing');

            /** get account by verification_key @var $Account */
            $Account = $this->mainModel::getUserByColumnAndValue('verification_key', $verification_key);

            /** check if exists */
            if(!$Account) return JsonAPIResponse::sendErrorResponse('invalid Verification key');

            /** proceed to verifying user */
            $this->mainModel::verifyUserByVerificationKey($Account);

            return JsonAPIResponse::sendSuccessResponse('Verification successful');

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }
    }

}
