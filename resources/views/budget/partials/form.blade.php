<!-- Modal -->
<div class="modal fade" id="newBudget" tabindex="-1" role="dialog" aria-labelledby="newBudget" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Intrare noua buget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newBudgetForm" action="/budget/add" method="post">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-lg-5">
                            <label for="group">Grupa produs</label>
                            <select class="form-control" name="group" id="group">
                                <option>-- Selecteaza o grupa de produs --</option>
                                @foreach ($group as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="year">An</label>
                            <select class="form-control" name="year" id="year">
                                <option value="{{ $year }}">{{ $year }}</option>
                                <option value="{{ $year - 1 }}">{{ $year - 1 }}</option>
                            </select>
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="week">Saptamana</label>
                            <select class="form-control" name="week" id="week">
                                @foreach ($weeks as $week)
                                    <option value="{{ $week }}">{{ $week }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                          <label for="volume">Volum</label>
                          <input type="number"
                            class="form-control" name="volume" id="volume" placeholder="" min="1" step="0.001">
                        </div>
                        <div class="form-group col-lg-12" id="error-msg" style="color: red"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Inchide</button>
                        <div id="submit"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
