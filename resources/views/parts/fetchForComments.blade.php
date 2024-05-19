<script>
    document.querySelectorAll('.buttonForAddingComments').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            // Получение данных из атрибутов кнопки
            const leadId = button.dataset.leadId;
            const userId = button.dataset.userId;
            const commentSelector = `textarea[name="lead_notes[${leadId}]"]`;
            const comment = document.querySelector(commentSelector).value;

            // Очистка поля ввода комментария
            document.querySelector(commentSelector).value = '';

            // Вызов функции для добавления комментария
            addComment(leadId, userId, comment);
        });
    });

    function addComment(leadId, userId, comment) {
        @include('parts.всплывашки.error', ['message' => 'Ошибка'])
        @include('parts.всплывашки.success', ['message' => 'Коментарий добавлен'])

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        fetch('/addCommentFetch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                leadId: leadId,
                userId: userId,
                comment: comment,
            })
        })
            .then(response => response.json())
            .then(data => {
                fetchComments(leadId)
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

</script>
