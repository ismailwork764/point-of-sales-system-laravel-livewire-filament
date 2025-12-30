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

class CreateUser extends Component implements HasActions, HasSchemas
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
                    ->description('Fill in the details of the user below.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('User Name')
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->unique()
                            ->required(),

                        Select::make('role')
                            ->label('Role')
                            ->options([
                                'admin' => 'Admin',
                                'cashier' => 'Cashier',
                                'staff' => 'Staff',
                            ])
                            ->required(),

                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->required(),
                    ]),
            ])
            ->statePath('data')
            ->model(User::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = User::create($data);

        $this->form->model($record)->saveRelationships();
        $this->redirectRoute('users.index');
        Notification::make()
            ->title('User created successfully')
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('livewire.management.create-user');
    }
}
