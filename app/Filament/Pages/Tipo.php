<?php

namespace App\Filament\Pages;

use App\Models\Type;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Tipo extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tipo';
    protected static ?string $navigationLabel = 'Documento';
    public ?array $data = [];
    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Tipo documento')
                    ->description('registre eltipo de documento para el tramite')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->rule('string')
                    ])
            ])->statePath('data');
    }
    public function createType(): void
    {

        $type = Type::create($this->form->getstate());
        $this->form->model($type)->saveRelationships();
        $this->redirect(route('filament.admin.pages.tipo'));
        $this->getSaveNotification()->send();
    }
    public function getSaveNotification()
    {
        return Notification::make()
            ->title('Tipo')
            ->body('Se registro corectamente el tipo de documento!')
            ->success();
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(Type::query())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
                // ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                EditAction::make('edit')
                    ->label('Editar')
                    ->form([
                        Forms\Components\TextInput::make('name')

                    ]),
                DeleteAction::make('delete')
                    ->label('Eliminar')
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                // ...
            ]);
    }
}
