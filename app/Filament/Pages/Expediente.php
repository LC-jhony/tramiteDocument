<?php

namespace App\Filament\Pages;

use App\Models\Document;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class Expediente extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.expediente';
    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->query(Document::whereHas('movement', function ($query) {
    //             $query->where('destination_area_id', Auth::user()->area_id);
    //         }))
    //         ->columns([
    //             TextColumn::make('asunto')
    //         ]);
    // }
}
