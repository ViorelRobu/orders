<!-- Modal -->
<div class="modal fade" id="delivery" tabindex="-1" role="dialog" aria-labelledby="delivery" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exporta livrari pentru perioada</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="/reports/deliveries" method="get">
                    @csrf
                    @method('GET')
                    <div class="row">
                        <div class="form-group col-lg-6">
                          <label for="start">Data de inceput</label>
                          <input type="date"
                            class="form-control" name="start" id="start" required>
                        </div>
                        <div class="form-group col-lg-6">
                          <label for="end">Data de final</label>
                          <input type="date"
                            class="form-control" name="end" id="end" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Inchide</button>
                        <button type="submit" class="btn btn-primary">Exporta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
