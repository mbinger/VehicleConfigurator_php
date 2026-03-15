<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts::kfz')]
class extends Component
{
    public string $order_number = "";

    public function search()
    {
        $this->validate([
            'order_number' => 'required'
        ]);

        $this->redirectRoute('kfz.order.details', ['number' => $this->order_number]);
    }
};
?>

    <form wire:submit="search">
        <label>
            Order number
            <input type="text" wire:model="order_number">
            @error('order_number') <span style="color: red;">{{ $message }}</span> @enderror
        </label>
        <br>
        <button type="submit">Search</button>
        &nbsp; - &nbsp;
        <a href="{{route('home')}}">Cancel</a>
    </form>
