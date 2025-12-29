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
use App\Models\PaymentMethod;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;

class ListPaymentMethods extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => PaymentMethod::query())
            ->columns([
                TextColumn::make('name')->label('Payment Method')->searchable()->sortable(),
                TextColumn::make('description')->label('Description')->limit(50)->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                Action::make('edit')
                    ->url(fn (PaymentMethod $record): string => route('payment.method.update', ['record' => $record->id]))
                    ->label('Edit'),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (PaymentMethod $record) => $record->delete())
                    ->successNotificationTitle('Payment method deleted successfully'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.management.list-payment-methods');
    }
}
