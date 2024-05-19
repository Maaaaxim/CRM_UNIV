<div class="col-md-4">
    <div class="card card-primary collapsed-card">
        <div class="card-header">
            <h3 class="card-title">Пошук</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body" style="display: none;">
            <div class="form-group">
                <label for="searchId">ID</label>
                <input type="text" class="form-control" id="searchId" placeholder="Введите ID">
            </div>
            <div class="form-group">
                <label for="searchName">Имя</label>
                <input type="text" class="form-control" id="searchName" placeholder="Введите Имя">
            </div>
            <div class="form-group">
                <label for="searchPhone">Телефон</label>
                <input type="text" class="form-control" id="searchPhone" placeholder="Введите Телефон">
            </div>
            <div class="form-group">
                <label for="searchEmail">Email</label>
                <input type="email" class="form-control" id="searchEmail" placeholder="Введите Email">
            </div>
            <div class="form-group">
                <label for="searchAffiliate">Афил</label>
                <input type="text" class="form-control" id="searchAffiliate" placeholder="Введите Афил">
            </div>
            <div class="form-group">
                <label for="searchAdvert">Реклама</label>
                <input type="text" class="form-control" id="searchAdvert" placeholder="Введите Рекламу">
            </div>
            <div class="row">
                <div class="col-md-3">
                    <button type="button" id="searchButton" class="btn btn-primary">Поиск</button>
                </div>
                <div class="col-md-9">
                    <a href="{{ route('showLeads', array_filter(['page' => request('page'), 'per_page' => request('per_page')])) }}"
                       class="btn btn-primary w-100">Сбросить фильтры и параметры поиска</a>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>

        const url = new URL(window.location.href);
        document.getElementById("searchId").value = url.searchParams.get("searchId");
        document.getElementById("searchName").value = url.searchParams.get("searchName");
        document.getElementById("searchPhone").value = url.searchParams.get("searchPhone");
        document.getElementById("searchEmail").value = url.searchParams.get("searchEmail");
        document.getElementById("searchAffiliate").value = url.searchParams.get("searchAffiliate");
        document.getElementById("searchAdvert").value = url.searchParams.get("searchAdvert");


        document.getElementById("searchButton").addEventListener("click", function () {
            const id = document.getElementById("searchId").value;
            const name = document.getElementById("searchName").value;
            const phone = document.getElementById("searchPhone").value;
            const email = document.getElementById("searchEmail").value;
            const affiliate = document.getElementById("searchAffiliate").value;
            const advert = document.getElementById("searchAdvert").value;

            console.log(affiliate);

            if (id) {
                url.searchParams.set("searchId", id);
            }
            if (name) {
                url.searchParams.set("searchName", name);
            }
            if (phone) {
                url.searchParams.set("searchPhone", phone);
            }
            if (email) {
                url.searchParams.set("searchEmail", email);
            }
            if (affiliate) {
                url.searchParams.set("searchAffiliate", affiliate);
            }
            if (advert) {
                url.searchParams.set("searchAdvert", advert);
            }

            location.href = url.href;
        });

    </script>

@endpush
