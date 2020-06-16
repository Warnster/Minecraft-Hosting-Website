@extends('layouts.dashboard')

@section('content')

    <cloud-server-component :cloud-servers="{{$cloudServers}}"></cloud-server-component>


@endsection
