@extends('public.mail.mailLayout')

@section('title', 'Ваш новый пароль')

@section('content', 'Мы изменили пароль к вашему аккаунту, как вы просили. Новый пароль - <strong>'.$password.'</strong>.')

@section('button')

        <div><!--[if mso]>
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://glukoza-med.ru/cabinet/auth_form" style="height:45px;v-text-anchor:middle;width:155px;" arcsize="15%" strokecolor="#ffffff" fillcolor="#ff6f6f">
                <w:anchorlock/>
                <center style="color:#ffffff;font-family:Helvetica, Arial, sans-serif;font-size:14px;font-weight:regular;">Личный кабинет</center>
            </v:roundrect>
            <![endif]--><a  class="button-mobile" href="https://glukoza-med.ru/cabinet/auth_form"
                style="background-color:#2d9fe1;border-radius: 5px;color: #ffffff;display: inline-block;font-family: Arial, Helvetica, sans-serif;font-size: 15px;font-weight: 600;line-height: 45px;text-align: center;text-decoration: none;width: 200px;-webkit-text-size-adjust: none;mso-hide: all;">Личный кабинет</a></div>

@stop