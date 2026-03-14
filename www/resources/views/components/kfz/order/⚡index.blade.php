<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <a href="{{route('kfz.order.create')}}">Create order</a>
    <br><br>
    <a href="{{route('kfz.order.search')}}">Search order</a>
    <br><br>
    <a href="{{route('kfz.customer.search')}}">Search customer</a>
</div>
