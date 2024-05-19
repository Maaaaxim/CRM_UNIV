@extends('layouts.main-layout')


@section('content')
    <div class="content-wrapper">
        @if(!$myLeads)
            @can('import leads')
                <form method="post" action="{{route('leadsImport')}}" enctype="multipart/form-data">

                    @csrf

                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputFile">Імпорт лідів</label>
                            <div class="input-group mb-3"> <!-- Добавлен класс mb-3 для отступа -->
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="exampleInputFile" name="leadsFile">
                                    <label class="custom-file-label" for="exampleInputFile">Вибрати файл</label>
                                </div>
                            </div>
                            <!-- Кнопка вынесена из <div class="card-footer"> -->
                            <div class="form-group">
                                <label for="defaultStatus">Статус для імпортованих лідів</label>
                                <select class="form-control" id="defaultStatus" name="defaultStatus">
                                    @foreach($all_statuses as $index => $status)
                                        <option value="{{ $status->prefixed_id }}">{{ $status->name }}</option>
                                        @if (isset($all_statuses[$index+1]) &&
                                             Str::startsWith($status->prefixed_id, 'status_') &&
                                             Str::startsWith($all_statuses[$index+1]->prefixed_id, 'retention_'))
                                            <option disabled>!retention!</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary">Імпортувати</button>
                    </div>
                </form>
            @endcan
        @endif
        @include('parts.table', ['leads'=>$leads, 'statuses'=>$statuses, 'users' =>$users,
        'countries'=>$countries, 'teams'=>$teams, 'desks'=>$desks, 'myLeads'=>$myLeads,
        'retention_statuses'=>$retention_statuses, 'all_statuses'=>$all_statuses, 'leadsCount'=>$leadsCount])

    </div>
@endsection

@push('scripts')

    <script>


        {{--отображение названия файла в инпуте для импорта--}}
        document.getElementById('exampleInputFile').addEventListener('change', function () {
            var fileName = event.target.files[0].name; // получаем название файла
            document.querySelector('.custom-file-label').innerText = fileName; // заменяем текст в label
        });

        // изменения размера textarea, если слово не помещяется
        document.addEventListener('DOMContentLoaded', function () {
            const dataInputs = document.querySelectorAll('.txt_one, .txt_two');

            function adjustTextareaHeight(textarea) {
                textarea.style.height = 'auto'; // сбрасываем высоту, чтобы получить полную высоту содержимого

                // Используем requestAnimationFrame для задержки перед установкой новой высоты, чтобы обеспечить плавный переход
                requestAnimationFrame(() => {
                    textarea.style.height = textarea.scrollHeight + 'px'; // задаем новую высоту
                });
            }

            function resizeTextareaOnFocus(event) {
                const target = event.target;
                adjustTextareaHeight(target);
            }

            function resetTextareaOnBlur(event) {
                const target = event.target;
                target.style.height = '50px';
            }

            dataInputs.forEach(input => {
                input.addEventListener('focus', resizeTextareaOnFocus);
                input.addEventListener('blur', resetTextareaOnBlur);
            });
        });
        // сабмит формы при при переходе на следующую страницу пагинации
        // document.querySelectorAll('.pagination a').forEach((link) => {
        //     link.addEventListener('click', (e) => {
        //         e.preventDefault();
        //
        //         // URL, на который был сделан клик (для пагинации)
        //         const clickedPaginationUrl = e.target.href;
        //
        //         const form = document.querySelector('#leadsFormPlus');
        //
        //         //скрытый input для передачи URL пагинации
        //         let hiddenInput = document.createElement('input');
        //         hiddenInput.type = 'hidden';
        //         hiddenInput.name = 'paginationUrl';
        //         hiddenInput.value = clickedPaginationUrl;
        //
        //         //Добавляется этот input к форме
        //         form.appendChild(hiddenInput);
        //
        //         form.submit();
        //     });
        // });

        //кастом пагинации, чтобы не изменялась ссылка
        // document.addEventListener('DOMContentLoaded', function () {
        //     let paginationLinks = document.querySelectorAll('.pagination a');
        //
        //     paginationLinks.forEach(link => {
        //         link.addEventListener('click', function (event) {
        //             event.preventDefault();
        //
        //             let currentUrl = window.location.href;
        //             let newPageNumber = this.textContent; // предполагается, что текст ссылки - это номер страницы
        //             let newUrl;
        //
        //             if (currentUrl.includes('&page=')) {
        //                 newUrl = currentUrl.replace(/(&page=)(\d+)/, `$1${newPageNumber}`);
        //             } else {
        //                 newUrl = currentUrl + `&page=${newPageNumber}`;
        //             }
        //
        //             window.location.href = newUrl;
        //         });
        //     });
        // });
        document.addEventListener('DOMContentLoaded', function () {
            let paginationLinks = document.querySelectorAll('.pagination a');

            paginationLinks.forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();

                    let currentUrl = window.location.href;
                    let newPageNumber = this.textContent;
                    let newUrl;

                    // Проверка наличия параметров в URL
                    if (currentUrl.includes('?')) {
                        // Если уже есть параметр &page=, заменяем его
                        if (currentUrl.includes('&page=')) {
                            newUrl = currentUrl.replace(/(&page=)(\d+)/, `$1${newPageNumber}`);
                        } else {
                            newUrl = currentUrl + `&page=${newPageNumber}`;
                        }
                    } else {
                        newUrl = currentUrl + `?page=${newPageNumber}`;
                    }

                    window.location.href = newUrl;
                });
            });
        });

    </script>
@endpush
