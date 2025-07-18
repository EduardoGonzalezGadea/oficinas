<?php
use function Livewire\Volt\state;
use function Livewire\Volt\layout;

// Usaremos el layout de invitado, porque es el que nos da problemas
layout('layouts.guest');

state(['count' => 0]);

$increment = fn () => $this->count++;
$decrement = fn () => $this->count--;

?>

<div>
    <h1 style="font-size: 2rem; text-align: center; margin-bottom: 1rem;">Prueba del Contador de Livewire</h1>
    
    <div style="text-align: center; margin-bottom: 1rem;">
        <span style="font-size: 3rem; font-weight: bold;">{{ $count }}</span>
    </div>

    <div style="display: flex; justify-content: center; gap: 1rem;">
        <button wire:click="increment" style="padding: 0.5rem 1rem; font-size: 1rem; background-color: #22c55e; color: white; border-radius: 0.25rem;">+</button>
        <button wire:click="decrement" style="padding: 0.5rem 1rem; font-size: 1rem; background-color: #ef4444; color: white; border-radius: 0.25rem;">-</button>
    </div>

    <p style="text-align: center; margin-top: 2rem; color: #6b7280;">
        Si haces clic en los botones y el número cambia SIN recargar la página, Livewire está funcionando correctamente.
        <br>
        Si la página se recarga, la instalación de Livewire está fundamentalmente rota.
    </p>
</div>