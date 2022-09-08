<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationBell extends Component
{
    public $MyInstructions;

    public $myJobs;

    public function mount()
    {
        $this->myInstructions = auth()->user()->incompleteInstructions->count();

        $this->myJobs = auth()->user()->incompleteJobs->count();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
