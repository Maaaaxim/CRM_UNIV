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
            <h3 class="card-title">Дії</h3>
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
                        <h4 class="modal-title">Інструкція</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>У блоці “дії” ви побачите наступні елементи:</strong></p>
                        <ul>
                            <li><strong>Розподілити по виділеним:</strong> кнопка, що дозволяє рівномірно розподілити ліди між обраними сейлами.</li>
                            <li><strong>Інструкція:</strong> кнопка для виклику цього модального вікна з інструкціями.</li>
                            <li><strong>Ви вибрали:</strong> плашка, що показує кількість обраних лідів.</li>
                        </ul>
                        <p><strong>Інструкція по розподілу лідів:</strong></p>
                        <ol>
                            <li>У таблиці нижче виберіть сейлів, між якими хочете розподілити ліди, встановивши галочки.</li>
                            <li>Натисніть на кнопку “Розподілити по виділеним”. Ліди рівномірно розподіляться між обраними сейлами, і кількість лідів на одного сейла буде виведено в колонці “Кількість лідів”.</li>
                            <li>Якщо хочете змінити кількість лідів, яке було розподілено автоматично, ви можете змінити значення в інпутах у колонці “Кількість лідів”.</li>
                            <li>Також ви можете розподілити ліди вручну, вказавши в інпутах у колонці “Кількість лідів” кількість лідів, яке хочете видати кожному сейлу.</li>
                            <li>Щоб зміни набули чинності, натисніть кнопку "Зберегти".</li>
                        </ol>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрити</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body" style="display: block;">
            <div class="form-group">
                <button type="button" class="btn btn-primary" data-toggle="modal">
                    Розподілити по виділеним
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#instruction-modal">
                    Інструкція
                </button>
                <span class="info-badge">Ви вибрали: {{ count($leadsArray) }} лідів</span>
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
