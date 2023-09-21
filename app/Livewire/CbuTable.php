<?php

namespace App\Livewire;

use App\Models\CapitalSubscription;
use App\Models\Member;
use App\Oxytoxin\ShareCapitalProvider;
use DB;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class CbuTable extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Member $member;

    public function table(Table $table): Table
    {
        return $table
            ->query(CapitalSubscription::whereMemberId($this->member->id))
            ->columns([
                TextColumn::make('code'),
                TextColumn::make('number_of_shares'),
                TextColumn::make('number_of_terms'),
                TextColumn::make('amount_subscribed')->money('PHP'),
                TextColumn::make('initial_amount_paid')->money('PHP'),
                TextColumn::make('outstanding_balance')->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()
                    ->modalContent(fn ($record) => view('filament.app.views.cbu-payments', ['cbu' => $record])),
                Action::make('Pay')
                    ->icon('heroicon-o-banknotes')
                    ->form([
                        Select::make('type')
                            ->options([
                                'OR' => 'OR',
                                'JV' => 'JV',
                                'CV' => 'CV',
                            ])
                            ->default('OR')
                            ->selectablePlaceholder(false)
                            ->required(),
                        TextInput::make('reference_number')->required(),
                        TextInput::make('amount')->required()->numeric()->minValue(1)->prefix('P'),
                        TextInput::make('remarks'),
                    ])
                    ->action(function ($record, $data) {
                        $record->payments()->create($data);
                        Notification::make()->title('Payment made for capital subscription!')->success()->send();
                    })
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        TextInput::make('number_of_terms')->numeric()->readOnly()->default(36)->minValue(36)->maxValue(36),
                        TextInput::make('number_of_shares')->numeric()->readOnly()->minValue(1)->default(20),
                        TextInput::make('amount_subscribed')->prefix('P')->numeric()->minValue(1)->default(2000)
                            ->live(true)
                            ->afterStateUpdated(function ($set, $state) {
                                $set('number_of_shares', ShareCapitalProvider::computeNumberOfSharesFromAmountSubscribed($state));
                            }),
                        TextInput::make('initial_amount_paid')->prefix('P')->numeric()->minValue(1)->default(2000),
                    ])
                    ->action(function ($data) {
                        DB::beginTransaction();
                        $cbu = $this->member->capital_subscriptions()->create([
                            ...$data,
                            'code' => Str::random(12)
                        ]);
                        $cbu->payments()->create([
                            'amount' => 0,
                            'reference_number' => '#ORIGINALAMOUNT',
                            'type' => 'OR',
                        ]);
                        $cbu->payments()->create([
                            'amount' => $cbu->initial_amount_paid,
                            'reference_number' => '#INITIALAMOUNTPAID',
                            'type' => 'OR',
                        ]);
                        DB::commit();
                        Notification::make()->title('Capital subscription created!')->success()->send();
                    })
            ])
            ->bulkActions([]);
    }

    public function render(): View
    {
        return view('livewire.cbu-table');
    }
}
