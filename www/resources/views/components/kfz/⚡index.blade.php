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
<x-slot name="head">
    <style>
        html, body {
            height: 100%;
        }
    </style>
</x-slot>

<div class="container-fluid d-flex h-100 justify-content-center align-items-center p-0">
    <div class="row bg-white shadow-sm">
        <div class="col border rounded p-4">
            <h2>Auto shop</h2>
                <div class="d-grid gap-2">

                    <a class="btn btn-primary" href="{{route('kfz.order.create')}}">Create order</a>

                    <a class="btn btn-primary" href="{{route('kfz.order.search')}}">Search order</a>
                    <a class="btn btn-primary" href="{{route('kfz.customer.search')}}">Search customer</a>

                    @guest
                        <a class="btn btn-secondary" href="{{url('/login')}}">Login</a>
                        <a class="btn btn-secondary" href="{{url('/register')}}">Register</a>
                    @endguest

                    @auth
                        <a class="btn btn-secondary" href="{{url('/dashboard')}}">Dashboard</a>
                        <a class="btn btn-secondary" href="{{url('/admin')}}">Admin</a>
                    @endauth
                </div>
        </div>
    </div>
</div>

