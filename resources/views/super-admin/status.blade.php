@extends('layouts.main-layout')

@push('styles')
    <style>
        .palette {
            display: grid;
            grid-template-columns: repeat(4, 30px);
            grid-template-rows: repeat(4, 30px);
            gap: 5px;
        }

        .color {
            width: 30px;
            height: 30px;
            cursor: pointer;
        }

        /*.color-picker {*/
        /*    position: absolute;*/
        /*    display: none;*/
        /*}*/

    </style>
@endpush

@section('content')

    <div class="content-wrapper">
        @can('create status')
            <section class="content">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Створити статус</h3>
                    </div>

                    <form method="post" action="{{route('createStatus')}}">

                        @csrf

                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Назва</label>
                                <input type="name" class="form-control" id="name" placeholder="Статус"
                                       name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="color">Колір статусу</label>
                                <div id="color" class="palette"></div>
                                <input type="hidden" id="colorInput" name="color" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Створити</button>
                        </div>
                    </form>
                </div>
            </section>
        @endcan
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Статуси</h3>
                </div>

                <div class="card-body">
                    <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                           aria-describedby="example2_info">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Статус</th>
                            @can('delete status')
                                <th>Видалити</th>
                            @endcan
                            <th>Колір</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($statuses as $status)
                            <tr class="even">
                                <td class="sorting_1 dtr-control">{{ $status->id }}</td>
                                <td class="sorting_1 dtr-control">{{ $status->name }}</td>
                                @can('delete status')
                                    <td>
                                        <a href="{{route('deleteStatus', ['id'=>$status->id])}}">
                                            <button type="button" class="btn btn-danger">
                                                Видалити статус
                                            </button>
                                        </a>
                                    </td>
                                @endcan
                                @can('change color')
                                    <td data-id="{{ $status->id }}" class="color-cell" onclick="openColorPicker(event)"
                                        style="background-color: {{ $status->color }}; cursor: pointer; position:relative;">
                                        <input type="color" class="color-picker" onchange="applyColor(event)">
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openColorPicker(event) {
            let colorPicker = event.currentTarget.querySelector('.color-picker');
            colorPicker.style.display = 'block';
            colorPicker.click();
        }

        function applyColor(event) {
            let colorPicker = event.currentTarget;
            let cell = colorPicker.parentElement;
            let statusId = cell.getAttribute('data-id'); // отримуємо id статусу
            let newColor = colorPicker.value;

            // встановлюємо новий колір для ячейки
            cell.style.backgroundColor = newColor;
            // colorPicker.style.display = 'none';

            // відправляємо POST-запит на сервер
            fetch('/setColor', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    // додайте CSRF-токен для захисту вашого запиту, якщо це необхідно
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: statusId,
                    color: newColor
                })
            })
                .then(response => response.json())
                .then(data => {
                    // обробляємо відповідь сервера
                    console.log(data);
                })
                .catch(error => {
                    // обробляємо можливі помилки
                    console.error(error);
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            var palette = document.getElementById('color');
            var colors = [
                'rgba(255, 0, 0, 0.5)', 'rgba(0, 255, 0, 0.5)', 'rgba(0, 0, 255, 0.5)',
                'rgba(255, 255, 0, 0.5)', 'rgba(0, 255, 255, 0.5)', 'rgba(255, 0, 255, 0.5)',
                'rgba(128, 0, 0, 0.5)', 'rgba(0, 128, 0, 0.5)', 'rgba(0, 0, 128, 0.5)',
                'rgba(128, 128, 0, 0.5)', 'rgba(0, 128, 128, 0.5)', 'rgba(128, 0, 128, 0.5)',
                'rgba(192, 192, 192, 0.5)', 'rgba(128, 128, 128, 0.5)', 'rgba(0, 0, 0, 0.5)',
                'rgba(255, 165, 0, 0.5)', 'rgba(0, 206, 209, 0.5)', 'rgba(148, 0, 211, 0.5)'
            ];

            colors.forEach(function (color) {
                var colorDiv = document.createElement('div');
                colorDiv.className = 'color';
                colorDiv.style.backgroundColor = color;
                colorDiv.addEventListener('click', function () {
                    document.getElementById('colorInput').value = color;
                    Array.from(palette.children).forEach(function (child) {
                        child.style.border = 'none';
                    });
                    colorDiv.style.border = '2px solid black';
                });
                palette.appendChild(colorDiv);
            });
        });
    </script>
@endpush
