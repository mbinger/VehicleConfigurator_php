<?php

use Livewire\Component;
use \App\Models\Order;

new class extends Component
{
    public string $order_number;
    public  $order;

    public function mount($number)
    {
        $this->order_number = $number;
        $this->order = Order::where('number', $number)
             ->first();
    }
};
?>

<div>
    @if ($order)
        <h1>Order Nr. {{$order_number}} </h1>

        <table>
            <tr>
                <td>Customer</td>
            </tr>
            <tr>
                <td>First name</td><td>{{$order->Customer->first_name}}</td>
            </tr>
            <tr>
                <td>Last name</td><td>{{$order->Customer->last_name}}</td>
            </tr>
            <tr>
                <td>Birthday</td><td>{{$order->Customer->birthday}}</td>
            </tr>
            <tr>
                <td>Car</td>
            </tr>
            <tr>
                <td>Model</td><td>{{$order->Car->Vendor->name}} {{$order->Car->name}}</td>
            </tr>
            <tr>
                <td>Motor</td><td>{{$order->Motor->FuelType->name}} {{$order->Motor->name}}</td>
            </tr>
            <tr>
                <td>Options</td>
            </tr>
            @if ($order->OrderOptions->isNotEmpty())
                @foreach($order->OrderOptions as $opt)
                    <tr>
                        <td>{{$opt->Option->name}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>none</td>
                </tr>
            @endif
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Total</td><td>{{$order->price}} €</td>
            </tr>
        </table>
        <br>
        <br>
        <a href="{{route('kfz.order.edit', ['number' => $order_number])}}">Edit order</a>
        &nbsp; - &nbsp;
        <a href="{{route('kfz.order.delete', ['number' => $order_number])}}">Delete order</a>
    @else
        <h1>Order Nr. {{$order_number}} not found</h1>
    @endif
</div>
