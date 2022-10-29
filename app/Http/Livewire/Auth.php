<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth as AppAuth;

class Auth extends Component
{

    public $signType = 'login';
    public $email, $password, $password_confirmation;

    public function login() {
        $validatedDate = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(AppAuth::attempt(array('email' => $this->email, 'password' => $this->password))){
            session()->flash('message', "You have been successfully login.");
        }else{
            session()->flash('error', 'email and password are wrong.');
        }

        $this->resetFields();
    }

    public function register() {
        $this->validate([
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        AppAuth::login($user);

        session()->flash('message', 'You have been successfully registered.');

        $this->resetFields();
    }

    public function submit() {
        $this->signType === 'login' ? $this->login() : $this->register();
    }

    public function changeSignType($type) {
        $this->signType = $type;
    }

    public function resetFields() {
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function render()
    {
        return view('livewire.auth');
    }
}
