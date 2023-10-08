<?php

namespace App\Livewire\App;

use App\Models\Member;
use Livewire\Component;

class MsoTable extends Component
{
    public $mso_type = 1;
    public Member $member;

    public function render()
    {
        return view('livewire.app.mso-table');
    }
}
