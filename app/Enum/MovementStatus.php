<?php

namespace App\Enum;

use Filament\Support\Contracts\HasLabel;

enum MovementStatus: string implements HasLabel
{
    case Aceptado = 'Aceptado';
    case Proceso = 'Proceso';
    case Rechazado = 'Rechazado';
    case Finalizado = 'Finalizado';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::Aceptado => 'Aceptado',
            self::Proceso => 'Proceso',
            self::Rechazado => 'Rechazado',
            self::Finalizado => 'Finalizado'
        };
    }
}
