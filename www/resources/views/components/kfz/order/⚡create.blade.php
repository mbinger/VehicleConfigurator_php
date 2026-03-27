<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use Carbon\Carbon;
use \App\Models\Customer;
use \App\Models\Vendor;
use \App\Models\FuelType;
use \App\Models\Car;
use \App\Models\CarMotor;
use \App\Models\Motor;
use \App\Models\Option;
use \App\Models\Order;
use \App\Models\OrderOption;
use \App\Kfz\Text;

new
#[Layout('layouts::kfz')]
class extends Component
{
    public $customer_number;
    public string $firstName;
    public string $lastName;
    public string $birthDate = "";
    private $birthDateTyped;
    public $vendors;
    public $vendorId;
    public $cars;
    public $carId;
    public $carPrice;
    public $fuelTypes;
    public $fuelTypeId;
    public $motorIds;
    public $motors;
    public $motorId;
    public $motorPrice;
    public $color = "";
    public $options;
    public $selectedOptions = [];
    public $total = 0;
    private $orderNumber;


    public function __construct()
    {
        $this->vendors = Vendor::all()->pluck('name', 'id')->toArray();
        $this->options = Option::all()->select('name', 'price', 'id')->toArray();
    }

    public function mount($customer_number = null)
    {
        $this->$customer_number = $customer_number;
        if ($customer_number)
        {
            $customer = Customer::where('number', $customer_number)->firstOrFail();
            $this->firstName = $customer->first_name;
            $this->lastName = $customer->last_name;
            $this->birthDate = Carbon::create($customer->birthday)->format('d.m.Y');
        }
    }

    public function updateVendorId()
    {
        if ($this->vendorId)
        {
            $this->cars = Car::where('vendor_id', $this->vendorId)->pluck('name', 'id')->toArray();
        }
        else
        {
            $this->cars = null;
        }
        $this->carId = null;
        $this->fuelTypes = null;
        $this->fuelTypeId = null;
        $this->motorIds = null;
        $this->motors = null;
        $this->motorId = null;

        $this->calculateTotal();
    }

    public function updateCarId()
    {
        $this->fuelTypeId = null;
        $this->motorIds = null;
        $this->motors = null;
        $this->motorId = null;
        $this->motorIds = null;
        $this->fuelTypes = null;

        if ($this->carId)
        {
            $this->motorIds = CarMotor::where('car_id', $this->carId)->pluck('motor_id')->toArray();
            $fuelTypeIds = Motor::whereIn('id', $this->motorIds)->pluck('fuel_type_id')->toArray();
            $this->fuelTypes = FuelType::whereIn('id', $fuelTypeIds)->pluck('name', 'id')->toArray();

            $this->carPrice = Car::where('id', $this->carId)->first()->price;
        }

        $this->calculateTotal();
    }

    public function updateFuelTypeId()
    {
        if ($this->fuelTypeId)
        {
            $this->motors = Motor::whereIn('id', $this->motorIds)
                ->where('fuel_type_id', $this->fuelTypeId)
                ->pluck('name', 'id')
                ->toArray();
        }
        else
        {
            $this->motors = null;
        }
        $this->motorId = null;
        $this->calculateTotal();
    }

    public function updateMotorId()
    {
        if ($this->motorId)
        {
            $this->motorPrice = Motor::where('id', $this->motorId)->first()->price;
        }
        else
        {
            $this->motorPrice = null;
        }
        $this->calculateTotal();
    }

    public function onOptionChanged()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if ($this->carId && $this->motorId) {
            $optionsPrice = Option::whereIn('id', $this->selectedOptions)->sum('price');
            $this->total = $this->carPrice + $this->motorPrice + $optionsPrice;
        }
        else
        {
            $this->total = 0;
        }
        return $this->total;
    }

    public function save()
    {
        if ($this->customer_number)
        {
            $this->validate([
                'carId' => 'required',
                'motorId' => 'required',
                'color' => 'required|regex:/^#[0-9a-fA-F]{6}$/'
            ],[
                'carId.required' => Text::REQUIRED,
                'motorId.required' => Text::REQUIRED,
                'color.required' => Text::REQUIRED
            ]);
        }
        else
        {
            $this->validate([
                'firstName' => 'required|max:255',
                'lastName' => 'required|max:255',
                'birthDate' => 'required',
                'carId' => 'required',
                'motorId' => 'required',
                'color' => 'required|regex:/^#[0-9a-fA-F]{6}$/'
            ],[
                'firstName.required' => Text::REQUIRED,
                'lastName.required' => Text::REQUIRED,
                'birthDate.required' => Text::REQUIRED,
                'carId.required' => Text::REQUIRED,
                'motorId.required' => Text::REQUIRED,
                'color.required' => Text::REQUIRED
            ]);

            try
            {
                $this->birthDateTyped = Carbon::createFromFormat('d.m.Y', $this->birthDate);
            }
            catch (\Exception $e)
            {
                $this->addError('birthDate', 'required');
                return;
            }
        }

        $this->orderNumber = Str::uuid();

        DB::transaction(function()
        {
            if ($this->customer_number)
            {
                $customer = Customer::where('number', $this->customer_number)->firstOrFail();
            }
            else
            {
                $customer = Customer::whereRaw('LOWER(last_name) LIKE LOWER(?)', [$this->lastName])
                    ->whereRaw('LOWER(first_name) LIKE LOWER(?)', [$this->firstName])
                    ->whereDate('birthday', $this->birthDateTyped)
                    ->select('id')
                    ->first();

                if (!$customer) {
                    $customer = Customer::create([
                        'number' => Str::uuid(),
                        'last_name' => $this->lastName,
                        'first_name' => $this->firstName,
                        'birthday' => $this->birthDateTyped
                    ]);
                }
            }

            $order = Order::create([
                'number' => $this->orderNumber,
                'price' => $this->calculateTotal(),
                'customer_id' => $customer->id,
                'car_id' => $this->carId,
                'motor_id' => $this->motorId,
                'color' => $this->color,
                'status' => 'new'
            ]);

            foreach ($this->selectedOptions as $i => $item)
            {
                OrderOption::create([
                    'order_id' => $order->id,
                    'option_id' => $item
                ]);
            }
        });

        $this->redirectRoute('kfz.order.details', ['number' => $this->orderNumber]);
    }
};

