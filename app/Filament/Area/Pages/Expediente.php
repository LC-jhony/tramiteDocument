<?php

namespace App\Filament\Area\Pages;

use App\Enum\MovementStatus;
use App\Models\Area;
use App\Models\Document;
use App\Models\Movement;
use filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class Expediente extends Page  implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.area.pages.expediente';
    protected static ?string $navigationLabel = 'jhony';
    public function table(Table $table): Table
    {
        return $table
            ->query(Document::whereHas('movement', function ($query) {
                $query->where('destination_area_id', Auth::user()->area_id);
            }))
            ->columns([
                TextColumn::make('code')
                    ->label('Archivo'),
                TextColumn::make('latestMovement.areaOrigen.name')
                    ->label('Area origrn'),
                TextColumn::make('movement.mov_description_origen')
                    ->label('descripcion origen')
                    ->getStateUsing(fn ($record) => optional($record->movement->last())->mov_description_origen),
                TextColumn::make('movement.status')
                    ->label('Estado')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aceptado' => 'success',
                        'Proceso' => 'gray',
                        'Rechazado' => 'warning',
                        'Finalizado' => 'danger',
                    })
            ])
            ->filters([
                // ...
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                CreateAction::make('create')
                    ->label('Derivar')
                    ->model(Movement::class)
                    ->fillForm(function (Document $record): array {
                        $lastMovement = $record->movement()->latest('created_at')->first();
                        return [
                            'document_id' => $record->id,
                            'description' => $lastMovement ? $lastMovement->mov_description_origen : '',
                            'area_origen_id' => $lastMovement ? $lastMovement->destination_area_id : '',
                            'date_movement' => date(now()),
                        ];
                    })
                    ->form([

                        Fieldset::make('')
                            ->schema([

                                Textarea::make('description')
                                    ->label('Descripción')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->columnSpan(5),

                            ]),
                        Card::make()
                            ->schema([
                                Fieldset::make('')
                                    ->schema([

                                        Select::make('status')
                                            ->label('Estado')
                                            ->placeholder('Estado')
                                            ->options(MovementStatus::class)
                                            ->required()
                                            ->native(false),
                                        DatePicker::make('date_movement')
                                            ->label('Fecha de Movimiento')
                                            ->default(now())
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                        Forms\Components\Select::make('document_id')
                                            ->label('Tramite codigo')
                                            ->options(Document::all()->pluck('code', 'id')->toArray())
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                        Forms\Components\Select::make('area_origen_id')
                                            ->label('Area origen ')
                                            ->options(Area::all()->pluck('name', 'id')->toArray())
                                            ->disabled()
                                            ->dehydrated()
                                            ->required(),
                                        Forms\Components\Select::make('destination_area_id')
                                            ->label('Destino')
                                            ->helperText(new HtmlString('seleccione <strong>Area | Oficina </strong>para derivar documento.'))
                                            ->options(Area::all()->pluck('name', 'id'))
                                            ->required()
                                            ->native(false)
                                            ->columnSpan(2),
                                        FileUpload::make('mov_file')
                                            ->label('Archivo')
                                            ->hint('adjuntar archivo **Documento**')
                                            ->columnSpan(2),
                                        MarkdownEditor::make('mov_description_origen')
                                            ->label('Descripcion')
                                            ->columnSpan(2),
                                    ])->columnSpan(2),
                                ViewField::make('pdf')
                                    ->columnSpan(3)
                                    // ->hiddenLabel()
                                    ->view('forms.components.pdf-view'),
                            ])->columns(5),
                    ])->modalWidth(MaxWidth::SevenExtraLarge)
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
