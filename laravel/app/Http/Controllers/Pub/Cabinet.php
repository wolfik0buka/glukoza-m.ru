<?php namespace App\Http\Controllers\Pub;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pub\Cabinet\CabinetBonuses;
use App\Http\Controllers\Pub\Cabinet\CabinetUser;
use App\Models\RegUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Cabinet extends Controller
{

    public function profile()
    {
        if (Session::has('user')) {
            try {

                $user = CabinetUser::get();

                $bonuses = CabinetBonuses::getByUserId($user->id);

                $seo = self::seo()
                    ->setTitle('Личный кабинет')
                    ->setH1('Профиль')
                    ->all();

                return view('public.cabinet.profile', compact('user', 'bonuses', 'seo'));
            } catch (ModelNotFoundException $ex) {
                return redirect()->to('/cabinet/auth_form');
            }
        } else {
            return redirect()->to('/cabinet/auth_form');
        }

    }


    /**
     *  Форма регистрации
     */
    public function reg_form()
    {
        $seo = self::seo()
            ->setTitle('Регистрация')
            ->setH1('Регистрация')
            ->all();

        return view('public.cabinet.reg_form', compact('seo'));
    }


    /**
     *  Регистрация нового пользователя
     */
    public function reg(Request $request)
    {
        $check = RegUser::where('email', $request->email)->get();
        if ($check->count() > 0) {
            $txt = 'Пользователь с таким E-mail уже зарегистрирован в системе';
            return view('public.cabinet.reg_success', compact('txt'));
        } else {
            $reg = new RegUser;
            $reg->name = trim($request->fio);
            $reg->email = mb_strtolower(trim($request->email), 'UTF-8');
            $reg->phone = phone_format($request->phone);
            $reg->adr = $request->adr;
            $reg->pwd = md5($request->pwd1);
            $reg->save();
            $txt = 'Поздравляем, теперь вы зарегистрированный пользователь';
            Session::put('user.name', $reg->name);
            Session::put('user.id', $reg->id);
            Session::put('user.email', $reg->email);
            Session::put('user.phone', phone_format($reg->phone));
            Session::put('user.bonus_all', $reg->bonus);
            Session::put('user.bonus_use', 0);

            UsersController::sendEmailOnRegister(User::where('id', $reg->id)->get(), $request->pwd1);

            return redirect()->to('/cabinet/profile');
        }
    }


    /**
     *  Форма авторизации
     */
    public function auth_form()
    {
        $seo = self::seo()
            ->setTitle('Личный кабинет')
            ->setH1('Личный кабинет')
            ->all();

        return view('public.cabinet.auth_form', compact('seo'));
    }


    /**
     *  Авторизация пользователя
     */
    public function auth(Request $request)
    {
        $check = RegUser::where('email', mb_strtolower(trim($request->email), 'UTF-8'))
            ->where('pwd', md5($request->pwd))
            ->get();

        if ($check->count()) {
            Session::put('user.name', $check->first()->name);
            Session::put('user.id', $check->first()->id);
            Session::put('user.email', $check->first()->email);
            Session::put('user.phone', $check->first()->phone);
            Session::put('user.bonus_all', $check->first()->bonus);
            Session::put('user.bonus_use', 0);
            $txt = 'Здравствуйте, вы вошли как ' . $check->first()->name;

            return redirect()->to('/cabinet/profile');
        }

        return redirect()
            ->back()
            ->with('error', 'Указан неверный адрес email или пароль.');
    }


    /**
     *  Применение бонусов
     */
    public function bonus_use(Request $request)
    {
        $use = $request->bonus_use;
        if ($request->bonus_use > Session::get('user.bonus_all')) $use = Session::get('user.bonus_all');
        Session::put('user.bonus_use', $use);
        Session::put('user.bonus_all', Session::get('user.bonus_all') - $use);
        return redirect()->back();
    }


    /**
     * Обновление данных пользователя из личного кабинета
     */
    public function updateProfile(Request $request)
    {
        if (Session::has('user.id')) {
            try {
                $user = User::where('id', Session::get('user.id'))->firstOrFail();

                if (mb_strlen($request->name, 'utf-8') > 4) {
                    $user->name = $request->name;
                    Session::put('user.name', $request->name);
                }

                $email_validator = \Validator::make(
                    ['email' => $request->email],
                    ['email' => 'required|email']
                );

                if ($email_validator->fails()) {
                    return redirect()
                        ->back()
                        ->with('error', 'В адресе email допущены ошибки');
                } else {
                    $user->email = $request->email;
                    Session::put('user.email', $request->email);
                }
                if (mb_strlen($request->phone, 'utf-8') >= 10) {
                    $user->phone = $request->phone;
                    Session::put('user.phone', $request->phone);
                }
                if (mb_strlen($request->city, 'utf-8') > 3) {
                    $user->city = $request->city;
                }
                if (mb_strlen($request->adr, 'utf-8') > 3) {
                    $user->adr = $request->adr;
                }

                $user->save();

                return redirect()
                    ->back()
                    ->with('success', 'Изменения успешно сохранены');

            } catch (ModelNotFoundException $ex) {
                return $this->logout();
            }
        } else {
            return $this->logout();
        }
    }


    public function changePassword(Request $request)
    {
        if (Session::has('user.id')) {
            try {
                $user = User::where('id', Session::get('user.id'))
                    ->firstOrFail();

                if (md5($request->get('current_pwd')) === (string) $user->pwd) {
                    $user->pwd = md5($request->get('new_pwd'));
                    $user->save();
                    return redirect()
                        ->back()
                        ->with('success', 'Пароль успешно изменен.');
                }

                return redirect()
                    ->back()
                    ->with('error', 'Неверный текущий пароль, попробуйте еще раз.');

            } catch (ModelNotFoundException $ex) {
                return $this->logout();
            }
        } else {
            return $this->logout();
        }
    }


    /**
     *  Выход пользователя из системы
     */
    public function logout()
    {
        Session::forget('user');
        return redirect()
            ->to('cabinet/auth_form');
    }


}