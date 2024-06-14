<?php

namespace App\Filament\Pages;

use App\Models\Area as ModelsArea;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Area extends Page
{


    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static string $view = 'filament.pages.area';
    public ?array $data = [];
    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Area')
                    ->description('Registra area de la institucion')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->rules('required')
                            ->rules('required'),
                        Forms\Components\TextInput::make('code')
                            ->label('Codigo de Area')
                            ->default('COD-' . random_int(100000, 999999))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(32)
                            ->unique(ModelsArea::class, 'code', ignoreRecord: true),
                    ])->columns(2)

            ])
            ->statePath('data');
    }
    public function createArea(): void
    {
        $area = ModelsArea::create($this->form->getstate());
        $this->form->model($area)->saveRelationships();
        $this->redirect(route('filament.admin.pages.area'));
        $this->getSaveNotification()->send();
    }
    public function getSaveNotification()
    {
        return Notification::make()
            ->title('Area')
            ->body('Se registro corectamente el area')
            ->success();
    }
}
