<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Order;
use App\Kfz\Text;

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
            $this->birthdayStr = Text::formatDate($this->customer->birthday);
        }
        $this->loadOrders();
    }

    public function loadOrders()
    {
        if ($this->customer)
        {
            $this->orders = $this->customer->Orders->map(function ($order){
                return [
                    'number' => $order->number,
                    'car' => $order->Car->Vendor->name . ' ' . $order->Car->name,
                    'price' => $order->price,
                    'date' => Text::formatDate($order->created_at)
                ];
            });
        }
    }

    public function deleteCustomer()
    {
        $this->customer->delete();
        $this->redirectRoute('home');
    }

    public function getConfirmDeleteOrderText($orderNumber)
    {
        $order = Order::where('number', $orderNumber)->firstOrFail();
        $car = $order->Car->Vendor->name . ' ' . $order->Car->name;
        $price = $order->price;
        $date = Text::formatDate($order->created_at);
        return "Are you sure to cancel order {$car} for {$price}$ from {$date}?";
    }

    public function deleteOrder($orderNumber)
    {
        $order = Order::where('number', $orderNumber)->firstOrFail();
        $order->delete();
        $this->loadOrders();
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

    <div @class([
        'row',
        'invisible' => $orders->isNotEmpty()
    ])>
        <div class="col">None</div>
    </div>

    <div @class([
        'row',
        'invisible' => $orders->isEmpty()
    ])>  
        <div class="col">  
            <table class="table">
              <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Car</th>
                        <th scope="col">Price €</th>
                        <th scope="col">Date</th>
                        <th></th>
                    </tr>
             </thead>
             <tbody>
    @foreach($orders as $order)
                    <tr>
                        <td scope="row">
                            {{$order['number']}}
                        </td>
                        <td>
                            {{$order['car']}}</a>
                        </td>
                        <td>{{$order['price']}}</td>
                        <td>{{$order['date']}}</td>
                        <td>

                            <div class="btn-toolbar" role="toolbar">
                                <div class="btn-group me-2" role="group">
                                    <a class="btn btn-sm btn-outline-secondary py-0 px-1 border-0" href="{{route('kfz.order.edit', ['number' => $order['number']])}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"></path>
                                        </svg>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 border-0" onclick="confirmDeleteOrder('{{$order['number']}}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                            <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"></path>
                                        </svg>
                                    </button>
                                    <button id="delete-order-trigger-{{$order['number']}}" wire:click="deleteOrder('{{$order['number']}}')" class="d-none"></button>
                                </div>
                            </div>
                        </td>
                    </tr>
    @endforeach
            </tbody>
            </table>
        </div>  
    </div>

        <div class="row">
            <div class="col pt-4">
                <a class="btn btn-primary" href="{{route('kfz.order.create', ['customer_number' => $customer->number])}}">Create order</a>
                <button id="btn-delete" class="btn btn-danger">Delete customer</button>
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

    <div class="d-none">
        <div id="dialog-confirm" title="Are you sure?">
        <p>Do you want to delete the customer and cancel all its orders?</p>
        </div>
        <button id="dialog-confirm-trigger" wire:click="deleteCustomer" class="d-none"></button>
    </div>

    <div class="d-none">
        <div id="dialog-delete-order" title="Are you sure?">
        <p id='dialog-delete-order-text'></p>
        </div>
    </div>

    </div>
  </div>
</div>

<x-slot name="head">
    <script src="/js/jquery-4.0.0.min.js"></script>

    <script src="/js/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="/css/jquery-ui.css" />
    <link rel="stylesheet" href="/css/jquery-ui.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui.structure.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui.theme.min.css" />
    <link rel="stylesheet" href="/css/jquery-ui.fix.css" />
</x-slot>

<x-slot name="script">
    <script>
        $('#btn-delete').on('click', function(e) 
        {
            $( "#dialog-confirm" ).dialog(
            {
                classes: {
                   "ui-dialog": "no-close"
                },
                resizable: false,
                height: "auto",
                width: 400,
                modal: true,
                buttons: 
                [
                    {
                        text: "Cancel",
                        class: 'btn btn-secondary',
                        click: function() 
                        {
                            $(this).dialog( "close" );
                        }
                    },                   
                    {
                        text: "Delete",
                        class: 'btn btn-danger',
                        click: function() 
                        {
                            $('#load-indicator').removeClass('d-none');
                            $('#dialog-confirm-trigger').click();
                        }
                    }
                ]
            });
        });

        async function confirmDeleteOrder(orderNumber)
        {
            let text = await @this.getConfirmDeleteOrderText(orderNumber);
            $('#dialog-delete-order-text').html(text);
            $("#dialog-delete-order" ).dialog(
                        {
                            classes: {
                            "ui-dialog": "no-close"
                            },
                            resizable: false,
                            height: "auto",
                            width: 400,
                            modal: true,
                            buttons: 
                            [
                                {
                                    text: "Cancel",
                                    class: 'btn btn-secondary',
                                    click: function() 
                                    {
                                        $(this).dialog("close");
                                    }
                                },                   
                                {
                                    text: "Delete",
                                    class: 'btn btn-danger',
                                    click: function() 
                                    {
                                        $('#load-indicator').removeClass('d-none');
                                        $(this).dialog("close");
                                        $('#delete-order-trigger-'+orderNumber).click();
                                        $('#load-indicator').addClass('d-none');
                                    }
                                }
                            ]
                        });
        }
    </script>
</x-slot>