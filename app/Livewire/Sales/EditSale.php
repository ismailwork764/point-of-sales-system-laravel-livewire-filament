<?php

namespace App\Livewire\Sales;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Sale;

class EditSale extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Sale $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Sale Details')
                    ->description('Update the details of the sale below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('customer_name')
                            ->label('Customer Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->required()
                            ->numeric()
                            ->prefix('MYR')
                            ->minValue(0),
                        TextInput::make('paid_amount')
                            ->label('Paid Amount')
                            ->required()
                            ->numeric()
                            ->prefix('MYR')
                            ->minValue(0),
                        TextInput::make('discount')
                            ->label('Discount')
                            ->required()
                            ->numeric()
                            ->prefix('MYR')
                            ->minValue(0),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);
    }

    public function render(): View
    {
        return view('livewire.sales.edit-sale');
    }
}
