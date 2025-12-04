<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Pages\Cashier\Reports\HasSignatories;
use App\Filament\App\Resources\MemberResource;
use App\Models\CapitalSubscriptionPayment;
use App\Models\Member;
use App\Models\SignatureSet;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class CbuSubsidiaryLedger extends Page implements HasTable
{
    use HasSignatories, InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.cbu-subsidiary-ledger';

    public Member $member;

    public function getHeading(): string|Htmlable
    {
        return 'CBU Subsidiary Ledger';
    }

    protected function getSignatureSet()
    {
        return SignatureSet::where('name', 'SL Reports')->first();
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
                    ->url(route('filament.app.resources.members.view', ['record' => $this->member, 'tab' => '-cbu-tab'])),
            ])
            ->paginated(['all']);
    }
}
