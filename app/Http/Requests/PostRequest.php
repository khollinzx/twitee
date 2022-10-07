<?php

namespace App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\JsonAPIResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostRequest extends FormRequest
{

    /**
     * @var []
     */
    protected $controller;

    /**
     * @param Controller $controller
     * @param Post $post
     */
    public function __construct(Controller $controller, Post $post)
    {
        parent::__construct();

        $this->controller  = $controller;
        $this->post  = $post;
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
            case "create":
                return $this->validatePostCreation();

            case "comment":
                return $this->validateCommentCreation();

        }
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array
     */
    private function validatePostCreation(): array
    {
        return [
            'twit' => 'required|string|min:30'
        ];
    }

    /**
     * @return array
     */
    private function validateCommentCreation(): array
    {
        return [
            'comment' => 'required|string|min:30'
        ];
    }
}
