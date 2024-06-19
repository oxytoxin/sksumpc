<?php

namespace App\Filament\App\Pages\Cashier;

use App\Filament\App\Pages\Cashier\Traits\HasReceipt;
use App\Filament\App\Pages\Cashier\Traits\HasSpecificCashCollectionForm;
use App\Models\CashCollectible;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Livewire\Component;

class DormitoryTransactions extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms, RequiresBookkeeperTransactionDate, HasReceipt, HasSpecificCashCollectionForm;

    public function form(Form $form)
    {
        return $this->generateForm($form, $this->transaction_date, CashCollectible::where('cash_collectible_category_id', 2)->pluck('name', 'id'));
    }
    public function mount()
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('filament.app.pages.cashier.dormitory-transactions');
    }
}
