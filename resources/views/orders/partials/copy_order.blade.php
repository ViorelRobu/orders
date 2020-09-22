<!-- Modal -->
<div class="modal fade" id="copyOrder" tabindex="-1" role="dialog" aria-labelledby="copyOrder" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Copiaza comanda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="copyOrderForm" action="" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                      <label for="copies">Numar de copii</label>
                      <input type="number"
                        class="form-control" name="copies" id="copies" step="1" min="1" placeholder="Numarul de copii">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuleaza</button>
                        <button type="submit" class="btn btn-primary">Copiaza</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
