@extends('layouts.default')

@section('title')
@section('content')
    <div class="jumbotron">
      <h1> Hello Laravel</h1>

      <p class="lead">
        欢迎来到 <a href="#">Laravel 框架 开发应用</a>
      </p>

      <p class="lead">
        一切,将从这里开始。
      </p>

      <p>
        <a class="btn btn-lg btn-success" href="{{ route('users.create') }}" role="button">现在注册</a>
      </p>
    </div>
@stop
