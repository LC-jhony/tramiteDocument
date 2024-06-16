<?php

namespace App\Livewire;

use App\Models\Document;
use App\Models\Type;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Wallo\FilamentSelectify\Components\ToggleButton;

class DocumentForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Registre tramite')
                    ->description(' registre su tramite llene los campos requeridos para registrar su tramite')
                    ->schema([
                        Fieldset::make('')->schema([
                            ToggleButton::make('representation')
                                ->offColor('info')
                                ->onColor('success')
                                ->offLabel('Persona Juridica')
                                ->onLabel('Persona Natural')
                                ->requiredWith('juridica')
                                ->default(true)
                                ->live()
                                ->afterStateUpdated(
                                    fn ($state, callable $set) => $state ? $set('juridica', null) : $set('juridica', 'hidden')
                                ),
                            Grid::make('natural')
                                ->hidden(
                                    fn (callable $get): bool => $get('representation') == false
                                )
                                ->schema([
                                    Section::make()
                                        ->schema([
                                            TextInput::make('dni')
                                                ->label('DNI')
                                                ->numeric()
                                                ->required()
                                                ->requiredWith('representation'),
                                            TextInput::make('name')
                                                ->label('Nombre')
                                                ->required()
                                                ->requiredWith('representation'),
                                            TextInput::make('lasta_name')
                                                ->label('Apellido paterno')
                                                ->required()
                                                ->requiredWith('representation'),
                                            TextInput::make('first_name')
                                                ->label('Apellido materno')
                                                ->required()
                                                ->requiredWith('representation'),
                                        ])->columns(2),
                                ]),
                            Grid::make('juridica')
                                ->hidden(
                                    fn (callable $get): bool => $get('representation') == true
                                )
                                ->schema([
                                    Section::make('')
                                        ->schema([
                                            TextInput::make('ruc')
                                                ->numeric()
                                                ->requiredWith('representation'),
                                            TextInput::make('empresa')
                                                ->requiredWith('representation'),
                                        ])->columns(2),
                                ]),
                            Grid::make('')
                                ->schema([
                                    TextInput::make('phone')
                                        ->label('Celular | Telefono')
                                        ->tel()
                                        ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                                    TextInput::make('email'),
                                ])->columns(2),
                            TextInput::make('addres')
                                ->label('direccion')
                                ->columnSpan(2),

                        ])->columnSpan(2),
                        Fieldset::make('')->schema([
                            Grid::make()
                                ->schema([
                                    Section::make('')
                                        ->schema([
                                            Grid::make('')
                                                ->schema([
                                                    Select::make('type_id')
                                                        ->label('Tipo documento')
                                                        ->options(Type::query()->pluck('name', 'id'))
                                                        ->required()
                                                        ->native(false),
                                                    TextInput::make('code')
                                                        ->label('Codigo de Documento')
                                                        ->default('COD-' . random_int(100000, 999999))
                                                        ->disabled()
                                                        ->dehydrated()
                                                        ->required()
                                                        ->maxLength(32)
                                                        ->unique(Document::class, 'code', ignoreRecord: true),
                                                    DatePicker::make('date')
                                                        ->label('Fecha de registro')
                                                        ->default(now()->format('Y-m-d'))
                                                        ->disabled()
                                                        ->dehydrated()
                                                        ->required(),
                                                ])->columns(2),
                                        ]),
                                    Section::make('')
                                        ->schema([
                                            Grid::make('')
                                                ->schema([
                                                    TextInput::make('folio')
                                                        ->label('Folio')
                                                        //->hint('Forgotten your password? Bad luck.')
                                                        ->required()
                                                        ->numeric(),
                                                    Select::make('area_id')
                                                        ->label('Area')
                                                        ->options([
                                                            '1' => 'MESA DE PARTES',
                                                        ])
                                                        ->default(1)
                                                        ->selectablePlaceholder(false)
                                                        ->live()
                                                        ->required()
                                                        ->native(false),

                                                ])->columns(2),
                                            FileUpload::make('file')
                                                ->label('Adjunte su documento')
                                                ->directory('documents')
                                                ->helperText(str('El archivo  **de tramite** debe de subirlo para realizar el tramite.')->inlineMarkdown()->toHtmlString())
                                                ->getUploadedFileNameForStorageUsing(
                                                    fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                                        ->prepend('tramite-'),
                                                )
                                                ->acceptedFileTypes(['application/pdf'])
                                                ->required(),
                                        ]),

                                    // Forms\Components\TextInput::make('status'),
                                ])->columns(2),
                            Textarea::make('asunto')
                                ->label('Asunto del documento')
                                ->required()
                                ->columnSpan(2),
                        ])->columnSpan(2),
                    ])->columns(4),
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $document = Document::create($this->form->getState());
        $this->form->model($document)->saveRelationships();
        $this->getSavedNotification()->send();
    }

    public function getSavedNotification(): Notification
    {
        return Notification::make()
            ->title('Documento')
            ->body('Se registro tu tramite corectamente revise su correo')
            ->success();
    }

    public function render()
    {
        return view('livewire.document-form')
            ->layout('layouts.app');
    }
}
