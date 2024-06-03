<script>

    function changeCountry(leadId, element) {
        @include('parts.всплывашки.error', ['message' => 'Ошибка'])
        @include('parts.всплывашки.success', ['message' => 'Країна змінена'])
        const selectedCountry = element.value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/changeCountry', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken // добавляем CSRF-токен
            },
            body: JSON.stringify({
                leadId: leadId,
                selectedCountry: selectedCountry
            })
        })
            .then(response => response.json())
            .then(data => {

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
