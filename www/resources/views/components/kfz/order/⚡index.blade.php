<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new
#[Layout('layouts::kfz')]
class extends Component
{
    //
};
?>

<div>
    <a href="{{route('kfz.order.create')}}">Create order</a>
    <br><br>
    <a href="{{route('kfz.order.search')}}">Search order</a>
    <br><br>
    <a href="{{route('kfz.customer.search')}}">Search customer</a>

    @guest
    <br><br>
    <a href="{{url('/login')}}">Login</a>
    <br><br>
    <a href="{{url('/register')}}">Register</a>
    @endguest

    @auth
        <br><br>
        <a href="{{url('/dashboard')}}">Dashboard</a>
    @endauth
</div>
