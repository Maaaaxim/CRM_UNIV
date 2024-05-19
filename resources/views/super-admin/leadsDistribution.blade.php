@extends('layouts.main-layout')


@section('content')
    <div class="content-wrapper">

        <form method="post" action="{{route('leadsImport')}}" enctype="multipart/form-data">

            @csrf

            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputFile">Импорт лидов</label>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="exampleInputFile" name="leadsFile">
                            <label class="custom-file-label" for="exampleInputFile">Выбрать файл</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Импортировать</button>
                </div>
            </div>
        </form>

        @include('parts.table', ['leads'=>$leads, 'statuses'=>$statuses, 'users' =>$users,'countries'=>$countries, 'teams'=>$teams, 'desks'=>$desks])

    </div>
@endsection

@push('scripts')

    <script>
    </script>
@endpush
