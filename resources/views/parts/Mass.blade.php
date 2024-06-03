<div class="col-md-4">
    <div class="card card-primary collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Масові дії</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="display: none;">
            <div class="form-group">
                @can('assign lead')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-default">
                        Видати сейлу
                    </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" onclick="submitLeads()">
                        Розподілити лідів
                    </button>
                @endcan
                @can('delete lead')
                    <button type="button" class="btn btn-danger"
                            id="deleteMass"
                            data-dismiss="modal">Видалити
                    </button>
                @endcan
            </div>
            @include('parts.ModalMassAppointment', ['users' =>$users])
            {{-- @include('parts.ModalMassDistribution', ['users' =>$users]) --}}

            <div class="form-group">
                <label for="inputStatus">Змінити статус</label>

                <select id="inputStatus" class="form-control custom-select">
                    <option value="0">Всі</option>

                    @php
                        $isFirstRetention = true;
                        $isTeamLead = auth()->user()->hasRole('teamlead');
                        $isRetentionTeamLead = auth()->user()->hasRole('retention_teamlead');
                        $showStatuses = true; // для початку відображаємо всі статуси
                        $hideStatuses = $isRetentionTeamLead; // ховаємо статуси для retention_teamlead до роздільника
                    @endphp

                    @foreach($all_statuses as $status)
                        @if($isFirstRetention && Str::startsWith($status->prefixed_id, 'retention_'))
                            <option disabled>!retention!</option>

                            @php
                                $isFirstRetention = false;
                                if ($isTeamLead) {
                                    $showStatuses = false; // перестаємо відображати статуси для teamlead після роздільника
                                }
                                if ($isRetentionTeamLead) {
                                    $hideStatuses = false; // починаємо відображати статуси для retention_teamlead після роздільника
                                }
                            @endphp
                        @endif

                        @if($showStatuses && !$hideStatuses)
                            <option value="{{ $status->prefixed_id }}">
                                {{ $status->name }}
                            </option>
                        @endif
                    @endforeach
                </select>

            </div>
            {{--            <div class="form-group">--}}
            {{--                <div class="custom-control custom-checkbox">--}}
            {{--                    <input type="checkbox" class="custom-control-input" id="selectAllLeads">--}}
            {{--                    <label class="custom-control-label" for="selectAllLeads">Вибрати всіх лідів у базі</label>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            {{--            <button type="button" id="submitFormBtn" class="btn btn-primary">Зберегти</button>--}}
            <button type="button" id="submitFormBtnTrue" class="btn btn-primary">Зберегти</button>

        </div>
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('submitFormBtn').addEventListener('click', function () {
                document.getElementById('isMass').value = 'true';
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const massActionStatusSelect = document.getElementById("inputStatus");

            massActionStatusSelect.addEventListener('change', function () {

                const selectedStatusId = massActionStatusSelect.value;

                // Получаем все отмеченные чекбоксы
                const checkedLeads = document.querySelectorAll('.lead-checkbox:checked');

                // Для каждого отмеченного чекбокса находим соответствующий ему селект и меняем значение
                checkedLeads.forEach(checkbox => {
                    const leadRow = checkbox.closest('tr');
                    if (leadRow) {
                        let str = "lead-row-10447";
                        let parts = leadRow.id.split('-');
                        let id = parts[parts.length - 1];

                        let selectedLeadsInput = document.getElementById('selectedLeads');
                        let selectedLeadsValue = selectedLeadsInput.value;

                        let selectedLeads = selectedLeadsValue ? JSON.parse(selectedLeadsValue) : [];

                        selectedLeads.push(id);

                        selectedLeadsInput.value = JSON.stringify(selectedLeads);

                        try {
                            const leadStatusSelect = leadRow.querySelector('select[name^="lead_combined_statuses["]');
                            if (leadStatusSelect) {
                                leadStatusSelect.value = selectedStatusId;
                            }
                        } catch (e) {

                        }

                        try {
                            const leadStatusSelect2 = leadRow.querySelector('select[name^="lead_statuses["]');
                            leadStatusSelect2.value = parseInt(selectedStatusId.split("_")[1]);
                        } catch (e) {

                        }
                        try {
                            const leadStatusSelect3 = leadRow.querySelector('select[name^="lead_retention_statuses["]');
                            leadStatusSelect3.value = parseInt(selectedStatusId.split("_")[1]);
                        } catch (e) {

                        }
                    }
                });
            });
        });


        //массовое удаление лидов
        document.addEventListener("DOMContentLoaded", function () {
            const deleteMassBtn = document.getElementById("deleteMass");

            deleteMassBtn.addEventListener('click', function () {
                const checkedLeads = document.querySelectorAll('.lead-checkbox:checked');

                const leadIds = Array.from(checkedLeads).map(checkbox => checkbox.value);

                if (!leadIds.length) {
                    alert("Выберите лидов для удаления!");
                    return;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/deleteMassLeads';

                // Добавляем _method field для поддержки DELETE
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);

                // CSRF token
                const csrfField = document.createElement('input');
                csrfField.type = 'hidden';
                csrfField.name = '_token';
                csrfField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfField);

                // Добавляем все идентификаторы лидов
                leadIds.forEach(id => {
                    const hiddenField = document.createElement('input');
                    hiddenField.type = 'hidden';
                    hiddenField.name = 'leadIds[]';
                    hiddenField.value = id;
                    form.appendChild(hiddenField);
                });

                document.body.appendChild(form);
                form.submit();
            });
        });

        // массовое добавление одному сейлу
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('massActionForm');
            var leadsCheckbox = document.querySelectorAll('.lead-checkbox');
            var assignedLeadsInput = document.getElementById('assignedLeadsInput');

            form.addEventListener('submit', function (event) {
                var selectedLeads = [];

                leadsCheckbox.forEach(function (checkbox) {
                    if (checkbox.checked) {
                        selectedLeads.push(checkbox.value);
                    }
                });

                if (selectedLeads.length === 0) {
                    alert('Пожалуйста, выберите хотя бы одного лида.');
                    event.preventDefault();
                    return false;
                }

                if (!document.getElementById('userSelect').value) {
                    alert('Пожалуйста, выберите пользователя.');
                    event.preventDefault();
                    return false;
                }

                assignedLeadsInput.value = selectedLeads.join(',');
            });
        });

        // отправление массива выбраных лидов для дальнейшего распределения
        function submitLeads() {
            const checkedCheckboxes = document.querySelectorAll('.lead-checkbox-td:checked');
            const leadIds = Array.from(checkedCheckboxes).map(checkbox => checkbox.value);


            if (!leadIds.length) {
                alert('Пожалуйста, выберите хотя бы одного лида для передачи.');
                return;
            }

            // Отправляем данные через POST-запрос
            const form = document.createElement('form');
            document.body.appendChild(form);
            form.method = 'post';
            form.action = '{{ route("leadsDistributionPage") }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            leadIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'assigned_leads[]';
                input.value = id;
                form.appendChild(input);
            });

            form.submit();
        }

        // submit leadsFormPlus
        document.getElementById('submitFormBtn').addEventListener('click', function () {
            document.getElementById('leadsFormPlus').submit();
        });

    </script>
@endpush
