<?php

namespace App\Filament\Resources\PlantResource\Pages;

use App\Filament\Resources\PlantResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePlant extends CreateRecord
{
    protected static string $resource = PlantResource::class;

    public static function canViewAny(): bool
    {
        return Auth::user()->is_admin;
    }
}
