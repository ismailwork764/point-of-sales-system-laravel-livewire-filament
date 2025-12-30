<?php

namespace App\Livewire\Management;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class ListUsers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => User::query())
            ->columns([
                TextColumn::make('name')->label('User Name')->searchable()->sortable(),
                TextColumn::make('email')->label('Email')->searchable()->sortable(),
                TextColumn::make('role')->label('Role')->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'admin' => 'success',
                                'cashier' => 'primary',
                                'staff' => 'warning',
                            })->searchable()->sortable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->url(route('user.create'))
                    ->label('Create User')
                    ->color('primary'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (User $record): string => route('user.update', ['record' => $record->id]))
                    ->label('Edit'),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (User $record) => $record->delete())
                    ->successNotificationTitle('User deleted successfully'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-users');
    }
}
