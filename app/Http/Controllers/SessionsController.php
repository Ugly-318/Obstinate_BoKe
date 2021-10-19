<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    /**
     * 用户登录页面
     */
    public function create()
    {
        return view('sessions.create');
    }


    /**
     * 用户登录认证操作
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->activated) {
                session()->flash('success', '欢迎回来!');
                $fallback = route('users.show', [Auth::user()]);
                return redirect()->intended($fallback);
            } else {
                Auth::logout();
                session()->flash('warning', '你的账户未激活, 请检查你的邮箱中的注册邮件进行激活。');
                return redirect('/');
            }
        } else {
            session()->flash('danger', '很抱歉, 用户邮箱和密码不一致请重新填写');
            return redirect()->back()->withInput();
        }
    }

    /**
     * 用户退出
     */
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已退出成功!');
        return redirect('login');
    }

    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);

        // 限流 10分钟 10次
        $this->middleware('throttle:10,10', [
            'only' => ['store']
        ]);
    }

}
