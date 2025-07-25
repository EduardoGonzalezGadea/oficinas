<?php
use function Livewire\Volt\layout;
use function Livewire\Volt\state;

layout('layouts.app');

state(['module_name']);
?>

<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Módulo de {{ $module_name }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    ¡Bienvenido al módulo de {{ $module_name }}!
                </div>
            </div>
        </div>
    </div>
</div>