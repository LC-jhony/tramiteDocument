<?php

namespace App\Livewire;

use App\View\Components\AppLayout;
use Livewire\Attributes\Layout;
use Livewire\Component;

class DocumentForm extends Component
{
    // public function mount():void{
    //     $this->form->fill();
    // }
    //#[Layout(AppLayout::class)]
    public function render()
    {
        return view('livewire.document-form')
        ->layout('layouts.app');
    }
}
