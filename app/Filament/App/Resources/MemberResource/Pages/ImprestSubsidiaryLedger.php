<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\MemberResource;
use App\Models\Imprest;
use App\Models\Member;
use App\Models\SignatureSet;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ImprestSubsidiaryLedger extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.imprest-subsidiary-ledger';

    public Member $member;

    public function getHeading(): string|Htmlable
    {
        return 'Imprests Subsidiary Ledger';
    }

    protected function getSignatureSet()
    {
        return SignatureSet::where('name', 'SL Reports')->first();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Imprest::query()->where('member_id', $this->member->id))
            ->content(fn () => view('filament.app.views.imprests-sl', ['member' => $this->member, 'signatories' => $this->signatories]))
            ->filters([
                DateRangeFilter::make('transaction_date')
                    ->format('m/d/Y')
                    ->defaultToday()
                    ->displayFormat('MM/DD/YYYY'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->headerActions([
                Action::make('back')
                    ->extraAttributes(['wire:ignore' => true])
                    ->label('Back to Imprests')
                    ->url(route('filament.app.resources.members.view', ['record' => $this->member, 'tab' => '-mso-tab', 'mso_type' => 2])),
            ])
            ->paginated(['all']);
    }
}
