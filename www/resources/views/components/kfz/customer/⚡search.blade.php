<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Customer;
use Carbon\Carbon;
use \App\Kfz\Text;

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
            'firstName.required' => Text::REQUIRED,
            'lastName.required' => Text::REQUIRED,
            'birthDate.required' => Text::REQUIRED
        ]);

        try
        {
            $this->birthDateTyped = Carbon::createFromFormat('d.m.Y', $this->birthDate);
        }
        catch (\Exception $e)
        {
            $this->addError('birthDate', 'Should be dd.mm.yyyy');
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
                        <h3 class="text-center">Search customer</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">First name</label>
                            @error('firstName') <span style="color: red;">{{ $message }}</span> @enderror
                        <input class="form-control" type="text" wire:model="firstName">
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Last name</label>
                            @error('lastName') <span style="color: red;">{{ $message }}</span> @enderror
                        <input class="form-control" type="text" wire:model="lastName">
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Birth date</label>
                            @error('birthDate') <span style="color: red;">{{ $message }}</span> @enderror
                        <input id="birthDate" class="form-control" type="text" wire:model="birthDate">
                    </div>
                </div>

                <div class="row">
                    <div class="col pt-4">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="d-none">
        <div id="dialog-notfound" title="Not found">
            <p>Customer not found</p>
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
        $('#birthDate').datepicker({
            dateFormat: "dd.mm.yy",
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
