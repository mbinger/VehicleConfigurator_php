<?php

use Livewire\Component;

new class extends Component
{
    public string $order_number;

    public function mount($number)
    {
        $this->order_number = $number;
    }
};
?>

<div>
    <h1>Edit Order Nr. {{$order_number}} ?</h1>
</div>
