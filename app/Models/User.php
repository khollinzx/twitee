<?php

namespace App\Models;

use App\Services\MailGunDispatcher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** static variable declaration */
    protected static $EMAIL = 'pizcmr@gmail.com',  $PASSWORD = 'password';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'updated_at',
    ];

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var array
     */
    protected $relationship = [
        'posts',
        'comments'
    ];

    /**
     * This is the authentication guard to be used on this Model
     * This overrides the default guard which is the user guard
     * @var string
     */
    protected static $guard = 'api';

    /** has many relations */
    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    /** has many relations */
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** get the id attribute of an instant User
     * @return int
     */
    public function getId(): int
    {
        return $this->attributes['id'];
    }

    /** get the name attribute of an instant User
     * @return string
     */
    public function getName(): string
    {
        return $this->attributes['name'];
    }

    /** get the email attribute of an instant User
     * @return string
     */
    public function getEmail(): string
    {
        return $this->attributes['email'];
    }


    /** get a user by specific column and value
     * @param string $column
     * @param string|null $value
     * @return mixed
     */
    public static function getUserByColumnAndValue(string $column, ?string $value)
    {
        return self::where($column, $value)->first();
    }


    /** get user and related object's by userId
     * @param int $id
     * @return mixed
     */
    public static function getUserById(int $id)
    {
        return self::with((new self())->relationship)->where('id', $id)->first();
    }


    /**
     * This is initializes a default user
     */
    public static function initialiseDefaultUser()
    {
        /** this section instantiates a transaction to watch over the initialise action*/
        DB::transaction(function (){

            /** checks if a email already exists
             * if not, it creates a User instance
             */
            if(!self::getUserByColumnAndValue( 'email', self::$EMAIL))
            {
                $User = new self();
                $User->name = ucwords(explode('@', self::$EMAIL)[0]);
                $User->email = strtolower(self::$EMAIL);
                $User->password = Hash::make(self::$PASSWORD);
                $User->verification_key = Uuid::uuid4();
                $User->is_verified = 1;

                $User->save();

                self::sendVerificationMail($User);
            }
        });
    }

    /** this method creates a new user from the registration endpoint
     * @param array $validated
     * @return User
     */
    public static function createNewUser(array $validated): self
    {
        $User = new self();
        $User->name = ucwords(explode('@', $validated['email'])[0]);
        $User->email = strtolower($validated['email']);
        $User->password = Hash::make($validated['password']);
        $User->verification_key = Uuid::uuid4();
        $User->save();

        return $User;
    }

    /** this method verifies a user using the verification key and
     * set is_verified to 1
     * @param User $User
     */
    public static function verifyUserByVerificationKey(self $User)
    {
        $User->update(['is_verified' => 1]);
    }

    /** this method logout a user
     * @param int $tokenId
     */
    public static function logoutUser(int $tokenId)
    {
        (new OauthAccessToken())::deleteUserAccessToken($tokenId);
    }

    /** Mail Dispatcher
     * @param User $user
     */
    public static function sendVerificationMail(self $user)
    {
        /**
         * Send Auto Check mail to The Driver Mail
         */
        $config = [
            'sender_email' => "no-reply@twitee.com",
            'sender_name' => "Twitee!",
            'recipient_email' => $user->email,
            'recipient_name' => $user->name,
            'subject' => 'Welcome, to Twitee (Verification Mail)!',
        ];

        $bodyData = [
            'name' => $user->name,
            'link' => env('APP_URL')."/api/v1/onboard/verify?verification_key={$user->verification_key}",
        ];

        /**
         * Dispatch the Email to User
         */
        (new MailGunDispatcher())->sendMail($config, 'emails.verify', $bodyData);

    }
}
