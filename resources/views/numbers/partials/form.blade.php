<!-- Modal -->
<div class="modal fade" id="newNumber" tabindex="-1" role="dialog" aria-labelledby="newNumber" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Numar de comanda nou</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newNumberForm" action="/numbers/add" method="post">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="start_number">Numar de comanda</label>
                            <input type="number"
                                   class="form-control" name="start_number" id="start_number" aria-describedby="start_number" placeholder="Numar de comanda" min="0" step="1" required>
                        </div>
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
