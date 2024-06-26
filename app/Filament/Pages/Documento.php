<?php

namespace App\Filament\Pages;

use App\Enum\MovementStatus;
use App\Models\Area;
use App\Models\Document;
use App\Models\Movement;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\ViewField;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class Documento extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.documento';

    protected static ?string $navigationLabel = 'Mesa de parte';

    public function table(Table $table): Table
    {
        return $table
            ->query(Document::query())
            ->columns([
                TextColumn::make('code')
                    ->label('Codigo')
                    ->searchable(),
                TextColumn::make('dni')

                    ->label('DNI')
                    ->searchable()
                    ->placeholder('N/A'),
                TextColumn::make('ruc')
                    ->label('RUC')
                    ->searchable()
                    ->placeholder('N/A'),
                TextColumn::make('area.name')
                    ->label('Area')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('latestMovement.status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aceptado' => 'success',
                        'Proceso' => 'gray',
                        'Rechazado' => 'warning',
                        'Finalizado' => 'danger',
                    }),
                TextColumn::make('date')
                    ->label('Fecha'),
                // TextColumn::make('file')
            ])
            ->filters([
                SelectFilter::make('movement.status')
                    ->options(MovementStatus::class)
                    ->native(false),
            ])
            ->actions([
                CreateAction::make('create')
                    ->label('Derivar')
                    ->model(Movement::class)
                    ->fillForm(fn (Document $record): array => [
                        'document_id' => $record->id,
                        'area_origen_id' => $record->area_id,
                        // 'pdf' => $record->file,
                        'description' => $record->asunto,
                        'date_movement' => date(now()),
                    ])
                    ->form([

                        Fieldset::make('')
                            ->schema([
                                Forms\Components\Textarea::make('description')
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

                                        Forms\Components\Select::make('status')
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
                                        Forms\Components\FileUpload::make('mov_file')
                                            ->label('Archivo')
                                            ->hint('adjuntar archivo **Documento**')
                                            ->columnSpan(2),
                                        Forms\Components\MarkdownEditor::make('mov_description_origen')
                                            ->label('Descripcion')
                                            ->columnSpan(2),
                                    ])->columnSpan(2),
                                ViewField::make('pdf')
                                    ->columnSpan(3)
                                    // ->hiddenLabel()
                                    ->view('forms.components.pdf-view'),
                            ])->columns(5),
                    ])
                    ->modalHeading('Derivar documento')
                    ->modalDescription('formaulario para derivar y/o rechazar tramite')
                    ->createAnother(false)
                    ->successRedirectUrl(route('filament.admin.pages.documento'))
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Tramite')
                            ->body('documento aceptado'),
                    )
                    ->modalWidth(MaxWidth::SevenExtraLarge),
            ])
            ->bulkActions([
                DeleteBulkAction::make('Eliminar'),
            ]);
    }
}
