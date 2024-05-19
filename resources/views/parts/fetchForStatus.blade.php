<script>
    const showLeadsUrl = "{{ route('showLeads') }}";

    function setStatus(leadId, element) {
        const selectedStatus = element.value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (selectedStatus == 24) {
            const leadRow = document.getElementById('lead-row-' + leadId);
            leadRow.style.display = "none";
        }

        fetch('/setStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // добавляем CSRF-токен
            },
            body: JSON.stringify({
                lead_id: leadId,
                status: selectedStatus
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.message) {
                    window.location.href = showLeadsUrl;
                }
                console.log('Success:', data);

                const tdElement = document.querySelector(`td[data-lead-id="${data.leadId}"]`);
                if (tdElement) {
                    tdElement.style.backgroundColor = data.color;
                } else {
                    console.error(`Element with leadId ${data.leadId} not found.`);
                }
            })

            .catch(error => {
                console.error('Error:', error);
                // обработка ошибки, если необходимо
            });
    }

    function setRetentionStatus(selectElement) {
        const leadId = selectElement.getAttribute('data-id');
        const newStatusId = selectElement.value;
        fetch('/setRetentionStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                leadId: leadId,
                retentionStatusId: newStatusId
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.message) {
                    window.location.href = showLeadsUrl;
                }
                console.log(data.message);

                // Находим соответствующий <td> элемент по атрибуту data-lead-id и устанавливаем его цвет фона
                const tdElement = document.querySelector(`td[data-lead-id="${leadId}"]`);
                if (tdElement && data.color) {
                    tdElement.style.backgroundColor = data.color;
                }
            })
            .catch(error => {
                console.error('Error updating retention status:', error);
            });
    }


    function setCombinedStatus(leadId, selectElement) {
        const selectedStatus = selectElement.value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        if (selectedStatus === 'status_24') {
            selectElement.value = 'retention_1';
        }

        fetch('/setCombinedStatus', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                leadId: leadId,
                selectedStatus: selectedStatus,
                curUser: curUser,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (!data.message) {
                    window.location.href = showLeadsUrl;
                }
                console.log(data.message);

                // Находим соответствующий <select> элемент и обновляем его значение
                const userSelect = document.querySelector(`select[name="lead_user[${leadId}]"]`);
                if (userSelect) {
                    userSelect.value = data.userId;
                }

                // Находим соответствующий <td> элемент по атрибуту data-lead-id и устанавливаем его цвет фона
                const tdElement = document.querySelector(`td[data-lead-id="${leadId}"]`);
                if (tdElement && data.color) {
                    tdElement.style.backgroundColor = data.color;
                }
            });

    }
</script>
