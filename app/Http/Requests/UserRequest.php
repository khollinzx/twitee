<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Services\JsonAPIResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends BaseRequest
{

    /**
     * @var []
     */
    protected $controller;

    /**
     * @param Controller $controller
     */
    public function __construct(Controller $controller)
    {
        parent::__construct();

        $this->controller  = $controller;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    public function rules(): array
    {
        /** Validate header content-type */
        if(! $this->hasHeader('Content-Type') || $this->header('Content-Type') !== 'application/json')
            throw new HttpResponseException(JsonAPIResponse::sendErrorResponse('Include Content-Type and set the value to: application/json in your header.', 204));

        switch (basename($this->url()))
        {
            case "signup":
                return $this->validateSignUp();

            case "login":
                return $this->validateLoginIn();

        }
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    private function validateSignUp(): array
    {
        return [
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i|unique:users,email',
            'password' => 'required'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    private function validateLoginIn(): array
    {
        return [
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'password' => 'required|min:8'
        ];
    }
}
