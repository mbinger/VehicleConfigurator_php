<?php

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Customer;
use Carbon\Carbon;

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
            $this->addError('firstName', 'Customer not found');
        }
    }
};
?>

<form wire:submit="search">
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
    <br>
    <button type="submit">Search</button>
    &nbsp; - &nbsp;
    <a href="{{route('home')}}">Cancel</a>
</form>
