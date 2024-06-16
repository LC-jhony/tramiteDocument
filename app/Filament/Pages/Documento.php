<?php

namespace App\Filament\Pages;

use App\Enum\MovementStatus;
use App\Models\Area;
use App\Models\Document;
use App\Models\Movement;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

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
                    ->label('Codigo'),
                TextColumn::make('dni')
                    ->label('DNI')
                    ->placeholder('N/A'),
                TextColumn::make('ruc')
                    ->label('RUC')
                    ->placeholder('N/A'),
                TextColumn::make('date')
                    ->since(),
                // TextColumn::make('file')
            ])
            ->filters([
                // ...
            ])
            ->actions([
                CreateAction::make('create')
                    ->label('Derivar')
                    ->color('success')
                    ->model(Movement::class)
                    ->fillForm(fn (Document $record): array => [
                        'document_id' => $record->id,
                        'area_origen_id' => $record->id,
                        'pdf' => $record->file,
                        'description' => $record->asunto
                    ])
                    ->form([

                        Section::make('Documento')
                            ->description('Informacion del documento')
                            ->schema([

                                Forms\Components\Select::make('document_id')
                                    ->label('Documento')
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

                                Forms\Components\Select::make('status')
                                    ->label('Estado')
                                    ->options(MovementStatus::class)
                                    ->required()
                                    ->native(false),
                                Forms\Components\DatePicker::make('date_movement')
                                    ->label('Fecha de Movimiento')
                                    ->default(now())
                                    // ->disabled()
                                    // ->dehydrated()
                                    ->required(),

                                Forms\Components\Textarea::make('description')
                                    ->label('Descripción')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->columnSpan(5),

                            ])->columns(4),
                        Card::make()
                            ->schema([
                                Fieldset::make('')
                                    ->schema([
                                        Forms\Components\Select::make('destination_area_id')
                                            ->label('Destino')
                                            ->options(Area::all()->pluck('name', 'id'))
                                            ->native(false)
                                            ->columnSpan(2),

                                        Forms\Components\Select::make('user_id')
                                            ->label('Usuario')
                                            ->options(User::all()->pluck('name', 'id'))
                                            ->native(false)
                                            ->required()
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
                                    ->columnSpan(2)
                                    // ->hiddenLabel()
                                    ->view('forms.components.pdf-view')

                                    ->live()
                                    ->columnSpan(3),
                                // Fieldset::make('')
                                //     ->schema([])
                            ])->columns(5),

                    ])
                    ->modalWidth(MaxWidth::SevenExtraLarge),
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
