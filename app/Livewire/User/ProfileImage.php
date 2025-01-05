<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class ProfileImage extends Component
{
    public $user;
    protected $listeners = [
        'nameUpdated' => 'refreshFullName',
        'profilePhotoUpdated' => 'refreshPhoto',
    ];

    public function refreshFullName($full_name)
    {
        // Ensure the method is being called
        $this->user->full_name = $full_name;
    }

    public function refreshPhoto($newPhotoPath)
    {
        // Ensure the method is being called
        $this->user->profile_photo_path = $newPhotoPath; // Update the path
    }

    public function render()
    {
        if (auth()->check()) {
            $this->user = User::find(auth()->user()->id);
        } else {
            // Handle unauthenticated state
            $this->user = null;
        }

        return view('livewire.user.profile-image');
    }
}
