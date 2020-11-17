<?php namespace App\Http\Controllers\Pub;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Mail;
use App\Services\SmsSender;

class RestorePassword extends Controller
{

    protected $password_new;
    protected $user;


	public function index()
	{
		return view('public.cabinet.cabinetRestorePassword');
	}


	public function resetPassword(Request $request)
	{
	    $this->validateEmptyEmail($request);
        $this->user = $this->getUserByEmail($request->get('email'));

	    if ($this->user instanceof User) {
            $this->password_new = $this->generatePassword();
            $this->setNewPasswordToUser();
            $this->sendNewPasswordToEmail();
            $this->sendNewPasswordToPhone();
            return $this->success();
        }

        return redirect()
            ->back()
            ->with('error', 'Пользователь c таким адресом email не найден');
	}


    protected function validateEmptyEmail($request)
    {
        if (!$request->has('email') || mb_strlen($request->get('email'), 'utf-8') < 7) {
            return redirect()
                ->back()
                ->with('error', 'Введите корректный email');
        }
        return false;
	}


    protected function getUserByEmail($email)
	{
        try {
            return User::where('email', mb_strtolower(trim($email), 'UTF-8'))
                ->firstOrFail();
	    } catch (ModelNotFoundException $ex) {
            return false;
        }
	}


    /**
     * Генерируем новый пароль
     *
     * @return int
     * @throws \Exception
     */
    protected function generatePassword()
	{
		return random_int(100000, 999999);
	}


    /**
     * Сохраняем новый пароль для аккаунта пользователя
     */
    protected function setNewPasswordToUser()
    {
        $this->user->pwd = md5($this->password_new);
        $this->user->last_reset_pwd_at = Carbon::now()->toDateTimeString();
        $this->user->save();
    }


    /**
     * Отправляем пользователю новый пароль по email
     */
    public function sendNewPasswordToEmail()
    {
        try {
            Mail::send('public.mail.mailRestorePassword', [
                'password' => $this->password_new
            ], function ($message) {
                $message
                    ->to($this->user->email)
                    ->subject('Восстановление пароля в личный кабинет');
            });
        } catch (\Exception $ex) {
            //
        }
    }


    /**
     * Отправляем пользователю новый пароль по sms
     */
    public function sendNewPasswordToPhone()
    {
        try {
            if ($this->user->phone) {
                (new SmsSender)
                    ->setPhone($this->user->phone)
                    ->setMessage("Пароль в личный кабинет: {$this->password_new}. Магазин Глюкоза")
                    ->send();
            }
        } catch (\Exception $ex) {
            //
        }
    }
    

    public function success()
    {
        return view('public.cabinet.cabinetRestorePasswordSuccess');
    }

    
}