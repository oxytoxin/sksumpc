<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Models\User;
use App\Models\Member;
use App\Models\Saving;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\App\Resources\MemberResource;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\App\Pages\Cashier\Reports\HasSignatories;

class SavingsSubsidiaryLedger extends Page implements HasTable
{
    use InteractsWithTable, HasSignatories;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.savings-subsidiary-ledger';

    public Member $member;

    public function getHeading(): string|Htmlable
    {
        return 'Savings Subsidiary Ledger';
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
            ->query(Saving::query()->where('member_id', $this->member->id))
            ->content(fn () => view('filament.app.views.savings-sl', ['member' => $this->member, 'signatories' => $this->signatories]))
            ->filters([
                Filter::dateRange('transaction_date'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->headerActions([
                Action::make('back')
                    ->label('Back to Savings')
                    ->url(route('filament.app.resources.members.view', ['record' => $this->member, 'tab' => '-mso-tab', 'mso_type' => 1]))
            ])
            ->paginated(['all']);
    }
}
