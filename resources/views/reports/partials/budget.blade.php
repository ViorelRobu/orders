<!-- Modal -->
<div class="modal fade" id="budget" tabindex="-1" role="dialog" aria-labelledby="budget" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exporta bugetul pentru anul</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="/reports/budget" method="get">
                    @csrf
                    @method('GET')
                    <div class="row">
                        <div class="form-group col-lg-12">
                          <label for="start">An</label>
                          <input type="number"
                            class="form-control" name="year" id="year" step="1" min="2020" required>
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
