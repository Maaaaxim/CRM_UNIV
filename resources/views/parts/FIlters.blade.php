@push('styles')
    <style>
        /* Стиль для выбранного элемента */
        .select2-selection__choice {
            background-color: #337ab7 !important;
            color: white !important;
            border: none !important;
        }

        /* Стиль для иконки удаления выбранного элемента */
        .select2-selection__choice__remove {
            color: white !important;
        }

        /* Стиль для элементов в выпадающем списке */
        .select2-results__option {
            color: #333;
        }

        /* Стиль для выбранного элемента в выпадающем списке */
        .select2-results__option[aria-selected="true"] {
            background-color: #337ab7 !important;
            color: white !important;
        }


    </style>
@endpush

<div class="col-md-4">
    <form method="GET" action="{{ route('showLeads') }}" id="status_filter_id">
        <input type="hidden" name="page" value="{{ request('page', 1) }}">
        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        <div class="card card-primary collapsed-card">
            <div class="card-header">
                <h3 class="card-title">Фильтр</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <div class="form-group row">

                    <div class="col-md-6">
                        <label for="inputStatus">Сейлы</label>
                        <select id="salesFilter" name="sales_id[]"
                                class="form-control custom-select filter-select" multiple>
                            <option value="0">Все</option>
                            <option value="free">Свободные</option>
                            @foreach($users as $user)
                                <option
                                    value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="inputStatus">Команда</label>
                        <select id="teamFilter" name="team_id"
                                class="form-control custom-select filter-select">
                            <option value="0">Все</option>
                            @foreach($teams as $team)
                                <option
                                    value="{{ $team->team_id }}">
                                    {{ $team->team }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="form-group row">

                    <div class="col-md-6">
                        <label for="inputStatus">Дэска</label>
                        <select id="deskFilter" name="desk_id" class="form-control custom-select filter-select">
                            <option value="0">Все</option>
                            @foreach($desks as $desk)
                                <option
                                    value="{{ $desk->desk_id }}">
                                    {{ $desk->desk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="inputStatus">Статус</label>

                        <select id="statusesFilter" name="status_prefixed_id[]"
                                class="form-control custom-select filter-select" multiple>

                            <option value="0">Все</option>
                            @php
                                $isFirstRetention = true;
                            @endphp
                            @foreach($all_statuses as $status)
                                @if($isFirstRetention && Str::startsWith($status->prefixed_id, 'retention_'))
                                    <option disabled>!retention!</option>
                                    @php
                                        $isFirstRetention = false;
                                    @endphp
                                @endif
                                <option value="{{ $status->prefixed_id }}">
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{--                    <div class="col-md-3">--}}
                    {{--                        <label for="inputStatus">Р статус</label>--}}
                    {{--                        <select id="statusesFilter" name="retention_status_id"--}}
                    {{--                                class="form-control custom-select filter-select">--}}
                    {{--                            <option value="0">Все</option>--}}
                    {{--                            @foreach($retention_statuses as $retention_status)--}}
                    {{--                                <option--}}
                    {{--                                    value="{{ $retention_status->id }}">--}}
                    {{--                                    {{ $retention_status->name }}--}}
                    {{--                                </option>--}}
                    {{--                            @endforeach--}}
                    {{--                        </select>--}}
                    {{--                    </div>--}}

                </div>
                <div class="form-group row">

                    <div class="col-md-6">
                        <label for="unique_user_id_updated_at">Дата прикрепления</label>
                        <input type="text" id="unique_user_id_updated_at" name="unique_user_id_updated_at" autocomplete="off"
                               class="form-control" placeholder="YYYY-MM-DD - YYYY-MM-DD">
                    </div>

                    <div class="col-md-6">
                        <label for="dateRange">Дата добавления</label>
                        <input type="text" id="dateRange" name="dateRange" class="form-control" autocomplete="off"
                               placeholder="YYYY-MM-DD - YYYY-MM-DD">
                    </div>

                </div>
                <div class="form-group row">

                    <div class="col-md-6">
                        <label for="inputStatus">Страна</label>
                        <select id="countriesFilter" name="country_id"
                                class="form-control custom-select filter-select">
                            <option value="0">Все</option>
                            @foreach($countries as $country)
                                <option
                                    value="{{ $country->country_id }}"
                                >
                                    {{ $country->country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary w-100 mb-3">Применить фильтры</button>
                        <a href="{{ route('showLeads', array_filter(['page' => request('page'), 'per_page' => request('per_page')])) }}"
                           class="btn btn-primary w-100">Сбросить фильтры и параметры поиска</a>

                    </div>

                </div>

            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>

    </script>


    <script>

        {{--инициализация selecet2 для статусов--}}
        jQuery(document).ready(function ($) {
            $('#statusesFilter').select2({
                placeholder: "Выберите статусы",
                width: '100%'
            });
        });

        {{--инициализация selecet2 для сейлов--}}
        jQuery(document).ready(function ($) {
            $('#salesFilter').select2({
                placeholder: "Выберите сейлов",
                width: '100%'
            });
        });


        function isActiveDate(date, activeDatesArray) {
            const formattedDate = date.toISOString().split('T')[0];
            return activeDatesArray.includes(formattedDate) ? formattedDate : undefined;
        }


        $(document).ready(function () {
            // Инициализация daterangepicker для #dateRange и #unique_user_id_updated_at
            $('#dateRange, #unique_user_id_updated_at').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            $('#dateRange, #unique_user_id_updated_at').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            });

            $('#dateRange, #unique_user_id_updated_at').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });

            // Получение и установка значения из URL
            const urlParams = new URLSearchParams(window.location.search);

            // Для dateRange
            if (urlParams.has("dateRange")) {
                let dateRangeValue = urlParams.get("dateRange").split(' - ');

                if (dateRangeValue.length == 2) {
                    $('#dateRange').val(dateRangeValue.join(' - '));
                    $('#dateRange').data('daterangepicker').setStartDate(dateRangeValue[0]);
                    $('#dateRange').data('daterangepicker').setEndDate(dateRangeValue[1]);
                }
            }

            // Для unique_user_id_updated_at
            if (urlParams.has("unique_user_id_updated_at")) {
                let updatedDateRangeValue = urlParams.get("unique_user_id_updated_at").split(' - ');

                if (updatedDateRangeValue.length == 2) {
                    $('#unique_user_id_updated_at').val(updatedDateRangeValue.join(' - '));
                    $('#unique_user_id_updated_at').data('daterangepicker').setStartDate(updatedDateRangeValue[0]);
                    $('#unique_user_id_updated_at').data('daterangepicker').setEndDate(updatedDateRangeValue[1]);
                }
            }
        });




        document.addEventListener("DOMContentLoaded", function () {
            const filterParams = ['sales_id', 'team_id', 'desk_id', 'status_prefixed_id', 'country_id', 'unique_created_at', 'unique_user_id_updated_at', 'retention_status_id'];
            const urlParams = new URLSearchParams(window.location.search);
            let hasFilterParams = false;

            // Проверяем, есть ли хотя бы один параметр фильтрации в URL
            for (let filter of filterParams) {
                if (urlParams.has(filter) || urlParams.has(filter + "[]")) {
                    hasFilterParams = true;
                    break;
                }
            }

            // Если есть параметры фильтрации, устанавливаем значения для соответствующих select элементов
            if (hasFilterParams) {
                urlParams.forEach((value, key) => {
                    if (key.endsWith("[]")) {
                        let realKey = key.slice(0, -2);  // Убираем "[]" из ключа
                        const selectElement = document.querySelector(`select[name="${realKey}[]"]`);

                        if (selectElement) {
                            // Сначала сбросим все выбранные элементы
                            selectElement.querySelectorAll('option').forEach(opt => {
                                opt.selected = false;
                            });

                            // Превращаем все значения параметра в массив
                            let values = urlParams.getAll(realKey + "[]");
                            values.forEach(val => {
                                let option = selectElement.querySelector(`option[value="${val}"]`);
                                if (option) {
                                    option.selected = true;
                                }
                            });
                        }
                    } else {
                        // Обработка обычных параметров (не массивов)
                        const selectElement = document.querySelector(`select.filter-select[name="${key}"]`);
                        if (selectElement) {
                            selectElement.value = value;
                        }
                    }
                });
            } else {
                // Если параметров фильтрации в URL нет, устанавливаем значения по умолчанию для всех select элементов
                const selectElements = document.querySelectorAll('select.filter-select');
                selectElements.forEach(select => {
                    if (select.getAttribute('name').endsWith('[]')) {
                        // Если это множественный select, убедимся, что ничего не выбрано
                        select.querySelectorAll('option').forEach(option => {
                            option.selected = false;
                        });
                    } else {
                        select.value = "0";
                    }
                });
            }
        });



        const form = document.getElementById('yourFormId'); // замените 'yourFormId' на реальный ID вашей формы

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(form);
            const searchParams = new URLSearchParams();

            formData.forEach((value, key) => {
                // Если ключ уже есть в searchParams, удаляем его
                if (searchParams.has(key)) {
                    searchParams.delete(key);
                }
                // Добавляем каждое значение как отдельный параметр
                formData.getAll(key).forEach(val => {
                    searchParams.append(key + '[]', val);
                });
            });

            // Перенаправляем на новый URL
            window.location.href = window.location.pathname + '?' + searchParams.toString();
        });

    </script>
@endpush
