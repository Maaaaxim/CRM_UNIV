<div class="col-md-6">
    <form method="GET" action="{{ route('leadsDistributionPage') }}">
        <input type="hidden" name="page" value="{{ request('page', 1) }}">
        <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Фільтри</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="display: block;">

                <div class="form-group row">

                    <div class="col-md-6">
                        <label for="inputStatus">Деск</label>
                        <select id="deskFilter" name="desk_id" class="form-control custom-select filter-select">
                            <option value="0">Всі</option>
                            @foreach($desks as $desk)
                                <option
                                    value="{{ $desk->desk_id }}">
                                    {{ $desk->desk }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="inputStatus">Команда</label>
                        <select id="teamFilter" name="team_id"
                                class="form-control custom-select filter-select">
                            <option value="0">Всі</option>
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
                        <a href="{{ route('leadsDistributionPage', array_filter(['page' => request('page'), 'per_page' => request('per_page')])) }}"
                           class="btn btn-primary w-100">Скинути фільтри</a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary w-100 mb-3">Застосувати фільтри</button>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script>

        {{--костыль, в для инпутов--}}
        document.addEventListener("DOMContentLoaded", function () {
            const filterParams = ['sales_id', 'team_id', 'desk_id'];
            const urlParams = new URLSearchParams(window.location.search);
            let hasFilterParams = false;

            // Проверяем, есть ли хотя бы один параметр фильтрации в URL
            for (let filter of filterParams) {
                if (urlParams.has(filter)) {
                    hasFilterParams = true;
                    break;
                }
            }

            if (hasFilterParams) {
                // Если есть параметры фильтрации, устанавливаем значения для соответствующих select элементов
                urlParams.forEach((value, key) => {
                    const selectElement = document.querySelector(`select.filter-select[name="${key}"]`);
                    if (selectElement) {
                        selectElement.value = value;
                    }
                });
            } else {
                // Если параметров фильтрации в URL нет, устанавливаем значения по умолчанию для всех select элементов
                const selectElements = document.querySelectorAll('select.filter-select');
                selectElements.forEach(select => {
                    select.value = "0";
                });
            }
        });

    </script>
@endpush
