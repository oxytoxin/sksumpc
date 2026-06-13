<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Actions\Memberships\UpdateMemberCreditAndBackground;
use App\Filament\App\Resources\MemberResource;
use App\Models\Member;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Schema;

class EditMemberCreditAndBackground extends Page implements HasForms
{
    use InteractsWithForms;

    public Member $record;

    public ?array $data;

    protected static string $resource = MemberResource::class;

    protected static ?string $title = 'Edit Credit & Background';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return false;
    }

    protected string $view = 'filament.app.resources.member-resource.pages.edit-member-credit-and-background';

    public function mount(): void
    {
        $this->form->fill($this->getData());
    }

    private function getData(): array
    {
        return MemberResource::getCreditAndBackgroundFormData($this->record);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                ...MemberResource::getCreditAndBackgroundFormSchema(),
                Actions::make([
                    \Filament\Actions\Action::make('save')
                        ->label('Save')
                        ->color('success')
                        ->keyBindings(['command+s', 'ctrl+s'])
                        ->action(function () {
                            $data = $this->form->getState();

                            app(UpdateMemberCreditAndBackground::class)->handle($this->record, $data);

                            Notification::make()->title('Credit & background updated.')->success()->send();
                        }),
                ])->alignRight(),
            ]);
    }
}
