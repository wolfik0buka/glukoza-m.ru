<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

	use Authenticatable, CanResetPassword;

	protected $table = 'users';

	protected $fillable = [
	    'name',
        'email'
    ];

	protected $guarded = [
	    'id'
    ];

	protected $hidden = ['password', 'remember_token'];
    
    
    public function orders()
    {
        return $this
            ->hasMany('App\Models\Order', 'user_id', 'id');
    }


    /**
     * Генерируем новый пароль
     */
    public static function generatePassword(): int
    {
        return random_int(100000, 999999);
    }


    /**
     * Поиск юзера по телефону
     * Если нашли - возвращаем id юзера, если нет - 0
     * @param $phone
     * @return int
     */
    public static function findByPhone($phone): int
    {
        if (!empty($email)) {
            try {
                $user = User::where('phone', $phone)->firstOrFail();
                return $user->id;
            } catch (ModelNotFoundException $ex) {
                //
            }
        }
        return 0;
    }


    /**
     * Поиск юзера по email адресу
     * Если нашли - возвращаем id юзера, если нет - 0
     * @param $email
     * @return int
     */
    public static function findByEmail($email): int
    {
        if (!empty($email)) {
            try {
                $user = User::where('email', $email)->firstOrFail();
                return $user->id;
            } catch (ModelNotFoundException $ex) {
                //
            }
        }
        return 0;
    }

}