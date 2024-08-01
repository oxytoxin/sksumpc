<?php

namespace App\Filament\App\Resources\MemberResource\Pages;

use App\Filament\App\Pages\Cashier\RequiresBookkeeperTransactionDate;
use App\Filament\App\Resources\MemberResource;
use Filament\Resources\Pages\EditRecord;

class EditMember extends EditRecord
{
    use RequiresBookkeeperTransactionDate;
    
    protected static string $resource = MemberResource::class;
}
