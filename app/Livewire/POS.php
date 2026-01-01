<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{Customer,Item,PaymentMethod,Inventory,Sale,SalesItem};
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Filament\Notifications\Notification;
use DB;

class POS extends Component
{
    public $items;
    public $customers;
    public $paymentMethods;
    public $search = '';
    public $cart = [];

    public $customer_id;
    public $payment_method_id;
    public $paid_amount = 0;
    public $discount_amount = 0;


    public function mount(): void
    {
        $this->items = Item::whereHas('inventory', function($builder){
            $builder->where('quantity', '>', 0);
        })->with('inventory')->where('status', 'active')->get();
        $this->customers = Customer::all();
        $this->paymentMethods = PaymentMethod::all();

    }
    #[Computed]
    public function filteredItems(){
        if(empty($this->search)){
            return $this->items;
        }

        return $this->items->filter(function($item){
            return str_contains(strtolower($item->name), strtolower($this->search)) || str_contains(strtolower($item->sku), strtolower($this->search));
        });
    }

    #[Computed]
    public function subTotal(){
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    #[Computed]
    public function tax(){
        return ($this->subTotal * 15) / 100;
    }

    #[Computed]
    public function totalBeforeDiscount(){
        return $this->subTotal + $this->tax;
    }

    #[Computed]
    public function total(){
        return $this->totalBeforeDiscount - $this->discount_amount;
    }

    #[Computed]
    public function change(){
        if($this->paid_amount <= $this->total){
            return 0;
        }else{
            return $this->paid_amount - $this->total;
        }
    }

    public function addToCart($itemId){
        $item = Item::find($itemId);
        $inventory = Inventory::where('item_id', $itemId)->first();
        if(!$inventory || $inventory->quantity <= 0){
            Notification::make()
                ->title('No stock for '.$item->name)
                ->danger()
                ->send();
            return;
        }

        if(isset($this->cart[$itemId])){
            $currentQuantity = $this->cart[$itemId]['quantity'];
            if($currentQuantity >= $inventory->quantity){
                Notification::make()
                    ->title('Cannot add more of '.$item->name.'. Only '.$inventory->quantity.' left in stock.')
                    ->danger()
                    ->send();
                return;
            }else{
                $this->cart[$itemId]['quantity'] += 1;
                return;
            }
        }else{
            $this->cart[$itemId] = [
                'id' => $item->id,
                'name' => $item->name,
                'sku' => $item->sku,
                'price' => $item->price,
                'quantity' => 1,
            ];
            return;
        }

    }

    public function removeFromCart($itemId){
        $cartItemKey = array_search($itemId, array_column($this->cart, 'id'));
        if($cartItemKey !== false){
            unset($this->cart[$cartItemKey]);
            // Reindex the array
            $this->cart = array_values($this->cart);
        }
    }

    public function checkout(){

        if(empty($this->cart)){
            Notification::make()
                ->title('Cart is empty. Add items before checkout.')
                ->danger()
                ->send();
            return;
        }
        if($this->paid_amount < $this->total){
            Notification::make()
                ->title('Paid amount is less than total amount.')
                ->danger()
                ->send();
            return;
        }

        DB::beginTransaction();
        try{
            $sale = Sale::create([
                'customer_id' => $this->customer_id,
                'payment_method_id' => $this->payment_method_id,
                'total_amount' => $this->total,
                'paid_amount' => $this->paid_amount,
                'discount_amount' => $this->discount_amount,
            ]);

            foreach($this->cart as $cartItem){
                SalesItem::create([
                    'sale_id' => $sale->id,
                    'item_id' => $cartItem['id'],
                    'quantity' => $cartItem['quantity'],
                    'price' => $cartItem['price'],
                    'total' => $cartItem['price'] * $cartItem['quantity'],
                ]);

                // Deduct inventory
                $inventory = Inventory::where('item_id', $cartItem['id'])->first();
                if($inventory){
                    if($inventory->quantity < $cartItem['quantity']){
                        throw new \Exception('Insufficient stock for item: '.$cartItem['name']);
                    }
                    $inventory->quantity -= $cartItem['quantity'];
                    $inventory->save();
                }else{
                    throw new \Exception('No inventory record found for item: '.$cartItem['name']);
                }
            }
            DB::commit();

            $this->cart = [];
            $this->paid_amount = 0;
            $this->discount_amount = 0;
            $this->customer_id = null;
            $this->payment_method_id = null;
            Notification::make()
                ->title('Checkout successful!')
                ->success()
                ->send();

        }catch(\Exception $e){
            DB::rollBack();
            Notification::make()
                ->title('Checkout failed: '.$e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function updateQuantity($itemId, $quantity){
        if(isset($this->cart[$itemId])){
            $quantity = max(1, intval($quantity));
            $inventory = Inventory::where('item_id', $itemId)->first();
            if($quantity > $inventory->quantity){
                Notification::make()
                    ->title('Cannot set quantity. Only '.$inventory->quantity.' left in stock.')
                    ->danger()
                    ->send();
                $this->cart[$itemId]['quantity'] = $inventory->quantity;
            }else{
                $this->cart[$itemId]['quantity'] = $quantity;
            }

        }
    }


    public function render()
    {
        return view('livewire.p-o-s');
    }
}
