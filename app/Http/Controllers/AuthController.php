<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use Hash;
use Auth;
use Session;
use DB;

class AuthController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function postLogin(Request $request, $id)
    {
        if (Crypt::decrypt($id) == 'masuk.post') {
            $request->validate([
                'username'  => 'required',
                'password'  => 'required',
            ]);
            if ( $request->captcha == null) {
                return back()->with('failed','mohon isi kode captcha');
            } elseif(captcha_check($request->captcha) == false ) {
                return back()->with('failed','captcha salah');
            } else {
                    $credentials = $request->only('username', 'password');
                    if (Auth::attempt($credentials)) {
                        return redirect()->intended('dashboard')->with('success','Berhasil Masuk !');
                    }
                    return redirect()->route('login')->with('failed', 'Username atau Password Salah');
            }
        } else {
            return back()->with('failed','Anda Tidak Memiliki Akses !');
        }
    }

    public function reloadCaptcha() {
        return response()->json(['captcha' => captcha_img('math')]);
    }

    public function registration()
    {
        return view('registration');
    }

    public function postRegistration(Request $request)
    {
        $request->validate([
            'id'        => 'required',
            'full_name' => 'required',
            'username'  => 'required',
            'password'  => 'required|min:6',
        ]);
        $data = $request->all();
        $check = $this->create($data);
        return redirect("dashboard")->with('Success', 'Berhasil Login !');
    }


    public function create(array $data)
    {
        return User::create([
            'id'        => $data['id'],
            'role_id'   => '3',
            'full_name' => $data['full_name'],
            'nip'       => $data['nip'],
            'password'  => Hash::make($data['password']),
            'status_id' => '1',
        ]);
    }


    public function dashboard()
    {
        return redirect()->route('pages.dashboard')->with('success', 'Selamat Datang');
    }


    public function keluar() {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}
