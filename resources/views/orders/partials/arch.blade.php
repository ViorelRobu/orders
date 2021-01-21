<!-- Modal -->
<div class="modal fade" id="archiveOrder" tabindex="-1" role="dialog" aria-labelledby="archiveOrder" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h5 class="modal-title">Arhiveaza comanda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="archiveOrderForm" action="/orders/{{ $order->id }}/archive" method="POST">
                    <div class="form-group">
                        @method('PATCH')
                        @csrf

                        <label for="comment">Comentariu</label>
                        <textarea class="form-control" name="comment" rows="3"></textarea>

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
