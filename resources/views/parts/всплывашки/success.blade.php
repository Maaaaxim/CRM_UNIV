const cardHtmlSuccess = `
<div class="d-flex justify-content-center align-items-center card-container" id="successCard">
    <div class="card bg-success col-md-6">
        <div class="overlay">
            <div class="card-container">
                <div class="card bg-success">
                    <div class="card-header">
                        <h3 class="card-title">Успіх</h3>
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
