<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{Customer,Item,PaymentMethod};
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;

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
        return collect($this->cart)->sum(function($cartItem){
            $cartItem['price'] * $cartItem['quantity'];
        });
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

    public function render()
    {
        return view('livewire.p-o-s');
    }
}
