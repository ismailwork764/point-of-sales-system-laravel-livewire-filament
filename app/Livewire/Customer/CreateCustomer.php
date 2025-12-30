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
use Filament\Actions\Action;

class CreateCustomer extends Component implements HasActions, HasSchemas
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
                Section::make()->description('Fill in the details of the customer below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Customer Name')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->unique()
                            ->required(),

                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->unique()
                            ->required(),
                    ]),
            ])
            ->statePath('data')
            ->model(Customer::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Customer::create($data);

        $this->form->model($record)->saveRelationships();

        $this->redirectRoute('customers.index');
        Notification::make()
            ->title('Customer created successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.customer.create-customer');
    }
}
