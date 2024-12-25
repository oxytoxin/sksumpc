<?php

namespace App\Filament\App\Pages;

use App\Models\Member;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class Mmigs extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.app.pages.mmigs';

    protected static ?int $navigationSort = 15;

    protected static ?string $navigationGroup = 'Share Capital';

    protected static ?string $navigationLabel = 'MMIGS';

    public function getHeading(): string|Htmlable
    {
        return 'MMIGS';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('manage cbu');
    }

    public function table(Table $table)
    {
        return $table
            ->query(Member::query())
            ->columns([
                TextColumn::make('alt_full_name')->label('Name')->searchable(),
                TextColumn::make('patronage_status.name'),
                TextColumn::make('membership_acceptance.bod_resolution')->label('BOD Resolution'),
                TextColumn::make('membership_acceptance.effectivity_date')->label('Date Accepted')->date('F d, Y'),
            ])
            ->filters([
                SelectFilter::make('member_type')
                    ->relationship('member_type', 'name'),
                SelectFilter::make('patronage_status')
                    ->relationship('patronage_status', 'name'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent);
    }
}
