<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use App\Filament\App\Resources\CapitalSubscriptionResource\Pages\ListCapitalSubscriptions;
use App\Filament\App\Resources\CapitalSubscriptionResource\Pages\Reports\TopTenHighestCbuReport;
use App\Filament\App\Resources\CapitalSubscriptionResource\Pages\ShareCertificate;
use App\Filament\App\Resources\CapitalSubscriptionResource\Pages;
use App\Models\CapitalSubscription;
use App\Models\MemberSubtype;
use App\Models\MemberType;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CapitalSubscriptionResource extends Resource
{
    protected static ?string $model = CapitalSubscription::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Share Capital';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member.alt_full_name')->sortable()->searchable(),
                TextColumn::make('amount_subscribed')->money('PHP'),
                TextColumn::make('monthly_payment')->money('PHP'),
                TextColumn::make('outstanding_balance')->money('PHP'),
            ])
            ->filters([
                SelectFilter::make('member_type_id')
                    ->query(function ($query, $state) {
                        return $query
                            ->when($state['value'], function ($query, $value) {
                                return $query->whereRelation('member', 'member_type_id', $value);
                            });
                    })
                    ->label('Member Type')
                    ->options(MemberType::pluck('name', 'id')),
                SelectFilter::make('member_subtype_id')
                    ->query(function ($query, $state) {
                        return $query
                            ->when($state['value'], function ($query, $value) {
                                return $query->whereRelation('member', 'member_subtype_id', $value);
                            });
                    })
                    ->label('Member Subtype')
                    ->options(MemberSubtype::pluck('name', 'id')),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->defaultSort('member.alt_full_name')
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCapitalSubscriptions::route('/'),
            'reports.top-ten-highest-cbu' => TopTenHighestCbuReport::route('/reports/top-ten-highest-cbu'),
            'share-certificate' => ShareCertificate::route('/share-certificate/{capital_subscription}'),
        ];
    }
}
