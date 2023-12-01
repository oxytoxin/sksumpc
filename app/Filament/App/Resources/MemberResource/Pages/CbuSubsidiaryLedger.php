<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Models\User;
use App\Models\Member;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Models\CapitalSubscriptionPayment;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\App\Resources\MemberResource;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\App\Pages\Cashier\Reports\HasSignatories;


class CbuSubsidiaryLedger extends Page implements HasTable
{
    use InteractsWithTable, HasSignatories;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.cbu-subsidiary-ledger';

    public Member $member;

    public function getHeading(): string|Htmlable
    {
        return 'CBU Subsidiary Ledger';
    }

    protected function getSignatories()
    {
        $manager = User::whereRelation('roles', 'name', 'manager')->first();
        $this->signatories = [
            [
                'action' => 'Prepared by:',
                'name' => auth()->user()->name,
                'position' => 'Teller/Cashier'
            ],
            [
                'action' => 'Noted:',
                'name' => $manager?->name ?? 'FLORA C. DAMANDAMAN',
                'position' => 'Manager'
            ],
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(CapitalSubscriptionPayment::query()->whereRelation('capital_subscription', 'member_id', $this->member->id))
            ->content(fn () => view('filament.app.views.cbu-sl', ['member' => $this->member, 'signatories' => $this->signatories]))
            ->filters([
                Filter::dateRange('transaction_date'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->headerActions([
                Action::make('back')
                    ->extraAttributes(['wire:ignore' => true])
                    ->label('Back to CBU')
                    ->url(route('filament.app.resources.members.view', ['record' => $this->member, 'tab' => '-cbu-tab']))
            ])
            ->paginated(['all']);
    }
}
