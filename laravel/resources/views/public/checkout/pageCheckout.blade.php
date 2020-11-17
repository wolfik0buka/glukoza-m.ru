@extends('public.app')

@section('title', $seo->title)

@section('java_box')
    <script src="https://twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>
@stop

@section('content')
    <router-view name="basketLayout"
                 :user="{{ $user_id }}"
                 :bonus_balance="{{ $bonus_balance }}">
    </router-view>
@stop
