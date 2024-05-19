@extends('layouts.main-layout')

@section('content')
    @push('styles')
        <style>
            .note-container {
                max-height: 150px;
                max-width: 350px;
                overflow-y: auto;
                border: 1px solid #ccc;
                padding: 5px;
            }

            .online-badge {
                display: inline-block;
                padding: 2px 8px;
                background-color: #28a745;
                color: white;
                border-radius: 15px;
                font-size: 15px;
                margin-left: 15px;
            }

            .offline-badge {
                display: inline-block;
                padding: 2px 8px;
                background-color: #dc3545; /* Красный цвет для офлайн */
                color: white;
                border-radius: 15px;
                font-size: 15px;
                margin-left: 15px;
            }


            .input-wrapper {
                /*margin-bottom: 20px;*/
            }

            .custom-label {
                display: block;
                /*margin-bottom: 5px;*/
                font-weight: bold;
            }

            .custom-input {
                display: flex;
                align-items: center;
                border: 1px solid #ced4da;
                padding: 5px 10px;
                border-radius: 5px;
                background-color: #fff;
                height: 39px;

            }

            .dollar-icon {
                margin-right: 5px;
                fill: #495057;
            }

            .comment-container {
                padding: 0 3px;
                max-height: 650px;
                overflow-y: auto;
                overflow-x: hidden;
            }

            .bootstrap-datetimepicker-widget a.btn {
                color: #000; /* Цвет стрелок */
            }

            .bootstrap-datetimepicker-widget .timepicker-hour,
            .bootstrap-datetimepicker-widget .timepicker-minute {
                background-color: #ddd; /* Фоновый цвет для выбора часа и минуты */
            }

            /*.external-event .bi-trash {*/
            /*    float: right; !* Поместить иконку перед текстом *!*/
            /*    margin-left: 10px; !* Отступ от текста *!*/
            /*    !* Другие стили по необходимости *!*/
            /*}*/
            .external-event-wrapper {
                position: relative; /* Для позиционирования иконки */
                padding-right: 20px; /* Добавьте отступ справа, чтобы оставить место для иконки */
            }

            .external-event-wrapper .external-event {
                /* Ваши стили для событий */
            }

            .external-event-wrapper .bi-trash {
                position: absolute; /* Абсолютное позиционирование относительно родителя */
                right: 0; /* Прижать к правому краю родителя */
                top: 50%; /* Центрировать по вертикали */
                transform: translateY(-50%); /* Сдвиг для точного центрирования по вертикали */
                cursor: pointer; /* Стиль курсора при наведении на иконку */
            }

            .notification-container {
                max-height: 170px;
                overflow-y: auto;
                overflow-x: hidden;
            }

            /*.notification-center {*/
            /*    border: 3px solid #0062cc;*/
            /*    padding: 3px;*/
            /*    border-radius: 10px;*/
            /*}*/
            hr {
                height: 5px; /* Устанавливает толщину линии */
                border: none; /* Убираем стандартную рамку */
                background-color: #333; /* Цвет линии */
            }


        </style>
    @endpush
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Сторінка ліда</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Головна</a></li>
                            <li class="breadcrumb-item active">Інформація про ліда</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <a href="{{$linkback}}">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                <path
                                    d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z"/>
                            </svg>
                        </a> Інформація про ліда
                        <a href="{{$linkforward}}">
                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                                <path
                                    d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/>
                            </svg>
                        </a>
                    </h3>
                    @can('see online status')
                        <span id="userStatus"></span> <!-- Місце для відображення статусу -->
                    @endcan
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('leadsAssignPlus') }}">
                        @csrf
                        <input type="hidden" value="lead_page" name="is_lead_page">

                        <div class="row mt-3">
                            <!-- First Column -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">ID: </label>
                                    <input type="text" readonly class="form-control" value="{{$lead->id}}"
                                           name="lead_id">
                                </div>
                                <div class="form-group">
                                    <label for="name">Ім'я: </label>
                                    @role(['sale'])
                                    <input type="text" readonly class="form-control" name="name"
                                           value="{{$lead->name ? $lead->name : 'Вільний'}}">
                                    @endrole
                                    @role(['super-admin', 'head', 'teamlead', 'retention_manager',
                                    'retention_teamlead'])
                                    <input type="text" class="form-control" name="name"
                                           value="{{$lead->name ? $lead->name : 'Вільний'}}">
                                    @endrole
                                </div>
                                <div class="form-group">
                                    <label for="email">Пошта: </label>
                                    @role(['sale'])
                                    <input readonly type="email" class="form-control" name="email" id="userEmail"
                                           value="{{$lead->email ? $lead->email : 'Немає пошти'}}">
                                    @endrole
                                    @role(['super-admin', 'head', 'teamlead', 'retention_manager',
                                    'retention_teamlead'])
                                    <input type="email" class="form-control" name="email" id="userEmail"
                                           value="{{$lead->email ? $lead->email : 'Немає пошти'}}">
                                    @endrole
                                </div>
                                <div class="form-group">
                                    <label for="phone">Телефон: </label>
                                    @role(['sale'])
                                    <input type="text" readonly class="form-control" name="phone"
                                           value="{{cleanPhoneNumber($lead->phone)}}">
                                    @endrole
                                    @role(['super-admin', 'head', 'teamlead', 'retention_manager',
                                    'retention_teamlead'])
                                    <input type="text" class="form-control" name="phone"
                                           value="{{cleanPhoneNumber($lead->phone)}}">
                                    @endrole
                                </div>
                                @can('assign lead')
                                    <div class="form-group">
                                        <label for="assigned_to">Закріплено за: </label>
                                        <select class="form-control"
                                                onchange="reassignment({{$lead->id}}, this, {{auth()->user()->id}})"
                                                name="lead_user[{{ $lead->id }}]">
                                            <option value="null">Вільний</option>
                                            @foreach($users as $user)
                                                <option
                                                    value="{{ $user->id }}" {{ $lead->user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endcan
                                <div class="form-group">
                                    <label for="assigned_by">Хто призначив: </label>
                                    <input readonly type="text" id="assigned_by" class="form-control" name="assigned_by"
                                           value="{{ $lead->createdBy->name ?? 'N/A' }}">
                                </div>
                                <div class="form-group">
                                    <label for="attachment_date">Дата прикріплення: </label>
                                    <input readonly type="text" id="attachment_date" class="form-control"
                                           name="attachment_date" value="{{$lead->user_id_updated_at}}">
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="creation_date">Дата створення: </label>
                                    <input readonly type="text" id="creation_date" class="form-control"
                                           name="creation_date" value="{{$lead->created_at}}">
                                </div>
                                <div class="form-group">
                                    <div class="input-wrapper">
                                        <label for="lead-value">Цінність ліда</label>
                                        <div class="custom-input">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"
                                                 class="dollar-icon" style="cursor: pointer"
                                                 data-lead-id="{{ $lead->id }}">
                                                <path
                                                    d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3-32-32s-14.3-32 32-32H256V80z"/>
                                            </svg>
                                            <span>{{$lead->lead_value}}$</span>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modal-dollar-{{ $lead->id }}" style="display: none;"
                                         aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Внесення депозиту</h4>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                <textarea class="form-control txt_one" rows="3"
                                                          name="lead_payments[{{ $lead->id }}]"
                                                          placeholder="Сума..."
                                                          style="max-width: 1920px; transition: height 0.3s ease-out; height: 50px; resize: none; white-space: pre-wrap; word-wrap: break-word;"
                                                ></textarea>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        Закрити
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Внести</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    @role(['sale', 'teamlead'])
                                    <td data-lead-id="{{ $lead->id }}"
                                        style="background-color: {{ $lead->statusObject->color}};">
                                        <label for="lead-value">Статус</label>
                                        <select class="form-control" onchange="setStatus({{ $lead->id }}, this)"
                                                name="lead_statuses[{{ $lead->id }}]" style="min-width: max-content;">
                                            @foreach($statuses as $status)
                                                <option
                                                    value="{{ $status->id }}" {{ $lead->status == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    @endrole

                                    @role(['retention_manager', 'retention_teamlead'])
                                    <td data-lead-id="{{ $lead->id }}"
                                        style="background-color: {{ $lead->retention_statusObject && $lead->retention_statusObject->color ? $lead->retention_statusObject->color : 'defaultColor' }};">
                                        @if(true)
                                            <label for="lead-value">Статус</label>
                                            <select class="form-control" data-id="{{ $lead->id }}"
                                                    onchange="setRetentionStatus(this)"
                                                    name="lead_retention_statuses[{{ $lead->id }}]"
                                                    style="min-width: max-content;">
                                                @foreach($retention_statuses as $retention_status)
                                                    <option
                                                        value="{{ $retention_status->id }}" {{ $lead->retention_status == $retention_status->id ? 'selected' : '' }}>
                                                        {{ $retention_status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <!-- Нічого не відображається -->
                                        @endif
                                    </td>
                                    @endrole

                                    @role(['super-admin', 'head'])
                                    <td data-lead-id="{{ $lead->id }}"
                                        style="background-color: {{ $lead->retention_status ? optional($lead->retention_statusObject)->color : optional($lead->statusObject)->color }};">
                                        <label for="lead-value">Статус</label>
                                        <select class="form-control" onchange="setCombinedStatus({{ $lead->id }}, this)"
                                                name="lead_combined_statuses[{{ $lead->id }}]"
                                                style="min-width: max-content;">
                                            @foreach($all_statuses as $index => $status)
                                                @php
                                                    $isSelected = false;

                                                    if (Str::startsWith($status->prefixed_id, 'retention_')) {
                                                        if ($lead->retention_status == $status->id) {
                                                            $isSelected = true;
                                                        }
                                                    } else {
                                                        if (is_null($lead->retention_status) && $lead->status == $status->id) {
                                                            $isSelected = true;
                                                        }
                                                    }
                                                @endphp

                                                <option
                                                    value="{{ $status->prefixed_id }}" {{ $isSelected ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>

                                                @if (isset($all_statuses[$index+1]) &&
                                                     Str::startsWith($status->prefixed_id, 'status_') &&
                                                     Str::startsWith($all_statuses[$index+1]->prefixed_id, 'retention_'))
                                                    <option disabled>!retention!</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    @endrole
                                </div>

                                <div class="form-group">
                                    <label for="phone">Країна</label>
                                    @role(['sale'])
                                    <select id="countriesFilter" disabled name="country_id"
                                            class="form-control custom-select filter-select">
                                        @endrole
                                        @role(['super-admin', 'head', 'teamlead', 'retention_manager',
                                        'retention_teamlead'])
                                        <select id="countriesFilter" name="country_id"
                                                class="form-control custom-select filter-select"
                                                onchange="changeCountry({{$lead->id}}, this)">
                                            @endrole
                                            @foreach($countries as $country)
                                                <option value="{{ $country->country_id }}"
                                                        @if($country->country_id == $lead->country_id) selected @endif>
                                                    {{ $country->country }}
                                                </option>
                                            @endforeach
                                        </select>
                                </div>

                                <div class="form-group">
                                    <label for="affiliate">Aфіл: </label>
                                    @role(['sale'])
                                    <input type="text" readonly id="affiliate" class="form-control" name="affiliate"
                                           value="{{$lead->Affiliate ?? ''}}">
                                    @endrole
                                    @role(['super-admin', 'head', 'teamlead', 'retention_manager',
                                    'retention_teamlead'])
                                    <input type="text" id="affiliate" class="form-control" name="affiliate"
                                           value="{{$lead->Affiliate ?? ''}}">
                                    @endrole
                                </div>
                                <div class="form-group">
                                    <label for="advert">Реклама: </label>
                                    @role(['sale'])
                                    <input type="text" readonly id="advert" class="form-control" name="advert"
                                           value="{{$lead->Advert ?? ''}}">
                                    @endrole
                                    @role(['super-admin', 'head', 'teamlead', 'retention_manager',
                                    'retention_teamlead'])
                                    <input type="text" id="advert" class="form-control" name="advert"
                                           value="{{$lead->Advert ?? ''}}">
                                    @endrole
                                </div>
                                <div class="form-group">
                                    <div class="row mt-5">
                                        <div class="col-md-2">
                                            <div class="modal fade" id="modal-default-{{ $lead->id }}"
                                                 style="display: none;" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Коментар</h4>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <textarea class="form-control txt_one" rows="3"
                                                                  name="lead_notes[{{ $lead->id }}]"
                                                                  placeholder="Коментар..."
                                                                  style="max-width: 1920px; transition: height 0.3s ease-out; height: 150px; resize: none; white-space: pre-wrap; word-wrap: break-word;"
                                                        ></textarea>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <button type="button" class="btn btn-default"
                                                                    data-dismiss="modal">Закрити
                                                            </button>
                                                            <button type="submit" class="btn btn-primary">Зберегти
                                                            </button>
                                                        </div>
                                                        <div id="comments-container-{{ $lead->id }}"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <button type="submit" class="btn btn-primary">Зберегти</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Third Column -->
                            <div class="col-md-4">
                                <label for="textarea-default-{{ $lead->id }}">Написати коментар</label>
                                <textarea class="form-control" id="textarea-default-{{ $lead->id }}"
                                          name="lead_notes[{{ $lead->id }}]" rows="3"
                                          placeholder="Введіть коментар..."
                                          style="max-width: 1920px; height: 100px;"></textarea>
                                <button type="submit" class="btn btn-primary mt-2 mb-5">Додати</button>

                                @can('see users comments')
                                    <div class="comment-container">
                                        @foreach($comments as $comment)
                                            <div class="margin-bottom">
                                                <div class="card card-primary">
                                                    <div class="card-header">
                                                        <h3 class="card-title">{{$comment->created_at}}</h3>
                                                        <div class="card-tools">
                                                            @can('delete comments')
                                                                <a href="{{ route('deleteComment', ['id' => $comment->id]) }}">
                                                                    <svg class="delete-icon" style="cursor: pointer"
                                                                         xmlns="http://www.w3.org/2000/svg" height="1em"
                                                                         viewBox="0 0 448 512">
                                                                        <path
                                                                            d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                                                    </svg>
                                                                </a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        {!!$comment->body!!}
                                                    </div>
                                                    <div class="card-footer">
                                                        Залишив
                                                        коментар: {{ $comment->user ? $comment->user->name : 'невідомий' }}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Історія змін</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                        <tr>
                                            <th>Дата</th>
                                            <th>Тип зміни</th>
                                            <th>Деталі</th>
                                            <th>Змінено</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($changes as $change)
                                            <tr>
                                                <td>{{ $change->change_date }}</td>
                                                <td>{{ $headers[$change->change_type] }}</td>
                                                <td>{{ $actions[$change->change_type]($change) }}</td>
                                                <td>{{ $change->user ? $change->user->name : 'невідомий' }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @notrole(['sale', 'retention_manager'])
                <div class="mt-3 mb-3 text-left">
                    <a href="{{ route('showLeads')}}" class="btn btn-primary" style="width: 120px; margin-left: 20px;">Назад</a>
                </div>
                @endrole

                @role(['sale', 'retention_manager'])
                <div class="mt-3 mb-3 text-left">
                    <a href="{{ route('myLeads')}}" class="btn btn-primary" style="width: 120px; margin-left: 20px;">Назад</a>
                </div>
                @endrole
            </div>
        </section>
    </div>


    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
        </div>
    </footer>
    @push('scripts')
        <script>
            let curUser = @json(auth()->user()->id);

            const dollarIcons = document.querySelectorAll('.dollar-icon');

            dollarIcons.forEach(function (dollarIcon) {
                dollarIcon.addEventListener('click', function () {
                    const leadId = this.getAttribute('data-lead-id');
                    const modal = document.getElementById('modal-dollar-' + leadId);
                    $(modal).modal('show');
                });
            });


        </script>
        @include('parts.fetchForStatus')
        @include('parts.fetchForreassignment')
        @include('parts.fetchForChangeCountry')
        @include('parts.fetchForNotification')

    @endpush
@endsection
