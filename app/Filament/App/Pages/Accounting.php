<?php

    namespace App\Filament\App\Pages;

    use Filament\Pages\Page;
    use Illuminate\Support\Facades\DB;

    class Accounting extends Page
    {
        protected static string|\BackedEnum|null $navigationIcon = 'icon-accounting';

        protected string $view = 'filament.app.pages.accounting';

        protected static ?int $navigationSort = 11;

        public static function shouldRegisterNavigation(): bool
        {
            return false;
        }

        public function mount(): void
        {
            $hasActiveSessions = DB::table('sessions')
                ->where('last_activity', '>=', now()->subMinutes(config('session.lifetime'))->timestamp)
                ->whereNot('user_id', auth()->id())
                ->get();
            dd($hasActiveSessions);
        }
    }
