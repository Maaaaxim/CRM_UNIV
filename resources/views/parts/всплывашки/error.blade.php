const cardHtmlError = `
<div class="d-flex justify-content-center align-items-center card-container" id="errorCard">
    <div class="card bg-danger col-md-6" id="myCard">
        <div class="overlay">
            <div class="card-container">
                <div class="card bg-danger">
                    <div class="card-header">
                        <h3 class="card-title">Ужас!</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                    class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        {{$message}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`;
