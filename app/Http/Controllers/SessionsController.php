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

        if (Auth::attempt($credentials)) {
            session()->flash('success', '欢迎回来!');
            return redirect()->route('users.show', [Auth::user()]);
        } else {
            session()->flash('danger', '很抱歉, 用户邮箱和密码不一致请重新填写');
            return redirect()->back()->withInput();
        }
    }
}
