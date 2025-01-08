<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class changePassword extends Component
{
    public  $current_password;
    public  $new_password;
    public $new_password_confirmation;

    public function updatePassword()
    {
        $validatedData = $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required',
        ]);

        if (!Hash::check($this->current_password, Auth::user()->password)) {
            session()->flash('error', 'رمز فعلی اشتباه است');
            return;
        }

        $user = Auth::user();
        $user->password = Hash::make($this->new_password);
        $user->first_logged_in = true;
        $user->save();
        logActivity('password changed', 'app\Models\users', $user->id);
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login')->with('status', '!رمز تان موفقانه تجدید گردید، با رمز جدید تان دوباره وارد سیستم شوید');
    }
    public function render()
    {
        return view('livewire.auth.changePassword');
    }
}
