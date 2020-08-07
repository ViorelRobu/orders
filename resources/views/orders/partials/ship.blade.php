<!-- Modal -->
<div class="modal fade" id="loadingDate" tabindex="-1" role="dialog" aria-labelledby="loadingDate" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title">Data de incarcare</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form action="/orders/{{ $order->id }}/ship" method="POST">
                    <div class="form-group">
                        @method('PATCH')
                        @csrf
                        <label for="">Data de incarcare</label>
                        <input type="text"
                            class="form-control" name="loading_date" id="loading_date" placeholder="Data incarcare" autocomplete="off">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuleaza</button>
                            <button type="submit" class="btn btn-primary" id="load_truck">Salveaza</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
