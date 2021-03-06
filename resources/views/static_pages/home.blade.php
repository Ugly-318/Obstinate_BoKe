@extends('layouts.default')

@section('content')

  @if(Auth::check())
    <div class="row">
      <div class="col-md-8">
        <section class="status_form">
          @include('statuses._status_form')
        </section>
        <section>
          <h4>微博列表</h4>
          <hr>
          @include('shared._feed')
        </section>
      </div>
      <aside class="col-md-4">
        <section class="user_info">
          @include('shared._user_info', ['user' => Auth::user()])
        </section>
        <section class="stats mt-2">
          @include('shared._stats', ['user' => Auth::user()])
        </section>
      </aside>
    </div>
  @else
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
  @endif
@stop
