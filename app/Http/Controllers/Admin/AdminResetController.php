<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminResetController extends Controller
{
    public function show()
    {
        return view('admin.reset');
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required','string','max:20'],
            'new_password' => ['required','string','min:6'],
        ]);

        $user = User::where('phone', $data['phone'])->firstOrFail();
        $user->password = Hash::make($data['new_password']);
        $user->save();

        Activity::log(
            'admin_reset_password',
            "Admin reset password for {$user->name} (ID: {$user->id})",
            auth()->id(),
            $user
        );

        return back()->with('success', 'Password reset successful.');
    }

    public function resetPin(Request $request)
    {
        $data = $request->validate([
            'phone' => ['required','string','max:20'],
        ]);

        $user = User::where('phone', $data['phone'])->firstOrFail();
        $pin = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->pin()->updateOrCreate(
            ['user_id' => $user->id],
            ['pin_hash' => Hash::make($pin), 'expires_at' => null]
        );

        Activity::log(
            'admin_reset_pin',
            "Admin reset PIN for {$user->name} (ID: {$user->id})",
            auth()->id(),
            $user
        );

        // Show PIN to admin to print/share
        return back()->with('success', "PIN reset successful. New PIN: {$pin}");
    }
}
