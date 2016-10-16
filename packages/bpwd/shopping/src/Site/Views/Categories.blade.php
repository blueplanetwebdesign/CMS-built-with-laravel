@extends('layouts.app')
@section('content')
    @if(count($categories))

        <ul>
        @foreach($categories AS $category)
            <li> {{ $category->name }}</li>
        @endforeach
        </ul>

    @endif
@endsection