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
            $this->birthday = Carbon::create($this->order->Customer->birthday)->format('d.m.Y');
        }
    }

    public function deleteOrder()
    {
        Order::where('number', $this->order_number)
            ->firstOrFail()
            ->delete();

        $this->redirectRoute('kfz.customer.orders', ['number' => $this->order->Customer->number]);
    }
};
?>
<div>

<div class="container-fluid d-flex h-100 justify-content-center align-items-center p-0">
    <div class="row bg-white shadow-sm">
        <div class="col border rounded p-4">

    @if ($order)
       <div class="row">
            <h3>Order details</h3>
        </div>
       
        <div class="row">
            <div class="col">Number</div>
        </div>

        <div class="row">
            <div class="col"><code>{{$order_number}}</code></div>
        </div>

        <div class="row">
            <div class="col">Customer</div>
            <div class="col">
                <a href="{{route('kfz.customer.orders', ['number' => $order->Customer->number])}}">{{$order->Customer->first_name}} {{$order->Customer->last_name}} {{$this->birthday}}</a>
            </div>
        </div>

        <div class="row">
            <div class="col"><strong>Car</strong></div>
        </div>

        <div class="row">
            <div class="col">Model</div>
            <div class="col">{{$order->Car->Vendor->name}} {{$order->Car->name}}</div>
        </div>

        <div class="row">
            <div class="col">Motor</div>
            <div class="col"><span>{{$order->Motor->FuelType->name}} {{$order->Motor->name}}</span></div>
        </div>

        <div class="row">
            <div class="col"><strong>Options</strong></div>
        </div>

        <div class="row">
            <div class="col">

            @if ($order->OrderOptions->isNotEmpty())
                    @foreach($order->OrderOptions as $opt)
                        <span class="badge text-bg-success">{{$opt->Option->name}}</span>
                    @endforeach
                @else
                    none
                @endif
            
            </div>
        </div>

        <div class="row">
            <div class="col pt-4">
                <a class="btn btn-primary" href="{{route('kfz.order.edit', ['number' => $order_number])}}">Edit order</a>
                <a id="btn-delete" class="btn btn-danger">Delete order</a>
            </div>
        </div>
    @else

    <div class="row">
        <div class="col">
            <div class="alert alert-warning" role="alert">
                <strong>Order Nr. <code>{{$order_number}}</code> not found</strong>
            </div>
        </div>     
    </div>

        <div class="row">
            <div class="col pt-4">
                <a class="btn btn-primary" href="{{route('kfz.order.search')}}">New search</a>
            </div>
        </div>

    @endif
</div>
</div>

<div class="d-none">
    <div id="dialog-confirm" title="Are you sure?">
    <p>The order will be permanently deleted and cannot be recovered</p>
    </div>
    <button id="dialog-confirm-trigger" wire:click="deleteOrder" class="d-none"></button>
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
                            $('#dialog-confirm-trigger').click();
                        }
                    }
                ]
            });
        });
    </script>
</x-slot>
