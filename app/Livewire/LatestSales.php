<?php

namespace App\Livewire;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Sale;
use Filament\Tables\Columns\TextColumn;

class LatestSales extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Sale::query())
            ->columns([
                Textcolumn::make('id')->label('Sale ID')->sortable(),
                Textcolumn::make('customer.name')->label('Customer')->sortable()->searchable(),
                Textcolumn::make('total_amount')->label('Total Amount')->sortable(),
                Textcolumn::make('created_at')->label('Date')->dateTime()->sortable(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
