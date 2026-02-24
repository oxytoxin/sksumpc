<?php

    use App\Actions\Imprests\GenerateImprestsInterestForMember;
    use App\Actions\LoveGifts\GenerateLoveGiftsInterestForMember;
    use App\Actions\Savings\GenerateSavingsInterestForMember;
    use App\Models\Member;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Member::each(function ($member) {
                $date = '2025-12-31';
                app(GenerateSavingsInterestForMember::class)->handle($member, $date);
                app(GenerateLoveGiftsInterestForMember::class)->handle($member, $date);
                app(GenerateImprestsInterestForMember::class)->handle($member, $date);
            });
        }
    };
