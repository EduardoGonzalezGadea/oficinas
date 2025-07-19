<div>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Lista de Usuarios') }}
            </h2>
        </header>

        <div class="mt-6">
            <livewire-tables::table
                :columns="$this->columns()"
                :filters="$this->filters()"
                :rows="$this->rows()"
                :primary-key="$this->getPrimaryKey()"
            />
        </div>
    </div>
</div>