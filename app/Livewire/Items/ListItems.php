<?php

namespace App\Livewire\Items;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Item;
use Livewire\Component;
use Filament\Actions\Action;

class ListItems extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Item::query())
            ->columns([
                TextColumn::make('name')->label('Item Name')->searchable()->sortable(),
                TextColumn::make('sku')->label('Sku')->searchable()->sortable(),
                TextColumn::make('price')->label('Price')->money('MYR')->searchable()->sortable(),
                TextColumn::make('status')->label('Status')->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'warning',
                            })->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('create')
                    ->url(route('item.create'))
                    ->label('Create Item')
                    ->color('primary'),
            ])
            ->recordActions([

                Action::make('edit')
                    ->url(fn (Item $record): string => route('item.update', ['record' => $record->id]))
                    ->label('Edit'),

                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Item $record) => $record->delete())
                    ->successNotificationTitle('Item deleted successfully'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-items');
    }
}
