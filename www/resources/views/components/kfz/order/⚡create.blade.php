<?php

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

new class extends Component
{
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
    public $color = "FFFFFF";
    public $options;
    public $selectedOptions = [];
    public $total = 0;
    private $orderNumber;


    public function __construct()
    {
        $this->vendors = Vendor::all()->pluck('name', 'id')->toArray();
        $this->options = Option::all()->select('name', 'price', 'id')->toArray();
    }

    public function mount($customer_number)
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
                'color' => 'required|regex:/^[0-9a-fA-F]{6}$/'
            ]);
        }
        else
        {
            $this->validate([
                'firstName' => 'required|max:255',
                'lastName' => 'required|max:255',
                'carId' => 'required',
                'motorId' => 'required',
                'color' => 'required|regex:/^[0-9a-fA-F]{6}$/'
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
    <form wire:submit="save">
        <h3>Customer</h3>
        <br>
        @if ($customer_number)
            <label>
                First name {{$firstName}}
            </label>
            <br>

            <label>
                Last name {{$lastName}}
            </label>
            <br>
            <label>
                Birth date {{$birthDate}}
            </label>
        @else
            <label>
                First name
                <input type="text" wire:model="firstName">
                @error('firstName') <span style="color: red;">{{ $message }}</span> @enderror
            </label>
            <br>

            <label>
                Last name
                <input type="text" wire:model="lastName">
                @error('lastName') <span style="color: red;">{{ $message }}</span> @enderror
            </label>
            <br>
            <label>
                Birth date
                <input type="text" wire:model="birthDate">
                @error('birthDate') <span style="color: red;">{{ $message }}</span> @enderror
            </label>
            <br>
        @endif

        <h3>Car</h3>

        <label>
            Vendor
            <select wire:model.live="vendorId" wire:change="updateVendorId">
                    <option value=""></option>
                @foreach($vendors as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </label>
        <br>

        <label>
            Model
            <select wire:model.live="carId" wire:change="updateCarId">
                <option value=""></option>
                @if ($cars)
                    @foreach($cars as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                @endif
            </select>
            @if ($carId && $carPrice)
            {{$carPrice}} €
            @endif
            @error('carId') <span style="color: red;">{{ $message }}</span> @enderror
        </label>
        <br>

        <label>
            Fuel type
            <select wire:model.live="fuelTypeId" wire:change="updateFuelTypeId">
                <option value=""></option>
                @if ($fuelTypes)
                    @foreach($fuelTypes as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                @endif
            </select>
        </label>
        <br>

        <label>
            Motor
            <select wire:model.live="motorId" wire:change="updateMotorId">
                <option value=""></option>
                @if ($motors)
                    @foreach($motors as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                @endif
            </select>
        </label>
        @if ($motorId && $motorPrice)
            {{$motorPrice}} €
        @endif
        @error('motorId') <span style="color: red;">{{ $message }}</span> @enderror
        <br>

        <label>
            Color
            <input type="text" wire:model="color">
            @error('color') <span style="color: red;">{{ $message }}</span> @enderror
        </label>
        <br>

        <h3>Options</h3>
        @foreach($options as $i => $item)
            <input type="checkbox" value="{{$item['id']}}" wire:model="selectedOptions" wire:change="onOptionChanged"> {{$item['name']}} - {{$item['price']}} €
            <br>
        @endforeach

        <br><br>
        @if ($total > 0)
            <h3>Total</h3>
            <b>{{$total}}</b> €
        @endif
        <br><br>

        <button type="submit">Submit order</button>
        @if ($customer_number)
        &nbsp; - &nbsp;
        <a href="{{route('kfz.customer.orders', ['number' => $customer_number])}}">Customer</a>
        @endif
        &nbsp; - &nbsp;
        <a href="{{route('home')}}">Cancel</a>
    </form>
