<?php

namespace App\Filament\App\Pages;

use App\Models\Gender;
use App\Models\Member;
use App\Models\MemberType;
use Filament\Pages\Page;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Livewire\Attributes\Computed;

class CbuSchedule extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.app.pages.share-capital';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Share Capital';

    protected static ?string $navigationLabel = 'CBU Schedule';

    protected ?string $heading = 'CBU Schedule';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    private function number_of_shares_paid($record)
    {
        return $record->capital_subscriptions->map(fn ($cs) => intdiv($cs->payments()->sum('amount'), $cs->par_value))->sum();
    }

    private function amount_shares_paid($record)
    {
        return $record->capital_subscriptions->map(fn ($cs) => intdiv($cs->payments()->sum('amount'), $cs->par_value) * $cs->par_value)->sum();
    }

    #[Computed]
    public function Gender()
    {
        return Gender::find($this->tableFilters['gender_id']['value'])?->name;
    }

    #[Computed]
    public function MemberType()
    {
        return MemberType::find($this->tableFilters['member_type_id']['value'])?->name;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Member::query()
                    ->has('capital_subscriptions')
                    ->withSum('capital_subscriptions', 'number_of_shares')
                    ->withSum('capital_subscriptions', 'amount_subscribed')
                    ->withSum('capital_subscription_payments', 'amount')
                    ->orderBy('alt_full_name')
            )
            ->content(fn () => view('filament.app.views.cbu-schedule'))
            ->filters([
                SelectFilter::make('member_type_id')
                    ->relationship('member_type', 'name')
                    ->label('Member Type'),
                SelectFilter::make('gender_id')
                    ->relationship('gender', 'name')
                    ->label('Gender'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                //
            ]);
    }
}
