<!-- Modal -->
<div class="modal fade" id="printOrders" tabindex="-1" role="dialog" aria-labelledby="printOrders" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Imprima comenzi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="/orders/print/multiple" method="post">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="form-group col-lg-6">
                          <label for="start">Comanda de inceput</label>
                          <input type="text"
                            class="form-control" name="start" id="start" placeholder="Comanda de inceput" required>
                        </div>
                        <div class="form-group col-lg-6">
                          <label for="start">Comanda de final</label>
                          <input type="text"
                            class="form-control" name="end" id="end" placeholder="Comanda de final" required>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="orientation" value="landscape"> Landscape
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="orientation" value="portrait" checked> Portret
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Inchide</button>
                        <button type="submit" class="btn btn-primary">PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
