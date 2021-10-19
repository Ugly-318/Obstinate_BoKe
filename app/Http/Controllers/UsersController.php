<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
           'name' => 'required|unique:users|min:3|max:50',
           'email' => 'required|unique:users|max:255',
           'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

//        Auth::login($user);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已经发送到你的注册邮件上, 请注意查收。');
//        dd(session()->get('success'));
        return redirect('/');
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => [
                'required',
                'max:50',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);

        session()->flash('success', '个人信息已更新成功!');

        return redirect()->route('users.show', $user->id);
    }

    public function index()
    {
        $users = User::paginate(6);
        return view('users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户!');
        return back();
    }
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['create', 'show', 'store', 'index', 'confirmEmail']
        ]);

        // 只允许游客 访问登录页面
        $this->middleware('guest', [
           'only' => ['create']
        ]);

        // 限流 一个小时只能提交 10次
        $this->middleware('throttle:10,60', [
            'only' => ['store']
        ]);
    }

    /**
     * 发送邮件给注册用户
     * @param $user
     */
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
//        $from = '1194884851@qq.com';
//        $name = '任晨阳';
        $to = $user->email;
        $subject = "感谢注册 Obstinate App 应用! 请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($to, $subject ) {
            $message->to($to)->subject($subject);
        });
    }

    /**
     * 邮箱激活
     * @param $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrfail();

        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你, 激活成功!');
        return redirect()->route('users.show', [$user]);
    }
}
