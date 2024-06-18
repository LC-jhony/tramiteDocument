<?php

namespace App\Livewire;

use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;

class RemitirCreate extends Component implements HasForms
{
    use InteractsWithForms;
    public function mount(): void
    {
        $record = Document::all();
        $this->form->fill();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('document_id')
                    ->label('Documento')
                    ->options(Document::all()->pluck('code', 'id')->toArray())
                    ->disabled()
                    ->dehydrated()
                    ->required(),
            ]);
    }
    public function render()
    {
        return view('livewire.remitir-create');
    }
}
