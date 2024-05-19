<script>
    document.querySelectorAll('.buttonForAddingValue').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault();

            const leadId = button.dataset.leadId;
            const valueSelector = `textarea[name="lead_payments[${leadId}]"]`;
            const value = document.querySelector(valueSelector).value;

            document.querySelector(valueSelector).value = '';

            addValue(leadId, value);
        });
    });

    function addValue(leadId, value) {
        @include('parts.всплывашки.error', ['message' => 'Ошибка'])
        @include('parts.всплывашки.success', ['message' => 'Платёж добавлен'])

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


        fetch('/addValueFetch', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                leadId: leadId,
                value: value,
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data[0] === false) {
                    throw new Error('Ошибка! Вы ввели некорректный символ!');
                } else {
                    document.getElementById(`lead-${leadId}-value`).textContent = parseFloat(data.value).toFixed(2) + '$';
                    document.body.insertAdjacentHTML('afterbegin', cardHtmlSuccess);
                    setTimeout(() => {
                        const successCard = document.getElementById('successCard');
                        if (successCard) {
                            successCard.remove();
                        }
                    }, 1000);
                }
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
