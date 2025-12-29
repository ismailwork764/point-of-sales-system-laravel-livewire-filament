<?php

namespace App\Livewire\Management;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\User;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class EditUser extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public User $record;

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
                    ->description('Update the details of the item below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                       TextInput::make('password')
                            ->password()
                            ->revealable(),
                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'staff' => 'Staff',
                                'cashier' => 'Cashier',
                            ])
                            ->nullable(),

                    ]),

            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->record->update($data);
        Notification::make()
            ->title('User updated successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.edit-user');
    }
}
