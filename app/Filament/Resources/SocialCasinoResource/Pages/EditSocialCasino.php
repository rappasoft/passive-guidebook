<?php

namespace App\Filament\Resources\SocialCasinoResource\Pages;

use App\Filament\Resources\SocialCasinoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialCasino extends EditRecord
{
    protected static string $resource = SocialCasinoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
