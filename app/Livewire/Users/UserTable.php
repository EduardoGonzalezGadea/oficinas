<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    protected $listeners = ['delete-user' => 'deleteUser'];

    protected static function getAssetBaseUrl()
    {
        return config('app.url');
    }

    protected function setUp(): void
    {
        $this->setTableAttributes([
            'data-asset-prefix' => url('/vendor/livewire-tables')
        ]);
    }
    
    public function query()
    {
        return User::with('roles');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('name', 'asc');
        $this->setPerPageAccepted([10, 25, 50]);
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->hideIf(true),

            Column::make('Nombre', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Roles')
                ->label(fn($row) => $row->roles->isEmpty()
                    ? 'Sin roles'
                    : $row->roles->pluck('name')->join(', ')),

            Column::make('Acciones')
                ->label(
                    fn($user) => view('livewire.users.actions', ['user' => $user])
                ),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Rol')
                ->options([
                    '' => 'Todos',
                    'ADMINISTRADOR' => 'Administradores',
                    'Supervisor Oficinas' => 'Supervisores',
                ])
                ->filter(function ($builder, string $value) {
                    $builder->whereHas('roles', fn($query) => $query->where('name', $value));
                }),
        ];
    }

    public function deleteUser($userId)
    {
        if ($userId == 1) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'No puedes eliminar al administrador principal'
            ]);
            return;
        }
        
        try {
            User::findOrFail($userId)->delete();
            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Usuario eliminado correctamente'
            );
        } catch (\Exception $e) {
            $this->dispatch(
                'notify',
                type: 'error',
                message: 'Error al eliminar usuario: ' . $e->getMessage()
            );
        }
    }
}
