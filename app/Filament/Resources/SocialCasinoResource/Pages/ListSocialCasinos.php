<?php

namespace App\Filament\Resources\SocialCasinoResource\Pages;

use App\Filament\Resources\SocialCasinoResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSocialCasinos extends ListRecords
{
    protected static string $resource = SocialCasinoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            Tab::make('Active')
                ->modifyQueryUsing(fn(Builder $query) => $query->active()),
            Tab::make('Inactive')
                ->modifyQueryUsing(fn(Builder $query) => $query->inactive()),
        ];
    }
}
