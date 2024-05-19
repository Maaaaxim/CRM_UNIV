<script>
    // alert(111);
    document.querySelectorAll('.buttonForAddingNotification').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            // Получение данных из атрибутов кнопки
            const notificationDate = document.getElementsByName('notificationDate')[0].value;
            const notificationInfo = document.getElementsByName('notificationInfo')[0].value;
            const pathArray = window.location.pathname.split('/');
            const leadId = pathArray[pathArray.length - 1];

            if (notificationDate && notificationInfo) {
                addNotification(notificationDate, notificationInfo, leadId);
            } else {
               alert('Заполните поля!')
            }
            
        });
    });

    function addNotification(notificationDate, notificationInfo, leadId) {
        @include('parts.всплывашки.error', ['message' => 'Ошибка'])
        @include('parts.всплывашки.success', ['message' => 'Уведомление добавлено!'])

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        fetch('/addNotification', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                notificationDate,
                notificationInfo,
                leadId,
            })
        })
            .then(response => response.json())
            .then(data => {
                document.getElementsByName('notificationDate')[0].value = '';
                document.getElementsByName('notificationInfo')[0].value = '';

                fetchForNotification();

                addNotificationToView(data.notificationId, notificationDate, notificationInfo);

                console.log('data: ' + data)

                document.body.insertAdjacentHTML('afterbegin', cardHtmlSuccess);
                setTimeout(() => {
                    const successCard = document.getElementById('successCard');
                    if (successCard) {
                        successCard.remove();
                    }
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                document.body.insertAdjacentHTML('afterbegin', cardHtmlError);
                setTimeout(() => {
                    const errorCard = document.getElementById('errorCard');
                    if (errorCard) {
                        errorCard.remove();
                    }
                }, 1000);
            });
    }

    function formatNotificationDate(dateString) {
        // Разбиваем входную строку по пробелу на дату и время
        var parts = dateString.split(' ');
        var dateParts = parts[0].split('.');
        var time = parts[1];

        // Переупорядочиваем части даты в формат YYYY-MM-DD
        var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0];

        return formattedDate + ' ' + time + ':00'; // Добавляем секунды, если они нужны
    }

    function addNotificationToView(notificationId, notificationDate, notificationInfo) {
        // Преобразуем формат даты
        var formattedDate = formatNotificationDate(notificationDate);

        // Создаем ссылку для удаления уведомления, если у нас есть ID
        var deleteLink = notificationId ?
            '<a href="/deleteNotification/' + notificationId + '">' +
            '<i class="bi bi-trash" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%);"></i>' +
            '</a>' : '';

        // Создаем HTML-структуру нового уведомления
        const newNotificationHtml = '<div class="external-event-wrapper" style="position: relative;">' +
            '<div class="external-event bg-warning ui-draggable ui-draggable-handle">' +
            formattedDate + ' | ' + notificationInfo +
            '</div>' + deleteLink +
            '</div>';

        // Находим label внутри контейнера и добавляем новое уведомление после него
        var labelElement = document.querySelector('.notification-container label');
        labelElement.insertAdjacentHTML('afterend', newNotificationHtml);
    }


</script>
