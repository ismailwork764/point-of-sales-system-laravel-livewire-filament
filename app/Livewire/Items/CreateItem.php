<?php

namespace App\Livewire\Items;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use App\Models\Item;
use Livewire\Component;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Forms\Components\ToggleButtons;

class CreateItem extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('Fill in the details of the item below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Item Name')
                            ->required(),

                        TextInput::make('sku')
                            ->label('SKU')
                            ->unique()
                            ->required(),

                        TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->required(),

                        ToggleButtons::make('Status')
                            ->label('Is Active ?')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->required()
                            ->grouped()
                    ]),
            ])
            ->statePath('data')
            ->model(Item::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Item::create($data);

        $this->form->model($record)->saveRelationships();
        $this->redirectRoute('items.index');
        Notification::make()
            ->title('Item created successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.items.create-item');
    }
}
