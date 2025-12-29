<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class EditCustomer extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Customer $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->description('Update the details of the customer below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),

                        TextInput::make('phone')
                            ->label('Phone')
                            ->required(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);
        Notification::make()
            ->title('Customer updated successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.customer.edit-customer');
    }
}
