<?php

use Livewire\Component;
use App\Models\Customer;

new class extends Component
{
    public $customer;

    public function mount($number)
    {
        $this->customer = Customer::where('number', $number)->first();
    }
};
?>

<div>
    @if ($this->customer)
        <table>
            <tr>
                <td>Customer</td>
            </tr>
            <tr>
                <td>First name</td>
                <td>{{$customer->first_name}}</td>
            </tr>
            <tr>
                <td>Last name</td>
                <td>{{$customer->last_name}}</td>
            </tr>
            <tr>
                <td>Birth date</td>
                <td>{{$customer->birthday}}</td>
            </tr>
            <tr>
                <td>Orders</td>
            </tr>
            @if($customer->Orders->isNotEmpty())
                @foreach($customer->Orders as $order)
                    <tr>
                        <td>
                            <a href="{{route('kfz.order.details', ['number' => $order->number])}}">{{$order->Car->Vendor->name}} {{$order->Car->name}}</a>
                        </td>
                        <td>{{$order->created_at}}</td>
                        <td>{{$order->price}}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td>None</td>
                </tr>
            @endif
        </table>

    <br>
    <br>
    <a href="{{route('kfz.order.create', ['customer_number' => $customer->number])}}">Create order</a>
    &nbsp; - &nbsp;
    <a href="{{route('home')}}">Home</a>

    @else
        <h3>Customer not found</h3>
        <br>
        <a href="{{route('kfz.customer.search')}}">Search customer</a>
        &nbsp; - &nbsp;
        <a href="{{route('home')}}">Home</a>
    @endif
</div>
