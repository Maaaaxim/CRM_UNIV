<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Распределение cейлам</h3>
        </div>

        <div class="card-body">
            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                {{--массовые действия--}}
                <div class="row">
                    @include('parts.DistributionActions', [])
                    @include('parts.DistributionFIlters', ['teams'=>$teams, 'desks'=>$desks])

                </div>
                {{--конец массовых действий--}}

                <form action="{{ route('distributionleadsAssign')}}" method="post" id="leadsFormPlus">

                    @csrf

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="card-body table-responsive p-0">
                            </div>
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th><input type="checkbox"
                                               class="lead-checkbox"
                                               name="assigned_leads[]"></th>
                                    <th>Имя</th>
                                    <th>Роль
                                    </th>
                                    <th>Деск
                                    </th>
                                    <th>Команда</th>
                                    <th>Текущее количество лидов</th>
                                    <th>Количество лидов</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td><input type="checkbox" id="lead_{{ $user->id }}"
                                                   class="lead-checkbox lead-checkbox-td"
                                                   name="assigned_leads[]" value="{{ $user->id }}"></td>
                                        <td><a href=""> {{ $user->name }}</a></td>

                                        <td>{{$user->role}}</td>
                                        <td>{{$user->desk ? $user->desk->desk : 'Нет деска'}}</td>
                                        <td>{{$user->team ? $user->team->team : 'Нет команды'}}</td>
                                        <td> {{$user->userLeadsCount ? $user->userLeadsCount : 'Нет лидов'}}</td>
                                        <td><input type="text" class="form-control leads-input"></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                    <div id="leads-hidden-fields">
                        @foreach($leadsArray as $leadId)
                            <input type="hidden" name="leads[]" value="{{ $leadId }}">
                        @endforeach
                    </div>

                    <div id="user-leads-hidden-fields"></div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>

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

            // функция для сортировки
            function sortHandler(column) {
                let sortOrder = 'asc'; // начальный порядок сортировки
                const urlParams = new URLSearchParams(window.location.search);

                if (urlParams.has('sortBy') && urlParams.get('sortBy') === column) {
                    sortOrder = urlParams.get('sortOrder') === 'asc' ? 'desc' : 'asc';
                } else {
                    urlParams.set('sortBy', column);
                }

                urlParams.set('sortOrder', sortOrder);
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
                sortHandler('date_attached'); // предполагая, что поле в базе данных называется 'date_attached'
            });
        });

        // сохранении пагинации при переадресации
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

    </script>
@endpush
