<?php

namespace App\Livewire\Sales;

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
use App\Models\Sale;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class ListSales extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Sale::query())
            ->columns([
                TextColumn::make('id')->label('Sale ID')->searchable()->sortable(),
                TextColumn::make('customer.name')->label('Customer')->searchable()->sortable(),
                TextColumn::make('paymentMethod.name')->label('Payment Method')->searchable()->sortable(),
                TextColumn::make('total_amount')->label('Total Amount')->money('MYR')->searchable()->sortable(),
                TextColumn::make('paid_amount')->label('Paid Amount')->money('MYR')->searchable()->sortable(),
                TextColumn::make('discount')->label('Discount')->money('MYR')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                /* Action::make('edit')
                    ->url(fn (Sale $record): string => route('sale.update', ['record' => $record->id]))
                    ->label('Edit'), */
                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Sale $record) => $record->delete())
                    ->successNotificationTitle('Sale deleted successfully'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.sales.list-sales');
    }
}
