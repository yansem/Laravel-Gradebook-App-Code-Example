@extends('layouts.app')
@section('content')
    <index/>
@endsection
@section('local_storage')
    <script>
        localStorage.setItem('user', JSON.stringify({!! json_encode($user) !!}))
    </script>
@endsection


