<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'post_id',
        'comment',
    ];

    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $relationship = [
        'user',
        'post'
    ];

    /** has many relations */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** has many relations */
    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /** get attribute Comment id
     * @return int
     */
    public function getId(): int
    {
        return $this->attributes['id'];
    }

    /** get attribute Comment comment
     * @return string
     */
    public function getComment(): string
    {
        return $this->attributes['twit'];
    }

    /** get attribute Comment user_id
     * @return string
     */
    public function getUserId(): string
    {
        return $this->attributes['user_id'];
    }

    /** get attribute Comment post_id
     * @return string
     */
    public function getPostId(): string
    {
        return $this->attributes['post_id'];
    }

    /** get a comment by Id
     * @param string $postId
     * @return mixed
     */
    public static function getCommentById(string $postId)
    {
        return self::where('id', $postId)->first();
    }

    /** this method creates a new comment
     * @param string $comment
     * @param int $post_id
     * @param int $userId
     * @return Comment
     */
    public static function createNewComment(string $comment, int $post_id, int $userId): self
    {
        $Comment = new self();
        $Comment->user_id = $userId;
        $Comment->post_id = $post_id;
        $Comment->comment = $comment;
        $Comment->save();

        return $Comment;
    }
}
