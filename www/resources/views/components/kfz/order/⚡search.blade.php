<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Order;
use App\Kfz\Text;

new
#[Layout('layouts::kfz')]
class extends Component
{
    public string $order_number = "";

    public function search()
    {
        $this->validate([
            'order_number' => 'required'
        ], [
            'order_number.required' => __("required")
        ]);

        if (Order::where('number', $this->order_number)->select('id')->first())
        {
            $this->redirectRoute('kfz.order.edit', ['number' => $this->order_number]);
        }else
        {
            $this->js("showNotFoundDialog();");
        }
    }
};
?>

<div class="container-fluid d-flex h-100 justify-content-center align-items-center p-0">
    <div class="row bg-white shadow-sm">
        <div class="col border rounded p-4">
            <form wire:submit="search">

                <div class="row">
                    <div class="col">
                        <h3 class="text-center">{{__("Search order")}}</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">{{__("Number")}}</label>
                            @error('order_number') <span style="color: red;">{{ $message }}</span> @enderror
                        <input class="form-control" type="text" wire:model="order_number">
                    </div>
                </div>

                <div class="row">
                    <div class="col pt-4">
                        <button class="btn btn-primary" type="submit">{{__("Search")}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-none">
        <div id="dialog-notfound">
            <p>{{__("Order not found")}}</p>
        </div>
    </div>
</div>

<x-slot name="script">
    <script>
        function showNotFoundDialog()
        {
            $('#dialog-notfound').dialog({
                buttons: [
                    {
                        text: '{{__("OK")}}',
                        class: 'btn btn-primary',
                        click: function() {
                            $(this).dialog( "close" );
                        }
                    }
                ]
            });
        }

    </script>
</x-slot>