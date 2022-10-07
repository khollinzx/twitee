<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
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
        'twit'
    ];

    /**
     * @var string
     */
    protected $table = 'posts';

    /**
     * @var array
     */
    protected $relationship = [
        'user',
        'comments'
    ];

    /** has many relations */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** has many relations */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** get attribute Post id
     * @return int
     */
    public function getId(): int
    {
        return $this->attributes['id'];
    }

    /** get attribute Post twit
     * @return string
     */
    public function getTwit(): string
    {
        return $this->attributes['twit'];
    }

    /** get attribute Post user_id
     * @return string
     */
    public function getUserId(): string
    {
        return $this->attributes['user_id'];
    }

    /** get a post by Id
     * @param string $postId
     * @return mixed
     */
    public static function getPostById(string $postId)
    {
        return self::with((new self())->relationship)->where('id', $postId)->first();
    }

    /** this method creates a new user's post
     * @param string $twit
     * @param int $userId
     * @return Post
     */
    public static function createNewPost(string $twit, int $userId): self
    {
        $Post = new self();
        $Post->user_id = $userId;
        $Post->twit = $twit;
        $Post->save();

        return $Post;
    }

    /** this method deletes a user's post
     * @param Post $Post
     */
    public static function deletePostById(self $Post)
    {
        $Post->delete();
    }
}
