@extends('layouts.dashboard')

@section('content')

    @foreach ($servers['servers'] as $server)
        <p>Server:  {{ $server['name'] }}
        </p>
        <dashboard-component csrf="{{csrf_token()}}" guid="{{$guid}}" ip="{{$server['public_net']['ipv4']['ip']}}"></dashboard-component>

    @endforeach
    <minecraft-server-form-component csrf="{{csrf_token()}}"></minecraft-server-form-component>
    @endsection
