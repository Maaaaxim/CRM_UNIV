@extends('layouts.main-layout')

@section('content')
    <div class="content-wrapper">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Лиды</h3>
                </div>
                <div class="card-body">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="text-right mb-3 mr-3 col-md-auto">
                                <form method="get" action="{{ url()->current() }}">
                                    <label>Кол лидов на странице</label>
                                    <select class="form-control leads-from" name="per_page"
                                            onchange="this.form.submit()">
                                        <option value="10" @if(request('per_page') == '10') selected @endif>10</option>
                                        <option value="20" @if(request('per_page') == '20') selected @endif>20</option>
                                        <option value="50" @if(request('per_page') == '50') selected @endif>50</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        <form action="{{ route('leadsAssign')}}" method="post" id="leadsForm">
                            @csrf

                            <input type="hidden" name="userId"
                                   value="{{ $userId }}">

                            <div class="row">
                                <div class="col-sm-12">

                                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                                           aria-describedby="example2_info">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone number</th>
                                            <th>Assign</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($leads as $lead)
                                            <tr class="even">
                                                <td>{{ $lead->name }}</td>
                                                <td>{{ $lead->email }}</td>
                                                <td>{{ $lead->phone }}</td>
                                                <td>
                                                    <input type="hidden" name="all_leads_on_page[]" value="{{ $lead->id }}">
                                                    <input type="checkbox" id="lead_{{ $lead->id }}"
                                                           name="assigned_leads[]" value="{{ $lead->id }}"
                                                        {{ $lead->user_id == $userId ? 'checked' : '' }}>
                                                    <label for="lead_{{ $lead->id }}">Assign to Manager</label>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-5">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                        {{ $leads->appends(['per_page' => request('per_page')])->links('vendor.pagination.bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.pagination a').forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                // URL, на который был сделан клик (для пагинации)
                const clickedPaginationUrl = e.target.href;

                const form = document.querySelector('#leadsForm');

                //скрытый input для передачи URL пагинации
                let hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'paginationUrl';
                hiddenInput.value = clickedPaginationUrl;

                //Добавляется этот input к форме
                form.appendChild(hiddenInput);

                form.submit();
            });
        });
    </script>
@endsection

