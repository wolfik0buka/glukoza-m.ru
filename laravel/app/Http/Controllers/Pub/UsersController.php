<?php namespace App\Http\Controllers\Pub;


use App\Http\Controllers\Controller;
use App\Models\Order;
use App\User;
use Mail;


class UsersController extends Controller
{

    /**
     * @param int $order_id
     * @return int (user_id if created, 0 if not)
     */
    public static function createFromOrder(int $order_id): int
    {
        $user_id = 0;
        $order = Order::where('id', $order_id)->first();

        if ($order->hasEmail()) {
            $user_id = self::create($order->fio, $order->email, $order->phone);
        }

        return $user_id;
    }


    /**
     * Create new user
     *
     * @param string $name
     * @param string $email
     * @param string $phone
     * @return int
     */
    public static function create(string $name, string $email, string $phone): int
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->phone = phone_format($phone);

        $password = User::generatePassword();
        $user->pwd = bcrypt($password);
        $user->save();

        self::sendEmailOnRegister($user, $password);

        return $user->id;
    }


    public static function sendEmailOnRegister(User $user, string $passsword)
    {
        Mail::send('public.mail.reg', [
            'reg' => $user,
            'pwd' => $passsword,
        ], function ($message) use ($user) {
            $message
                ->to($user->email)
                ->subject('Регистрация на сайте glukoza-med.ru');
        });
    }

}