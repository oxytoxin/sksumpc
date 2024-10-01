<?php

namespace App\Filament\App\Pages\Bookkeeper;

use App\Models\Member;
use App\Models\Account;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\LoanAccount;
use App\Models\VoucherType;
use App\Models\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use App\Actions\Loans\PayLegacyLoan;
use App\Models\JournalEntryVoucher;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Rules\BalancedBookkeepingEntries;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use Awcodes\FilamentTableRepeater\Components\TableRepeater;

class LegacyLoanVoucherPayments extends Page
{
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Bookkeeping';

    protected static string $view = 'filament.app.pages.bookkeeper.legacy-loan-voucher-payments';

    public $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage bookkeeping');
    }

    public function mount()
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->options([
                        'dv' => 'Disbursement Voucher',
                        'jev' => 'Journal Entry Voucher'
                    ])
                    ->required(),
                Select::make('voucher_type_id')
                    ->label('Voucher Type')
                    ->options(VoucherType::pluck('name', 'id')),
                TextInput::make('name')->required(),
                TextInput::make('address')->required(),
                TextInput::make('reference_number')->required(),
                TextInput::make('voucher_number')->required(),
                Textarea::make('description')->columnSpanFull()->required(),
                TableRepeater::make('voucher_items')
                    ->hideLabels()
                    ->columnSpanFull()
                    ->columnWidths(['account_id' => '13rem', 'member_id' => '13rem'])
                    ->rule(new BalancedBookkeepingEntries)
                    ->reactive()
                    ->reactive()
                    ->afterStateUpdated(function ($set, $state) {
                        $items = collect($state);
                        $cib = Account::getCashInBankGF();
                        $net_amount = $items->firstWhere('account_id', $cib?->id);
                        if ($net_amount) {
                            $items = $items->filter(fn($i) => $i['account_id'] != $net_amount['account_id']);
                            $net_amount['credit'] = $items->sum('debit') - $items->sum('credit');
                            $items->push($net_amount);
                        }
                        $set('disbursement_voucher_items', $items->toArray());
                    })
                    ->schema(function ($get) {
                        return [
                            Select::make('member_id')
                                ->options(Member::pluck('full_name', 'id'))
                                ->label('Member')
                                ->searchable()
                                ->reactive()
                                ->preload(),
                            Select::make('account_id')
                                ->options(
                                    fn($get) => Account::withCode()->whereDoesntHave('children', fn($q) => $q->whereNull('member_id'))->where('member_id', $get('member_id') ?? null)->pluck('code', 'id')
                                )
                                ->searchable()
                                ->required()
                                ->label('Account')
                                ->afterStateUpdated(function ($set) {
                                    $set('interest', null);
                                    $set('principal', null);
                                }),
                            TextInput::make('debit')
                                ->disabled(fn($get) => Account::find($get('account_id'))?->tag == 'member_loans_receivable')
                                ->dehydrated()
                                ->moneymask(),
                            TextInput::make('credit')
                                ->disabled(fn($get) => Account::find($get('account_id'))?->tag == 'member_loans_receivable')
                                ->dehydrated()
                                ->moneymask(),
                            TextInput::make('interest')
                                ->moneymask()
                                ->disabled(fn($get) => Account::find($get('account_id'))?->tag != 'member_loans_receivable')
                                ->afterStateUpdated(fn($set, $get) => $set('credit', floatval($get('interest') + floatval($get('principal'))))),
                            TextInput::make('principal')
                                ->moneymask()
                                ->disabled(fn($get) => Account::find($get('account_id'))?->tag != 'member_loans_receivable')
                                ->afterStateUpdated(fn($set, $get) => $set('credit', floatval($get('interest') + floatval($get('principal'))))),
                        ];
                    }),
                Actions::make([
                    Action::make('submit')
                        ->requiresConfirmation()
                        ->action(function () {
                            $data = $this->form->getState();
                            DB::beginTransaction();
                            $items = $data['voucher_items'];
                            unset($data['voucher_items']);
                            $data['transaction_date'] = config('app.transaction_date') ?? today();
                            $data['is_legacy'] = true;
                            if ($data['type'] == 'dv') {
                                unset($data['type']);
                                $voucher = DisbursementVoucher::create($data);
                                foreach ($items as $item) {
                                    unset($item['member_id']);
                                    if (isset($item['interest']) && isset($item['principal'])) {
                                        $item['details'] = [
                                            'interest' => $item['interest'],
                                            'principal' => $item['principal']
                                        ];
                                    }
                                    unset($item['interest'], $item['principal']);
                                    $voucher->disbursement_voucher_items()->create($item);
                                }
                            } else {
                                unset($data['type']);
                                $voucher = JournalEntryVoucher::create($data);
                                foreach ($items as $item) {
                                    unset($item['member_id']);
                                    if (isset($item['interest']) && isset($item['principal'])) {
                                        $item['details'] = [
                                            'interest' => $item['interest'],
                                            'principal' => $item['principal']
                                        ];
                                    }
                                    unset($item['interest'], $item['principal']);
                                    $voucher->journal_entry_voucher_items()->create($item);
                                }
                            }
                            DB::commit();
                            Notification::make()->title('Legacy loan payment posted!')->success()->send();
                            $this->reset();
                        })
                ])
            ])
            ->statePath('data');
    }
}
