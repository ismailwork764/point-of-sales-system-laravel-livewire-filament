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
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;


class EditPaymentMethod extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public PaymentMethod $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Payment Method Details')
                    ->description('Update the details of the payment method below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Payment Method Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('description')
                            ->label('Description')
                            ->maxLength(500),
                    ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->record->update($data);
        Notification::make()
            ->title('Payment method updated successfully')
            ->success()->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-payment-method');
    }
}
