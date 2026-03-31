<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Customer;
use App\Kfz\Text;

new
#[Layout('layouts::kfz')]
class extends Component
{
    public string $firstName = "";
    public string $lastName = "";
    public string $birthDate = "";
    public $birthDateTyped = null;

    public function search()
    {
        $this->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'birthDate' => 'required'
        ], [
            'firstName.required' => __("required"),
            'lastName.required' => __("required"),
            'birthDate.required' => __("required")
        ]);

        try
        {
            $this->birthDateTyped = Text::parseDate($this->birthDate);
        }
        catch (\Exception $e)
        {
            $this->addError('birthDate', __("Should be yyyy-mm-dd"));
            return;
        }

        $customer = Customer::whereRaw('LOWER(last_name) LIKE LOWER(?)', [$this->lastName])
            ->whereRaw('LOWER(first_name) LIKE LOWER(?)', [$this->firstName])
            ->whereDate('birthday', $this->birthDateTyped)
            ->select('number')
            ->first();

        if ($customer)
        {
            $this->redirectRoute('kfz.customer.orders', ['number' => $customer->number]);
        }
        else
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
                        <h3 class="text-center">{{__("Search customer")}}</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">{{__("First name")}}</label>
                            @error('firstName') <span style="color: red;">{{ $message }}</span> @enderror
                        <input class="form-control" type="text" wire:model="firstName">
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">{{__("Last name")}}</label>
                            @error('lastName') <span style="color: red;">{{ $message }}</span> @enderror
                        <input class="form-control" type="text" wire:model="lastName">
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">{{__("Birth date")}}</label>
                            @error('birthDate') <span style="color: red;">{{ $message }}</span> @enderror
                        <input id="birthDate" class="form-control" type="text" wire:model="birthDate">
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
            <p>{{__("Customer not found")}}</p>
        </div>
    </div>

</div>

<x-slot name="script">
    <script>
        $('#birthDate').datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: "c-100:c"
        }).on('click', function(e) {
            e.preventDefault();
            $(this).attr("autocomplete", "off");
        }).on('change', function (e) { @this.set('birthDate', e.target.value); });

        function showNotFoundDialog()
        {
            $('#dialog-notfound').dialog({
                buttons: [
                    {
                        text: 'OK',
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
