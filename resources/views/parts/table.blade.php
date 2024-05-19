@push('styles')
    <style>
        #expandableInput {
            width: 50px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: width 0.3s ease-in-out;
        }

        #expandableInput:focus {
            width: 400px;
        }


        .table-wrapper {
            overflow-x: auto;
        }
    </style>
@endpush

<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ліди</h3>
        </div>

        <div class="card-body">
            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">


                <div class="row">

                    <div class="text-right mb-3 mr-3 col-md-auto">
                        <form method="get" action="{{ url()->current() }}">

                            {{--                            @foreach(request()->query() as $key => $value)--}}
                            {{--                                @if($key !== 'per_page')--}}
                            {{--                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">--}}
                            {{--                                @endif--}}
                            {{--                            @endforeach--}}

                            @foreach(request()->query() as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $subValue)
                                        <input type="hidden" name="{{ $key }}[]"
                                               value="{{ htmlspecialchars($subValue) }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ htmlspecialchars($value) }}">
                                @endif

                            @endforeach


                            <label>Кількість лідів на сторінці</label>
                            <select class="form-control leads-from" name="per_page"
                                    onchange="this.form.submit()">
                                <option value="10" @if(request('per_page') == '10') selected @endif>10</option>
                                <option value="20" @if(request('per_page') == '20') selected @endif>20</option>
                                <option value="50" @if(request('per_page') == '50') selected @endif>50</option>
                                <option value="100" @if(request('per_page') == '100') selected @endif>100
                                </option>
                                <option value="200" @if(request('per_page') == '200') selected @endif>200
                                </option>
                                <option value="500" @if(request('per_page') == '500') selected @endif>500
                                </option>
                            </select>
                        </form>
                    </div>
                </div>

                {{--массовые действия--}}
                @if(!$myLeads)
                    <div class="row">
                        @notrole(['sale'])
                        @include('parts.Mass', ['leads'=>$leads, 'statuses'=>$statuses, 'myLeads'=>$myLeads, 'all_statuses'=> $all_statuses])
                        @endnotrole
                        @include('parts.Search')
                        @include('parts.FIlters', ['statuses'=>$statuses, 'users' =>$users,'countries'=>$countries,
                        'teams'=>$teams, 'desks'=>$desks, 'myLeads'=>$myLeads, 'all_statuses'=> $all_statuses])
                    </div>
                @endif
                {{--конец массовых действий--}}

                <form action="{{ route('leadsAssignPlus')}}" method="post" id="leadsFormPlus">

                    @csrf

                    {{--индикатор того, что сабмит формы с массовых действий--}}
                    <input type="hidden" value="false" name="isMass" id="isMass">

                    {{--выбранные лиды--}}
                    <input type="hidden" value="[]" name="selectedLeads" id="selectedLeads">
                    <div class="row">

                        <div class="col-sm-12">
                            <div class="card-body table-responsive p-0">
                                <strong>
                                    Кількість лідів: {{$leadsCount}}
                                </strong>
                            </div>
                            <div class="table-wrapper">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox"
                                                   class="lead-checkbox"
                                                   name="assigned_leads[]">
                                        </th>
                                        <th>ID</th>
                                        <th>Им'я</th>
                                        <th>Телефон</th>
                                        <th>Пошта</th>
                                        <th>Цінность
                                            <svg
                                                class="sort-value"
                                                xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 320 512"
                                                style="cursor: pointer">
                                                <path
                                                    d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/>
                                            </svg>
                                        </th>
                                        <th>Cтатус
                                            <svg
                                                class="sort-status"
                                                xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 320 512"
                                                style="cursor: pointer">
                                                <path
                                                    d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/>
                                            </svg>
                                        </th>
                                        <th>Добавлений
                                            <svg
                                                class="sort-date"
                                                xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 320 512"
                                                style="cursor: pointer">
                                                <path
                                                    d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/>
                                            </svg>
                                        </th>
                                        <th>Прикріплений
                                            <svg
                                                class="sort-attached"
                                                xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 320 512"
                                                style="cursor: pointer">
                                                <path
                                                    d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/>
                                            </svg>
                                        </th>
                                        <th>Дата коментаря
                                            <svg
                                                class="sort-comment"
                                                xmlns="http://www.w3.org/2000/svg" height="1em"
                                                viewBox="0 0 320 512"
                                                style="cursor: pointer">
                                                <path
                                                    d="M137.4 41.4c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H32c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9l128-128zm0 429.3l-128-128c-9.2-9.2-11.9-22.9-6.9-34.9s16.6-19.8 29.6-19.8H288c12.9 0 24.6 7.8 29.6 19.8s2.2 25.7-6.9 34.9l-128 128c-12.5 12.5-32.8 12.5-45.3 0z"/>
                                            </svg>
                                        </th>

                                        @notrole(['sale'])
                                        @if(!$myLeads)
                                            <th>Сейл</th>
                                        @endif
                                        @endnotrole
                                        <th>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                 viewBox="0 0 512 512">
                                                <path
                                                    d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                                            </svg>
                                            </td>
                                        </th>
                                        <th>Афіл</th>
                                        <th>Реклама</th>
                                        <th>Країна</th>
                                        {{--                                    @role(['super-admin', 'admin'])--}}
                                        @can('delete lead')
                                            <th>
                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                     viewBox="0 0 448 512">
                                                    <path
                                                        d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                                </svg>
                                            </th>
                                        @endcan
                                        {{--                                    @endrole--}}
                                        <th>
                                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"
                                                 viewBox="0 0 448 512">
                                                <path
                                                    d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V173.3c0-17-6.7-33.3-18.7-45.3L352 50.7C340 38.7 323.7 32 306.7 32H64zm0 96c0-17.7 14.3-32 32-32H288c17.7 0 32 14.3 32 32v64c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V128zM224 288a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/>
                                            </svg>
                                            </td>
                                        </th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($leads as $lead)
                                        {{--                                    <tr style="background-color: {{ optional($lead->statusObject)->id != 24 ? optional($lead->statusObject)->color : optional($lead->retention_statusObject)->color }};">--}}
                                        <tr id="lead-row-{{ $lead->id }}">
                                            <td><input type="checkbox" id="lead_{{ $lead->id }}"
                                                       class="lead-checkbox lead-checkbox-td"
                                                       name="assigned_leads[]" value="{{ $lead->id }}"></td>
                                            <td>
                                                {{--                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em"--}}
                                                {{--                                                     style="cursor: pointer" viewBox="0 0 448 512"--}}
                                                {{--                                                     onclick="copyContent('{{ $lead->id }}')">--}}
                                                {{--                                                    <path--}}
                                                {{--                                                        d="M208 0H332.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128h80v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"/>--}}
                                                {{--                                                </svg>--}}
                                                {{ $lead->id }}
                                            </td>
                                            <td>
                                                {{--                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em"--}}
                                                {{--                                                     style="cursor: pointer" viewBox="0 0 448 512"--}}
                                                {{--                                                     onclick="copyContent('{{ $lead->name }}')">--}}
                                                {{--                                                    <path--}}
                                                {{--                                                        d="M208 0H332.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128h80v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"/>--}}
                                                {{--                                                </svg>--}}
                                                <a style="color: {{$lead->viewed == 1 ? '#8B00FF' : ''}}"
                                                   href="{{ route('showLeadPage', ['id' => $lead->id]) }}"
                                                   data-lead="{{ $lead->name }}">
                                                    {{ $lead->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="tel:{{cleanPhoneNumber($lead->phone)}}">{{ cleanPhoneNumber($lead->phone)}}
                                            </td>
                                            <td>{{$lead->email}}</td>
                                            <td>
                                                {{--                                                <svg xmlns="http://www.w3.org/2000/svg" height="1em"--}}
                                                {{--                                                     viewBox="0 0 448 512"--}}
                                                {{--                                                     class="dollar-icon" style="cursor: pointer"--}}
                                                {{--                                                     data-lead-id="{{ $lead->id }}">--}}
                                                {{--                                                    <path--}}
                                                {{--                                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"/>--}}
                                                {{--                                                </svg>--}}
                                                {{--                                            <a href="{{route('showLeadPaymanets', ["id"=>$lead->id])}}">{{$lead->lead_value}}$</a>--}}
                                                <span id="lead-{{$lead->id}}-value">{{$lead->lead_value}}$</span>
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
                                                                <button class="btn btn-primary buttonForAddingValue"
                                                                        data-lead-id="{{ $lead->id }}">
                                                                    Внести
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            @role(['sale', 'teamlead'])
                                            <td data-lead-id="{{ $lead->id }}"
                                                style="background-color: {{ $lead->statusObject->color}};">
                                                {{--                                            <td style="background-color: {{ $lead->statusObject->color}};">--}}
                                                <select class="form-control" onchange="setStatus({{ $lead->id }}, this)"
                                                        name="lead_statuses[{{ $lead->id }}]"
                                                        style="min-width: max-content;">
                                                    @foreach($statuses as $status)
                                                        <option
                                                            value="{{ $status->id }}" {{ $lead->status == $status->id ? 'selected' : '' }}>
                                                            {{ $status->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            @endrole

                                            @role(['super-admin', 'head'])
                                            {{--                                            <td style="background-color: {{ $lead->retention_status ? optional($lead->retention_statusObject)->color : optional($lead->statusObject)->color }};">--}}
                                            <td data-lead-id="{{ $lead->id }}"
                                                style="background-color: {{ $lead->retention_status ? optional($lead->retention_statusObject)->color : optional($lead->statusObject)->color }};">


                                                {{--                                            <select class="form-control" onchange="this.form.submit()"--}}
                                                <select class="form-control"
                                                        onchange="setCombinedStatus({{ $lead->id }}, this)"
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
                                            <td>{{$lead->created_at}}</td>
                                            <td>{{$lead->user_id_updated_at}}</td>
                                            <td>{{$lead->note_updated_at}}</td>

                                            @notrole(['sale'])
                                            @if(!$myLeads)
                                                <td>
                                                    <select class="form-control"
                                                            onchange="reassignment({{$lead->id}}, this, {{auth()->user()->id}})"
                                                            name="lead_user[{{ $lead->id }}]"
                                                            style="min-width: max-content;">
                                                        <option value="null"
                                                        >Свободный
                                                        </option>
                                                        @foreach($users as $user)
                                                            <option
                                                                value="{{ $user->id }}" {{ $lead->user_id == $user->id ? 'selected' : '' }}>
                                                                {{ $user->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            @endif
                                            @endnotrole
                                            <td>
                                                <div data-lead-id="{{ $lead->id }}"
                                                     onclick="fetchComments({{ $lead->id }})"
                                                     data-toggle="modal" data-target="#modal-default-{{ $lead->id }}">
                                                    <svg class="note-icon" style="cursor: pointer"
                                                         xmlns="http://www.w3.org/2000/svg" height="1em"
                                                         viewBox="0 0 512 512">
                                                        <path
                                                            d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/>
                                                    </svg>
                                                </div>
                                                <div class="modal fade" id="modal-default-{{ $lead->id }}"
                                                     style="display: none;" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Коментарий</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                        aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            <textarea class="form-control txt_one" rows="3"
                                                                      name="lead_notes[{{ $lead->id }}]"
                                                                      placeholder="Коментарий..."
                                                                      style="max-width: 1920px; transition: height 0.3s ease-out; height: 150px; resize: none; white-space: pre-wrap; word-wrap: break-word;"
                                                            ></textarea>

                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Закрыть
                                                                </button>
                                                                <button class="btn btn-primary buttonForAddingComments"
                                                                        data-lead-id="{{ $lead->id }}"
                                                                        data-user-id="{{ auth()->user()->id }}">
                                                                    Сохранить
                                                                </button>
                                                            </div>
                                                            <div id="comments-container-{{ $lead->id }}"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$lead->Affiliate}}</td>
                                            <td>{{$lead->Advert}}</td>
                                            <td>{{ $lead->country ? $lead->country->country : '' }}</td>

                                            {{--                                        @role(['super-admin', 'admin'])--}}
                                            @can('delete lead')

                                                <td>
                                                    <a href="{{ route('deleteLead', ['id' => $lead->id]) }}"
                                                       onclick="return confirmDeletion()">
                                                        <svg class="delete-icon" style="cursor: pointer"
                                                             xmlns="http://www.w3.org/2000/svg" height="1em"
                                                             viewBox="0 0 448 512">
                                                            <path
                                                                d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                                        </svg>
                                                    </a>
                                                </td>
                                            @endcan
                                            {{--                                        @endrole--}}
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
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                {{ $leads->appends(array_merge(['per_page' => request('per_page')], request()->all()))->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function confirmDeletion() {
            return confirm('Вы уверены, что хотите удалить этого лида?');
        }

        let curUser = @json(auth()->user()->id);
        // сохранение по нажатию на svg иконку
        document.addEventListener("DOMContentLoaded", function () {
            let icons = document.querySelectorAll('.save-icon');
            icons.forEach(icon => {
                icon.addEventListener('click', function () {
                    document.getElementById('leadsFormPlus').submit();
                });
            });
        });


        // вызов модального окна по нажатию на svg иконку
        const noteIcons = document.querySelectorAll('.note-icon');

        noteIcons.forEach(function (noteIcon) {
            noteIcon.addEventListener('click', function () {
                const leadId = this.getAttribute('data-lead-id');

                const modal = document.getElementById('modal-default-' + leadId);

                $(modal).modal('show');
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

        // массовое выделение
        document.querySelector('th input[type="checkbox"]').addEventListener('change', function (e) {
            const isChecked = e.target.checked;
            document.querySelectorAll('.lead-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });

        // массовое выделения с зажатым shift
        let lastChecked;

        document.querySelectorAll('.lead-checkbox').forEach(checkbox => {
            checkbox.addEventListener('click', function (e) {
                let inBetween = false;

                if (e.shiftKey && e.target.checked && lastChecked) {
                    document.querySelectorAll('.lead-checkbox').forEach(box => {
                        if (box === e.target || box === lastChecked) {
                            inBetween = !inBetween;
                        }
                        if (inBetween) {
                            box.checked = true;
                        }
                    });
                }
                lastChecked = e.target;
            });
        });


        // сортировка

        document.addEventListener("DOMContentLoaded", function () {
            const svgValueElement = document.querySelector('.sort-value');
            const svgDateElement = document.querySelector('.sort-date');
            const svgAttachedElement = document.querySelector('.sort-attached');
            const svgCommentElement = document.querySelector('.sort-comment');
            const svgStatusElement = document.querySelector('.sort-status');

            // функция для сортировки
            function sortHandler(column) {
                const urlParams = new URLSearchParams(window.location.search);

                if (column !== 'status_sort') {
                    let sortOrder = 'asc'; // начальный порядок сортировки

                    if (urlParams.has('sortBy') && urlParams.get('sortBy') === column) {
                        sortOrder = urlParams.get('sortOrder') === 'asc' ? 'desc' : 'asc';
                    } else {
                        urlParams.set('sortBy', column);
                    }

                    urlParams.set('sortOrder', sortOrder);
                } else {
                    urlParams.set('sortBy', column);
                    if (urlParams.has('sortOrder')) {
                        urlParams.delete('sortOrder');
                    }
                }

                window.location.search = urlParams.toString();
            }

            // Назначим событие для каждого SVG
            svgValueElement.addEventListener('click', function () {
                sortHandler('value');
            });

            svgDateElement.addEventListener('click', function () {
                sortHandler('date_added');
            });

            svgAttachedElement.addEventListener('click', function () {
                sortHandler('date_attached');
            });

            svgCommentElement.addEventListener('click', function () {
                sortHandler('comment_attached');
            });

            svgStatusElement.addEventListener('click', function () {
                sortHandler('status_sort');
            });
        });


        //получения коментраиев
        async function fetchComments(leadId) {
            try {

                // const leadId = event.currentTarget.getAttribute('data-lead-id');

                // const leadId = event.target.getAttribute('data-lead-id');
                const commentsContainer = document.getElementById(`comments-container-${leadId}`);
                const response = await fetch(`/getComments/${leadId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();
                if (data === 'отказано') {
                    return;
                }

                if (data.length === 0) {
                    return; // завершить выполнение функции, если нет комментариев
                }

                commentsContainer.innerHTML = ''; // clear current content
                data.forEach(comment => {
                    const commentDiv = document.createElement('div');
                    commentDiv.classList.add('margin-bottom', 'mx-3');

                    const cardDiv = document.createElement('div');
                    cardDiv.classList.add('card', 'card-primary');
                    commentDiv.appendChild(cardDiv);

                    const cardHeaderDiv = document.createElement('div');
                    cardHeaderDiv.classList.add('card-header');

                    // Format the date
                    var date = new Date(comment.created_at);
                    var formattedDate = date.getFullYear() + '-' +
                        ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                        ('0' + date.getDate()).slice(-2) + ' ' +
                        ('0' + date.getHours()).slice(-2) + ':' +
                        ('0' + date.getMinutes()).slice(-2) + ':' +
                        ('0' + date.getSeconds()).slice(-2);

                    cardHeaderDiv.innerHTML = `<h3 class="card-title">${formattedDate}</h3>`;
                    cardDiv.appendChild(cardHeaderDiv);

                    const cardBodyDiv = document.createElement('div');
                    cardBodyDiv.classList.add('card-body');
                    cardBodyDiv.setAttribute('style', 'white-space: break-spaces;');
                    cardBodyDiv.textContent = comment.body.replace(/<br \/>/g, '');
                    cardDiv.appendChild(cardBodyDiv);

                    const cardFooterDiv = document.createElement('div');
                    cardFooterDiv.classList.add('card-footer');
                    cardFooterDiv.innerHTML = `Оставил комментарий: ${comment.user_name ? comment.user_name : 'неизвестный'}`;
                    cardDiv.appendChild(cardFooterDiv);

                    commentsContainer.appendChild(commentDiv);
                });
            } catch (error) {
                console.error('Error:', error);
            }
        }

        const getLeads = () => {
            const leadRows = document.querySelectorAll('tr');
            let leads = [];

            leadRows.forEach(row => {
                const checkbox = row.querySelector('.lead-checkbox');

                // Проверяем, есть ли чекбокс в этой строке
                if (checkbox) {
                    const leadId = checkbox.value;

                    // Проверяем, является ли leadId числом
                    if (!isNaN(leadId)) {
                        const leadEmail = row.cells[4].textContent;

                        leads.push({
                            id: leadId,
                            email: leadEmail,
                        });
                    }
                }
            });

            return leads;
        };

        //добавление идентификатора ture при сабмите с массовых действий
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('leadsFormPlus');

            // Кнопка, которая меняет значение на true и отправляет форму
            document.getElementById('submitFormBtnTrue').addEventListener('click', function (event) {
                event.preventDefault(); // Остановка стандартной отправки формы
                document.getElementById('isMass').value = 'true'; // Установка значения
                form.submit(); // Отправка формы
            });
        });


    </script>
    @include('parts.fetchForStatus')
    @include('parts.fetchForreassignment')
    @include('parts.fetchForComments')
    @include('parts.fetchForValue')
@endpush
