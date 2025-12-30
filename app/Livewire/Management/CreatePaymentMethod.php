<?php

namespace App\Livewire\Management;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\PaymentMethod;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class CreatePaymentMethod extends Component implements HasActions, HasSchemas
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
                    ->description('Create a new payment method by filling out the details below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Payment Method Name')
                            ->required(),

                        TextInput::make('description')
                            ->label('Description')
                            ->nullable(),
                    ]),
            ])
            ->statePath('data')
            ->model(PaymentMethod::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = PaymentMethod::create($data);

        $this->form->model($record)->saveRelationships();
        $this->redirectRoute('payment.method.index');
        Notification::make()
            ->title('Payment Method created successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-payment-method');
    }
}
