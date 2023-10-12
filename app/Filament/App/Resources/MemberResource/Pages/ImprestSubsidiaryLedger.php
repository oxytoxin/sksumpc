<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Resources\MemberResource;
use App\Models\Imprest;
use App\Models\Member;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;

class ImprestSubsidiaryLedger extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = MemberResource::class;

    protected static string $view = 'filament.app.resources.member-resource.pages.imprest-subsidiary-ledger';

    public Member $member;

    public function getHeading(): string|Htmlable
    {
        return 'Imprests Subsidiary Ledger for ' . $this->member->full_name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Imprest::query()->where('member_id', $this->member->id))
            ->columns([
                TextColumn::make('transaction_date')
                    ->date('m/d/Y')
                    ->label('DATE'),
                TextColumn::make('reference_number')
                    ->label('REF.#'),
                TextColumn::make('dr')
                    ->label('DR'),
                TextColumn::make('amount')
                    ->label('CR')
                    ->money('PHP')
                    ->summarize(Sum::make()->label('')->money('PHP')),
                TextColumn::make('remarks'),
            ])
            ->filters([
                Filter::make('transaction_date')
                    ->form([
                        DatePicker::make('from')
                            ->default(today()->startOfYear()),
                        DatePicker::make('to')
                            ->default(today()),
                    ])
                    ->columns(8)
                    ->columnSpanFull()
                    ->query(fn (Builder $query, array $data) => $query->whereDate('transaction_date', '>=', $data['from'])->whereDate('transaction_date', '<=', $data['to']))
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->headerActions([
                Action::make('back')
                    ->label('Back to Imprests')
                    ->url(route('filament.app.resources.members.view', ['record' => $this->member, 'tab' => '-mso-tab', 'mso_type' => 2]))
            ])
            ->paginated(['all']);
    }
}
