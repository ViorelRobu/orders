<!-- Modal -->
<div class="modal fade" id="addFields" tabindex="-1" role="dialog" aria-labelledby="addFields" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Definire campuri detalii comanda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="addFieldsForm">
                    @method('POST')
                    @csrf
                    <div class="form-group">
                        <div class="form-group">
                            <label for="details_fields">Campuri</label>
                            <input type="text" class="form-control" name="details_fields" id="details_fields" rows="3">
                            <small id="helpId" class="form-text text-muted">
                                Numele campurilor trebuie sa fie separate de <strong>"|"</strong> si denumirea aceluiasi camp trebuie legat cu <strong>"_"</strong>.
                                <br>
                                Exemplu: sticker|cod_ean
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div id="submit">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuleaza</button>
                            <button type="submit" class="btn btn-primary" id="save_fields_details">Salveaza</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