?>
<div class="container-fluid d-flex h-100 justify-content-center align-items-center p-0">
    <div class="row bg-white shadow-sm">
        <div class="col border rounded p-4">
            <form wire:submit="save">
                <div class="row">
                    <h1 class="text-center">Create order</h1>
                    <h3>Customer</h3>

                    @if ($customer_number)
                        <div class="col mb-3">
                            <label class="form-label">First name</label>
                            <input type="text" readonly class="form-control-plaintext" value="{{$firstName}}">
                        </div>

                        <div class="col mb-3">
                            <label class="form-label">Last name</label>
                            <input type="text" readonly class="form-control-plaintext" value="{{$lastName}}">
                        </div>

                        <div class="col mb-3">
                            <label class="form-label">Birth date</label>
                            <input type="text" readonly class="form-control-plaintext" value="{{$birthDate}}">
                        </div>
                    @else
                        <div class="col mb-3">
                            <label class="form-label">First name</label>
                            @error('firstName') <span style="color: red;">{{ $message }}</span> @enderror
                            <input class="form-control" type="text" wire:model="firstName">
                        </div>

                        <div class="col mb-3">
                            <label class="form-label">Last name</label>
                            @error('lastName') <span style="color: red;">{{ $message }}</span> @enderror
                            <input class="form-control" type="text" wire:model="lastName">
                        </div>

                        <div class="col mb-3">
                            <label class="form-label">Birth date</label>
                            @error('birthDate') <span style="color: red;">{{ $message }}</span> @enderror
                            <div wire:ignore>
                                <input class="form-control datepicker" type="text" id="birthDate" wire:model="birthDate">
                            </div>

                        </div>
                    @endif
                </div>

                <h3>Car</h3>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Vendor</label>
                        <select class="form-select" wire:model.live="vendorId" wire:change="updateVendorId">
                            <option value=""></option>
                            @foreach($vendors as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label">Model</label>
                        @error('carId') <span style="color: red;">{{ $message }}</span> @enderror
                        <label @class([
                            'invisible' => !$carId || !$carPrice
                            ])>
                                - {{$carPrice}} €
                        </label>
                        <select class="form-select" wire:model.live="carId" wire:change="updateCarId">
                            <option value=""></option>
                            @if ($cars)
                                @foreach($cars as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">Fuel type</label>
                        <select class="form-select" wire:model.live="fuelTypeId" wire:change="updateFuelTypeId">
                            <option value=""></option>
                            @if ($fuelTypes)
                                @foreach($fuelTypes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label">Motor</label>
                        @error('motorId') <span style="color: red;">{{ $message }}</span> @enderror
                        <label @class([
                                    'invisible' => !$motorId || !$motorPrice
                                ])>
                                - {{$motorPrice}} €
                        </label>

                        <select class="form-select"  wire:model.live="motorId" wire:change="updateMotorId">
                            <option value=""></option>
                            @if ($motors)
                                @foreach($motors as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <h3>Options</h3>
                        @foreach($options as $i => $item)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{$item['id']}}" wire:model="selectedOptions" wire:change="onOptionChanged"> {{$item['name']}} - {{$item['price']}} €
                            </div>
                        @endforeach
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">Color</label>
                        @error('color') <span style="color: red;">{{ $message }}</span> @enderror
                        <div wire:ignore>
                            <input id="color" class="form-control" type="text" wire:model="color">
                        </div>
                    </div>
                </div>

                    <div @class([
                                'mb-3',
                                'invisible' => $total == 0
                            ])>
                        <h3>Total</h3>
                        <b>{{$total}}</b> €
                    </div>

                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">Submit order</button>
                    @if ($customer_number)
                    <a class="btn btn-secondary" href="{{route('kfz.customer.orders', ['number' => $customer_number])}}">Customer</a>
                    @endif
                    <a class="btn btn-secondary" href="{{route('home')}}">Cancel</a>
                </div>
            </form>
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

    <script src="/js/spectrum.min.js"></script>
    <link rel="stylesheet" href="/css/spectrum.min.css" />

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

        $('#color').spectrum({
            type: "color",
            showAlpha: false,
            showButtons: false,
            preferredFormat: "hex"
        }).on('change', function (e) { @this.set('color', e.target.value); });

    </script>
</x-slot>
