<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;

new
#[Layout('layouts::kfz')]
class extends Component
{
    public string $order_number;
    public  $order;
    public $birthday;

    public function mount($number)
    {
        $this->order_number = $number;
        $this->order = Order::where('number', $number)
             ->first();

        if ($this->order)
        {
            $this->birthday = Carbon::create($this->order->birthday)->format('d.m.Y');
        }
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
                <td>Birthday</td><td>{{$this->birthday}}</td>
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
        &nbsp; - &nbsp;
        <a href="{{route('kfz.customer.orders', ['number' => $order->Customer->number])}}">Customer</a>
        &nbsp; - &nbsp;
        <a href="{{route('home')}}">Home</a>
    @else
        <h1>Order Nr. {{$order_number}} not found</h1>
        <br>
        <a href="{{route('kfz.order.search')}}">Search order</a>
        &nbsp; - &nbsp;
        <a href="{{route('home')}}">Home</a>
    @endif
</div>
