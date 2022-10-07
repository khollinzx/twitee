<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\JsonAPIResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    /** declarations */
    protected $mainModel, $commentModel;

    /** setting constructor
     * @param Post $post
     * @param Comment $comment
     */
    public function __construct(Post $post, Comment $comment)
    {
        $this->mainModel = $post;
        $this->commentModel = $comment;
    }

    /** create a new post
     * @param PostRequest $request
     * @return JsonResponse
     */
    public function newPost(PostRequest $request): JsonResponse
    {
        try {

            $validated = $request->validated();

            $Twit = $this->mainModel::createNewPost($validated['twit'], $this->getUserId());

            return JsonAPIResponse::sendSuccessResponse('Twit created Successfully', $Twit);

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }
    }

    /** get a single post by Id
     * @param int $post_id
     * @return JsonResponse
     */
    public function getPostById(int $post_id): JsonResponse
    {
        try {

            if(!$this->mainModel::getPostById($post_id))
                return JsonAPIResponse::sendErrorResponse('Post not found');

            return JsonAPIResponse::sendSuccessResponse('Post Details', $this->mainModel::getPostById($post_id));

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }
    }

    /** delete a post by Id
     * @param int $post_id
     * @return JsonResponse
     */
    public function deletePostById(int $post_id): JsonResponse
    {
        try {
            $Post = $this->mainModel::getPostById($post_id);

            if(!$Post)
                return JsonAPIResponse::sendErrorResponse('Post not found');

            if($Post->user_id !== $this->getUserId())
                return JsonAPIResponse::sendErrorResponse('Sorry!, you don\'t have ownership in deleting this post');

            $this->mainModel::deletePostById($Post);

            return JsonAPIResponse::sendSuccessResponse('Post was successfully deleted' );

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }
    }

    /** create a comment to a post by post id
     * @param PostRequest $request
     * @param int $post_id
     * @return JsonResponse
     */
    public function createComment(PostRequest $request, int $post_id): JsonResponse
    {
        try {

            $validated = $request->validated();

            if(!$this->mainModel::findPostById($post_id))
                return JsonAPIResponse::sendErrorResponse("the selected post no long exists");

            $Comment = $this->commentModel::createNewComment($validated['comment'], $post_id, $this->getUserId());

            return JsonAPIResponse::sendSuccessResponse('Comment created Successfully', $Comment);

        } catch (\Exception $exception) {
            Log::error($exception);

            return JsonAPIResponse::sendErrorResponse("Internal server error.", JsonAPIResponse::$BAD_REQUEST);
        }
    }
}
