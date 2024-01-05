<?php

namespace App\Http\Controllers;

use App\Model\submissionModel;
use App\Models\Pegawai;
use App\Models\Status;
use App\Models\UnitKerja;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show()
    {
        $user = User::get();
        return view('pages.user.show', compact('user'));
    }

    public function selectBulan(Request $request)
    {
        $response = array();

        $response[] = array(
            "id"    => "",
            "text"  => "SELURUH BULAN"
        );

        for ($i = 1; $i <= 12; $i++) {
            $response[] = array(
                "id"    =>  $i,
                "text"  =>  strtoupper(Carbon::create(null, $i, 1)->locale('id')->isoFormat('MMMM'))
            );
        }

        return response()->json($response);
    }

    public function selectStatus(Request $request)
    {
        $status = Status::whereIn('id_status',[101,102,103,105,106,100])->orderBy('id_status','ASC')->get();
        $response = array();

        $response[] = array(
            "id"    => "",
            "text"  => "SELURUH STATUS"
        );

        foreach($status as $data){
            $response[] = array(
                "id"    =>  $data->id_status,
                "text"  =>  strtoupper($data->nama_status)
            );
        }

        return response()->json($response);
    }

    public function create()
    {
        $status = Status::where('kategori_status','user')->get();

        return view('pages.user.create', compact('status'));
    }

    public function edit($id)
    {
        $user   = User::where('id',$id)->first();
        $status = Status::where('kategori_status','user')->get();

        return view('pages.user.edit', compact('user','status'));
    }

    public function store(Request $request)
    {
        $nip = Pegawai::where('id_pegawai',$request->pegawai_id)->first();

        $user   = User::count();
        $format = str_pad($user + 1, 3, 0, STR_PAD_LEFT);
        $idUser = Carbon::now()->isoFormat('DDMMYY').$format;

        $tambah = new User();
        $tambah->id             = $idUser;
        $tambah->role_id        = $request->role_id;
        $tambah->pegawai_id     = $request->pegawai_id;
        $tambah->username       = $nip->nip;
        $tambah->password       = Hash::make($request->password);
        $tambah->password_teks  = $request->password;
        $tambah->status_id      = $request->status_id;
        $tambah->save();

        return redirect()->route('user.show')->with('success', 'Berhasil Menambah Baru');
    }

    public function update(Request $request, $id)
    {
        User::where('id',$id)->update([
            'role_id'        => $request->role_id,
            'password'       => Hash::make($request->password),
            'password_teks'  => $request->password,
            'status_id'      => $request->status_id
        ]);

        if ($id == Auth::user()->id) {
            return redirect()->route('masuk')->with('success', 'Berhasil Memperbaharui Informasi');
        } else {
            return redirect()->route('user.show')->with('success', 'Berhasil Memperbaharui Informasi');
        }
    }

    public function delete($id)
    {

    }

    public function showProfile($id)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $google2fa   = app('pragmarx.google2fa');
        $secretkey   = $google2fa->generateSecretKey();
        $qrCodeImage = $google2fa->getQRCodeInline(
            $registration_data = 'Siporsat Kemenkes',
            $registration_data = Auth::user()->username,
            $registration_data = $secretkey
        );

        return view('pages.user.profil.show', compact('user', 'qrCodeImage', 'secretkey'));
    }

    public function confirmGoogle2fa(Request $request, $id)
    {
        User::where('id', $id)->first();
        User::where('id', $id)->update([
            'google2fa_secret' => $request->secretkey,
            'status_google2fa' => 1
        ]);

        return redirect()->back()->with('success', 'Berhasil Registrasi OTP');
    }

    public function resetAuth($id)
    {
        User::where('id', $id)->update(['status_google2fa' => null]);
        return redirect()->back()->with('success', 'Berhasil Reset OTP');
    }
}
