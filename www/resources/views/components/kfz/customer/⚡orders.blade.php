<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Customer;
use Carbon\Carbon;

new 
#[Layout('layouts::kfz')]
class extends Component
{
    public $customer;
    public $birthdayStr;
    public $orders;

    public function mount($number)
    {
        $this->customer = Customer::where('number', $number)->first();
        if ($this->customer)
        {
            $this->birthdayStr = $this->formatDate($this->customer->birthday);

            $this->orders = $this->customer->Orders->map(function ($order){
                return [
                    'number' => $order->number,
                    'car' => $order->Car->Vendor->name . ' ' . $order->Car->name,
                    'price' => $order->price,
                    'date' => $this->formatDate($order->created_at)
                ];
            });
        }
    }

    public function formatDate($date)
    {
        return Carbon::create($date)->format('d.m.Y');
    }
};
?>

<div class="container-fluid d-flex h-100 justify-content-center align-items-center p-0">
    <div class="row bg-white shadow-sm">
        <div class="col border rounded p-4">

    @if ($this->customer)

       <div class="row">
            <div class="col">
                <h3>Customer orders</h3>
            </div>   
        </div>

        <div class="row">
            <div class="col"><strong>Customer</strong></div>
        </div>

        <div class="row">
            <div class="col">First name</div>
            <div class="col">{{$customer->first_name}}</div>
        </div>

        <div class="row">
            <div class="col">Last name</div>
            <div class="col">{{$customer->last_name}}</div>
        </div>

        <div class="row">
            <div class="col">Birthday</div>
            <div class="col">{{$birthdayStr}}</div>
        </div>

        <div class="row pt-4">
            <div class="col"><strong>Orders</strong></div>
        </div>


 @if($orders->isNotEmpty())
    <div class="row">  
        <div class="col">  
            <table class="table">
              <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Car</th>
                        <th scope="col">Price €</th>
                        <th scope="col">Date</th>
                    </tr>
             </thead>
             <tbody>
    @foreach($orders as $order)
                    <tr>
                        <th scope="row">
                            <a href="{{route('kfz.order.details', ['number' => $order['number']])}}">{{$order['number']}}</a>
                        </th>
                        <td>
                            {{$order['car']}}</a>
                        </td>
                        <td>{{$order['price']}}</td>
                        <td>{{$order['date']}}</td>
                    </tr>
    @endforeach
            </tbody>
            </table>
        </div>  
    <div>
@else
    <div class="row">
        <div class="col">None</div>
    </div>
@endif

        <div class="row">
            <div class="col pt-4">
                <a class="btn btn-primary" href="{{route('kfz.order.create', ['customer_number' => $customer->number])}}">Create order</a>
            </div>
        </div>

    @else
        <div class="row">
            <div class="col">
                <div class="alert alert-warning" role="alert">
                    <strong>Customer not found</strong>
                </div>
            </div>     
        </div>

        <div class="row">
            <div class="col pt-4">
                <a class="btn btn-primary" href="{{route('kfz.customer.search')}}">New search</a>
            </div>
        </div>

    @endif

        </div>
    </div>
</div>
