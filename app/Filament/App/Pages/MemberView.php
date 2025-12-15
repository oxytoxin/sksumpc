<?php

namespace App\Filament\App\Pages;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;
use Filament\Schemas\Components\Livewire;
use App\Filament\App\Resources\MemberResource;
use App\Infolists\Components\DependentsEntry;
use App\Livewire\App\CbuTable;
use App\Livewire\App\LoansTable;
use App\Livewire\App\MsoTable;
use App\Models\Member;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Pages\Page;

class MemberView extends Page implements HasForms, HasInfolists
{
    use InteractsWithForms, InteractsWithInfolists;

    protected static string $resource = MemberResource::class;

    protected string $view = 'filament.app.resources.member-resource.pages.member-view';

    protected static string | \BackedEnum | null $navigationIcon = 'icon-dashboard';

    public Member $member;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasRole('member');
    }

    public function mount()
    {
        $this->member = auth()->user()->member;
    }

    public function memberInfolist(Schema $schema)
    {
        return $schema
            ->record($this->member)
            ->components([
                Tabs::make()
                    ->tabs([
                        Tab::make('Profile')
                            ->schema([
                                Section::make([
                                    SpatieMediaLibraryImageEntry::make('profile_photo')
                                        ->label('')
                                        ->collection('profile_photo')->circular()
                                        ->visible(fn () => $this->member->getFirstMedia('profile_photo')),
                                    TextEntry::make('full_name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->alignStart(),
                                    TextEntry::make('dob')->label('Date of Birth')->date('F d, Y')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('place_of_birth')->label('Place of Birth')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('gender.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('civil_status.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('contact_number')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('religion.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('highest_educational_attainment')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    TextEntry::make('tin')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->label('TIN'),
                                    TextEntry::make('member_type.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel()->label('Member Type'),
                                    TextEntry::make('division.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                ]),
                                Section::make()
                                    ->schema([
                                        TextEntry::make('occupation.name')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('present_employer')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('annual_income')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('other_income_sources')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    ]),
                                Section::make()
                                    ->schema([
                                        DependentsEntry::make('dependents')->label('Number of Dependents')
                                            ->inlineLabel(),
                                    ]),
                                Section::make('Initial Capital Subscription')
                                    ->schema([
                                        TextEntry::make('membership_acceptance.effectivity_date')->label('Date Accepted')->date('F d, Y')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('membership_acceptance.bod_resolution')->label('BOD Resolution')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('member_type.name')->label('Type of Member')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('initial_capital_subscription.number_of_shares')->label('# of Shares Subscribed')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                        TextEntry::make('initial_capital_subscription.amount_subscribed')->label('Amount Subscribed')->money('PHP')->extraAttributes(['class' => 'font-semibold'])->inlineLabel(),
                                    ]),
                                Actions::make([
                                    Action::make('print')
                                        ->url(route('filament.app.resources.members.print', ['member' => $this->member])),
                                ])->alignEnd(),
                            ]),
                        Tab::make('CBU')
                            ->schema(fn ($record) => [
                                Livewire::make(CbuTable::class, ['member' => $record]),
                            ]),
                        Tab::make('MSO')
                            ->schema(fn ($record) => [
                                Livewire::make(MsoTable::class, ['member' => $record]),
                            ]),
                        Tab::make('Loan')
                            ->schema(fn ($record) => [
                                Livewire::make(LoansTable::class, ['member' => $record]),
                            ]),
                    ])->persistTabInQueryString(),
            ])
            ->columns(1);
    }
}
