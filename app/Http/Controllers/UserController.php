<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Http\Requests\UserRequest;
use App\Http\Requests\SinginRequest;
use Hash;

class UserController extends Controller
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function getLogin()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        };
        return view('login');
    }
    public function postLogin(UserRequest $request)
    {
        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember))
            return redirect()->route('dashboard');
        else {
            return back()->with('thatbai', 'Đăng nhập thất bại!');
        }
    }

    public function getSingin()
    {
        return view('singin');
    }
    public function postSingin(SinginRequest $request)
    {
        $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            return redirect()->route('dashboard');
        else {
            return back()->with('thatbai', 'Đăng ký thất bại!');
        }
    }
    //logout
    public function Logout()
    {
        Auth::Logout();
        return redirect()->route('login');
    }
}
