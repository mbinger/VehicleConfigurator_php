<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Order;

new
#[Layout('layouts::kfz')]
class extends Component
{
    public string $order_number;

    public function mount($number)
    {
        $this->order_number = $number;
    }

    public function delete()
    {
        Order::where('number', $this->order_number)
            ->firstOrFail()
            ->delete();

        $this->redirectRoute('home');
    }

    public function cancel()
    {
        $this->redirectRoute('kfz.order.details', ['number' => $this->order_number]);
    }
};
?>
<div>
    <h1>Are you sure to delete Order Nr. {{$order_number}} ?</h1>
    <br>
    <br>
    <button wire:click="delete">Delete</button>
    &nbsp; - &nbsp;
    <button wire:click="cancel">Cancel</button>
</div>
