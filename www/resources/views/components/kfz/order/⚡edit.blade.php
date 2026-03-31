<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
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
    public string $order_number;
    public string $customer_number;
    public string $firstName;
    public string $lastName;
    public string $birthDate;
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
    public $oldTotal = 0;
    public $total = 0;
    private $orderNumber;
    public $orderDateStr;

    public function mount($number)
    {
        $this->order_number = $number;

        $order = Order::where('number', $number)->first();

        if (!$order)
        {
            abort(404);
        }

        $this->color = $order->color;
        $this->oldTotal = $order->price;
        $this->orderDateStr = Text::formatDate($order->created_at);

        $customer = $order->Customer;
        $this->customer_number = $customer->number;
        $this->firstName = $customer->first_name;
        $this->lastName = $customer->last_name;
        $this->birthDate = Text::formatDate($customer->birthday);
        $this->vendors = Vendor::all()->pluck('name', 'id')->toArray();
        $this->options = Option::all()->select('name', 'price', 'id')->toArray();
        $this->vendorId = $order->Car->vendor_id;
        $this->updateVendorId();
        $this->carId = $order->car_id;
        $this->updateCarId();
        $this->fuelTypeId = $order->Motor->fuel_type_id;
        $this->updateFuelTypeId();
        $this->motorId = $order->motor_id;
        $this->selectedOptions = $order->OrderOptions->select('option_id')->pluck('option_id')->toArray();
        $this->updateMotorId();
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
            $this->calculateTotal();
        }
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
    }

    public function updateMotorId()
    {
        $this->motorPrice = Motor::where('id', $this->motorId)->first()->price;
        $this->calculateTotal();
    }

    public function onOptionChanged()
    {
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $optionsPrice = Option::whereIn('id', $this->selectedOptions)->sum('price');
        $this->total = $this->carPrice + $this->motorPrice + $optionsPrice;
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
            ]);
        }
        else
        {
            $this->validate([
                'firstName' => 'required|max:255',
                'lastName' => 'required|max:255',
                'carId' => 'required',
                'motorId' => 'required',
                'color' => 'required|regex:/^#[0-9a-fA-F]{6}$/'
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
        }

        DB::transaction(function()
        {
            $order = Order::where('number', $this->order_number)->firstOrFail();

            $order->price = $this->calculateTotal();
            $order->car_id = $this->carId;
            $order->motor_id = $this->motorId;
            $order->color = $this->color;

            $order->save();

            $options = collect($this->selectedOptions);
            $orderOptions = $order->OrderOptions->all();

            //delete
            foreach ($orderOptions as $opt)
            {
                if (!$options->contains($opt->option_id))
                {
                    $opt->delete();
                }
            }

            //add
            $orderOptionOptionIds = $order->OrderOptions->pluck('option_id');

            foreach ($options as $opt)
            {
                if (!$orderOptionOptionIds->contains($opt))
                {
                    OrderOption::create([
                        'order_id' => $order->id,
                        'option_id' => $opt
                    ]);
                }
            }
        });

        $this->redirectRoute('kfz.customer.orders', ['number' => $this->customer_number]);
    }
};

?>

<div class="container-fluid d-flex h-100 justify-content-center align-items-center p-0">
    <div class="row bg-white shadow-sm">
        <div class="col border rounded p-4">
            <form wire:submit="save">

                <div class="row">
                    <h3 class="text-center">{{__("Edit order")}}</h3>
                        <strong>{{__("Customer")}}</strong>
                
                        <div class="col mb-3">
                            <label class="form-label">{{__("First name")}}</label>
                            <input type="text" readonly class="form-control-plaintext" value="{{$firstName}}">
                        </div>

                        <div class="col mb-3">
                            <label class="form-label">{{__("Last name")}}</label>
                            <input type="text" readonly class="form-control-plaintext" value="{{$lastName}}">
                        </div>

                        <div class="col mb-3">
                            <label class="form-label">{{__("Birth date")}}</label>
                            <input type="text" readonly class="form-control-plaintext" value="{{$birthDate}}">
                        </div>
                </div>

                <strong>{{__("Order")}}</strong>
                <div class="row">     
                    <div class="col mb-3">
                        <label class="form-label">{{__("Number")}}</label>
                        <input type="text" readonly class="form-control-plaintext" value="{{ $order_number }}">
                    </div>                     
                </div>

                <div class="row">     
              
                    <div class="col mb-3">
                        <label class="form-label">{{__("Created")}}</label>
                        <input type="text" readonly class="form-control-plaintext" value="{{ $orderDateStr }}">
                    </div>

                    <div class="col mb-3">
                        <label class="form-label">{{__("Changed")}}</label>
                        <input type="text" readonly class="form-control-plaintext" value="{{ $orderDateStr }}">
                    </div>                    
                </div>                

                <strong>{{__("Car")}}</strong>

                <div class="row">
                    <div class="col mb-3">
                        <label class="form-label">{{__("Vendor")}}</label>
                        <select class="form-select" wire:model.live="vendorId" wire:change="updateVendorId">
                            <option value=""></option>
                            @foreach($vendors as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col mb-3">
                        <label class="form-label">{{__("Model")}}</label>
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
                        <label class="form-label">{{__("Fuel type")}}</label>
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
                        <label class="form-label">{{__("Motor")}}</label>
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
                        <strong>{{__("Options")}}</strong>
                        @foreach($options as $i => $item)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{$item['id']}}" wire:model="selectedOptions" wire:change="onOptionChanged"> {{$item['name']}} - {{$item['price']}} €
                            </div>
                        @endforeach
                    </div>
                    <div class="col mb-3">
                        <label class="form-label">{{__("Color")}}</label>
                        @error('color') <span style="color: red;">{{ $message }}</span> @enderror
                        <div wire:ignore>
                            <input id="color" class="form-control" type="text" wire:model="color">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col mb-3">
                        <strong>{{__("Old price")}}</strong>
                        {{$oldTotal}} {{__("€")}}
                    </div>
                    <div class="col mb-3">
                        <strong>{{__("New price")}}</strong>
                        {{$total}} {{__("€")}}
                    </div>
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">{{__("Update order")}}</button>
                    <a class="btn btn-secondary" href="{{route('kfz.customer.orders', ['number' => $customer_number])}}">{{__("Cancel")}}</a>
                </div>
            </form>
         </div>
    </div>
</div>

<x-slot name="script">
    <script>
        $('#color').spectrum({
            type: "color",
            showAlpha: false,
            showButtons: false,
            preferredFormat: "hex"
        }).on('change', function (e) { @this.set('color', e.target.value); });
    </script>
</x-slot>