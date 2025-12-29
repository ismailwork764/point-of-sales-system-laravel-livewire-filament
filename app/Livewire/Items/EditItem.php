<?php

namespace App\Livewire\Items;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\TextInput;
use App\Models\Item;
use Filament\Notifications\Notification;

class EditItem extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    public Item $record;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Item Details')
                    ->description('Update the details of the item below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Item Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique()
                            ->maxLength(100),
                        TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('MYR')
                            ->minValue(0),
                        ToggleButtons::make('Status')
                            ->label('Is Active ?')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->grouped()
                    ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);

        Notification::make()
            ->title('Item updated successfully')
            ->success()->send();
    }

    public function render(): View
    {
        return view('livewire.items.edit-item');
    }
}
