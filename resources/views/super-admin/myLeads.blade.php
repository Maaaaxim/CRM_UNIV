@extends('layouts.main-layout')


@section('content')
    @push('styles')
        <style>
            .btn-save-icon {
                background: none;
                border: none;
                padding: 0;
                margin: 0;
                cursor: pointer;
                outline: none;
            }
        </style>
    @endpush
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
                        <form action="{{ route('leadsAssignPlus')}}" method="post" id="leadsFormPlus">

                            @csrf

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                                           aria-describedby="example2_info">
                                        <thead>
                                        <tr>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example2"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending">Имя
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                Почта
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                colspan="1" aria-label="Platform(s): activate to sort column ascending">
                                                Телефон
                                            </th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                colspan="1" aria-label="Platform(s): activate to sort column ascending">
                                                Страна
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                Статус
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                Написать оментарий
                                            </th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                Ценность лида
                                            </th><th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                                colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                     viewBox="0 0 448 512">
                                                    <path
                                                        d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V173.3c0-17-6.7-33.3-18.7-45.3L352 50.7C340 38.7 323.7 32 306.7 32H64zm0 96c0-17.7 14.3-32 32-32H288c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V128zM224 288a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/>
                                                </svg>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($leads as $lead)
                                            <tr class="even">
                                                <td><a href="{{ route('showLeadPage', ['id' => $lead->id]) }}"> {{ $lead->name }}</a></td>
                                                <td>{{ $lead->email  ?? ''}}</td>
                                                <td><a href="tel: {{ $lead->phone }}">{{ $lead->phone }}</a>
                                                <td>{{ $lead->country->country ?? '' }}
                                                </td>

                                                <td><input type="hidden" name="all_leads_on_page[]"
                                                           value="{{ $lead->id }}">
                                                    <div class="form-group">
                                                        <select class="form-control" onchange="this.form.submit()"
                                                                name="lead_statuses[{{ $lead->id }}]">
                                                            @foreach($statuses as $status)
                                                                <option
                                                                    value="{{ $status->id }}" {{ $lead->status == $status->id ? 'selected' : '' }}>
                                                                    {{ $status->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <textarea class="form-control txt_one" rows="3"
                                                                  name="lead_notes[{{ $lead->id }}]"
                                                                  placeholder="Комменртарий..."
                                                                  style="max-width: 1920px; transition: height 0.3s ease-out; height: 50px; resize: none; white-space: pre-wrap; word-wrap: break-word;"
                                                        ></textarea>
                                                    </div>
                                                </td>
                                                <td>
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"
                                                         class="dollar-icon" style="cursor: pointer"
                                                         data-lead-id="{{ $lead->id }}">
                                                        <path
                                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>
                                                    </svg>
                                                    {{$lead->lead_value}}$
                                                    <div class="modal fade" id="modal-dollar-{{ $lead->id }}"
                                                         style="display: none;" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Внесение депозита</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                            aria-label="Close">
                                                                        <span aria-hidden="true">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                            <textarea class="form-control txt_one" rows="3"
                                                                      name="lead_payments[{{ $lead->id }}]"
                                                                      placeholder="Сумма..."
                                                                      style="max-width: 1920px; transition: height 0.3s ease-out; height: 50px; resize: none; white-space: pre-wrap; word-wrap: break-word;"
                                                            ></textarea>
                                                                </div>
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default"
                                                                            data-dismiss="modal">Закрыть
                                                                    </button>
                                                                    <button type="submit" class="btn btn-primary">Внести
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <svg class="save-icon" style="cursor: pointer"
                                                         xmlns="http://www.w3.org/2000/svg" height="1em"
                                                         viewBox="0 0 448 512">
                                                        <path
                                                            d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V173.3c0-17-6.7-33.3-18.7-45.3L352 50.7C340 38.7 323.7 32 306.7 32H64zm0 96c0-17.7 14.3-32 32-32H288c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V128zM224 288a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/>
                                                    </svg>
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
@push('scripts')
    <script>

        // сохранение по нажатию на svg иконку
        document.addEventListener("DOMContentLoaded", function () {
            let icons = document.querySelectorAll('.save-icon');
            icons.forEach(icon => {
                icon.addEventListener('click', function () {
                    document.getElementById('leadsFormPlus').submit();
                });
            });
        });

        // изменения размера textarea, если слово не помещяется
        document.addEventListener('DOMContentLoaded', function () {
            const dataInputs = document.querySelectorAll('.txt_one, .txt_two');

            function adjustTextareaHeight(textarea) {
                textarea.style.height = 'auto'; // сбрасываем высоту, чтобы получить полную высоту содержимого

                // Используем requestAnimationFrame для задержки перед установкой новой высоты, чтобы обеспечить плавный переход
                requestAnimationFrame(() => {
                    textarea.style.height = textarea.scrollHeight + 'px'; // задаем новую высоту
                });
            }

            function resizeTextareaOnFocus(event) {
                const target = event.target;
                adjustTextareaHeight(target);
            }

            function resetTextareaOnBlur(event) {
                const target = event.target;
                target.style.height = '50px';
            }

            dataInputs.forEach(input => {
                input.addEventListener('focus', resizeTextareaOnFocus);
                input.addEventListener('blur', resetTextareaOnBlur);
            });
        });
        document.querySelectorAll('.pagination a').forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                // URL, на который был сделан клик (для пагинации)
                const clickedPaginationUrl = e.target.href;

                const form = document.querySelector('#leadsFormPlus');

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
        // вызов модального окна по нажатию на svg иконку плюса
        const dollarIcons = document.querySelectorAll('.dollar-icon');

        dollarIcons.forEach(function (dollarIcon) {
            dollarIcon.addEventListener('click', function () {
                const leadId = this.getAttribute('data-lead-id');
                const modal = document.getElementById('modal-dollar-' + leadId);
                $(modal).modal('show');
            });
        });
    </script>
@endpush
