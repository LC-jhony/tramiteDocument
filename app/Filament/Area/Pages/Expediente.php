<?php

namespace App\Filament\Area\Pages;

use App\Models\Document;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class Expediente extends Page  implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static string $view = 'filament.area.pages.expediente';

    public function table(Table $table): Table
    {
        return $table
            ->query(Document::whereHas('movement', function ($query) {
                $query->where('destination_area_id', Auth::user()->area_id);
            }))
            ->columns([
                TextColumn::make('movement.description'),
                TextColumn::make('code'),
            ])
            // ->columns([
            //     TextColumn::make('code')
            //         ->label('Codigo')
            //         ->searchable(),
            //     TextColumn::make('dni')

            //         ->label('DNI')
            //         ->searchable()
            //         ->placeholder('N/A'),
            //     TextColumn::make('ruc')
            //         ->label('RUC')
            //         ->searchable()
            //         ->placeholder('N/A'),
            //     TextColumn::make('area.name')
            //         ->label('Area')
            //         ->searchable()
            //         ->sortable(),
            //     TextColumn::make('movement.status')
            //         ->label('Estado')
            //         ->badge()
            //         ->color(fn (string $state): string => match ($state) {
            //             'Aceptado' => 'success',
            //             'Proceso' => 'gray',
            //             'Rechazado' => 'warning',
            //             'Finalizado' => 'danger',
            //         }),
            //     TextColumn::make('date')
            //         ->label('Fecha'),
            //     // TextColumn::make('file')
            // ])
            ->filters([
                //
            ])
            ->actions([])
            ->bulkActions([
                //
            ]);
    }
}
