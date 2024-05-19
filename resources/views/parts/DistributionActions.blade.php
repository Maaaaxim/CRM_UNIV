@push('styles')
    <style>
        .info-badge {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 5px 15px;
            border-radius: 20px;
        }
    </style>
@endpush
<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Действия</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="modal fade" id="instruction-modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Инструкция</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>В блоке “действия” вы увидите следующие элементы:</strong></p>
                        <ul>
                            <li><strong>Распределить по выделенным:</strong> кнопка, позволяющая равномерно распределить лиды между выбранными сейлами.</li>
                            <li><strong>Инструкция:</strong> кнопка для вызова этого модального окна с инструкциями.</li>
                            <li><strong>Вы выбрали:</strong> плашка, показывающая количество выбранных лидов.</li>
                        </ul>
                        <p><strong>Инструкция по распределению лидов:</strong></p>
                        <ol>
                            <li>В таблице ниже выберите сейлов, между которыми хотите распределить лиды, установив галочки.</li>
                            <li>Нажмите на кнопку “Распределить по выделенным”. Лиды равномерно распределятся между выбранными сейлами, и количество лидов на одного сейла будет выведено в колонке “Количество лидов”.</li>
                            <li>Если хотите изменить количество лидов, которое было распределено автоматически, вы можете изменить значения в инпутах в колонке “Количество лидов”.</li>
                            <li>Также вы можете распределить лиды вручную, указав в инпутах в колонке “Количество лидов” количество лидов, которое хотите выдать каждому сейлу.</li>
                            <li>Чтобы изменения вступили в силу, нажмите кнопку "Сохранить".</li>
                        </ol>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="card-body" style="display: block;">
            <div class="form-group">
                <button type="button" class="btn btn-primary" data-toggle="modal">
                    Распределить по выделенным
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#instruction-modal">
                    Инструкция
                </button>
                <span class="info-badge">Вы выбрали: {{ count($leadsArray) }} лидов</span>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        var leadsArray = @json($leadsArray);

        // Отслеживание изменений инпутов
        document.querySelectorAll('.leads-input').forEach(input => {
            input.addEventListener('input', function () {
                const userId = this.closest('tr').querySelector('.lead-checkbox-td').value;
                updateHiddenFieldsForUser(userId, this.value);
            });
        });

        function updateHiddenFieldsForUser(userId, leadsCount) {
            const usersContainer = document.getElementById('user-leads-hidden-fields');
            let input = usersContainer.querySelector(`input[name="user_leads[${userId}]"]`);

            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = `user_leads[${userId}]`;
                usersContainer.appendChild(input);
            }

            input.value = leadsCount;
        }

        function distributeLeadsAutomatically() {
            const selectedUsers = document.querySelectorAll('.lead-checkbox-td:checked');
            const leadsPerUser = Math.floor(leadsArray.length / selectedUsers.length);

            selectedUsers.forEach((checkbox, index) => {
                const parentRow = checkbox.closest('tr');
                const leadsInput = parentRow.querySelector('.leads-input');

                if (index === selectedUsers.length - 1) {
                    leadsInput.value = leadsPerUser + (leadsArray.length % selectedUsers.length);
                } else {
                    leadsInput.value = leadsPerUser;
                }

                updateHiddenFieldsForUser(checkbox.value, leadsInput.value);
            });
        }

        function addHiddenFieldsForLeads() {
            const leadsContainer = document.getElementById('leads-hidden-fields');
            leadsContainer.innerHTML = ''; // Очистка контейнера

            leadsArray.forEach(leadId => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'leads[]';
                input.value = leadId;
                leadsContainer.appendChild(input);
            });
        }

        // Обработчик события для кнопки "Распределить"
        document.querySelector('.btn.btn-primary[data-toggle="modal"]').addEventListener('click', function () {
            distributeLeadsAutomatically();
            addHiddenFieldsForLeads();
        });
    </script>
@endpush
