<div class="modal fade" id="modal-default" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Выдача лидов</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form method="post" action="{{ route('massleadsAssign') }}" id="massActionForm">
                @csrf
                <div class="modal-body">


                    <label for="inputStatus">Выберите кому выдать выбранных лидов</label>
                    <select name="userId" id="userSelect"
                            class="form-control custom-select filter-select">
                        @foreach($users as $user)
                            <option
                                value="{{ $user->id }}">
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="assigned_leads" id="assignedLeadsInput">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Выдать</button>
                </div>
            </form>
        </div>

    </div>

</div>
