<?php

namespace App\Livewire\Items;

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
use App\Models\Inventory;
use Livewire\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class ListInventories extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Inventory::query())
            ->columns([
                TextColumn::make('item.name')->label('Item')->searchable()->sortable(),
                TextColumn::make('quantity')->label('Quantity')->searchable()->sortable()->badge(),
                TextColumn::make('created_at')->label('Added On')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->url(route('inventory.create'))
                    ->label('Create Inventory')
                    ->color('primary'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (Inventory $record): string => route('inventory.update', ['record' => $record->id]))
                    ->label('Edit'),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Inventory $record) => $record->delete())
                    ->successNotificationTitle('Inventory deleted successfully'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-inventories');
    }
}
