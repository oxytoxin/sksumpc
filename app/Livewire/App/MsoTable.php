<?php

namespace App\Livewire\App;

use App\Models\Member;
use Livewire\Attributes\Url;
use Livewire\Component;

class MsoTable extends Component
{
    #[Url('mso_type')]
    public $mso_type = 1;

    public Member $member;

    public function render()
    {
        return view('livewire.app.mso-table');
    }

    public function mount()
    {
        $this->mso_type = request('mso_type', 1);
    }
}
