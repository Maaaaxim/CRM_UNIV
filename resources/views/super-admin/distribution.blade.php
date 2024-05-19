@extends('layouts.main-layout')


@section('content')
    <div class="content-wrapper">

        @include('parts.distributionTable', ['users' =>$users, 'teams'=>$teams, 'desks'=>$desks])

    </div>
@endsection

@push('scripts')

    <script>

    </script>
@endpush
