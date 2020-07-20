<!-- Modal -->
<div class="modal fade" id="newProduct" tabindex="-1" role="dialog" aria-labelledby="newProduct" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tip produs nou</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newProductForm" action="/products/add" method="post">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="">Tip produs</label>
                            <input type="text"
                                   class="form-control" name="name" id="name" aria-describedby="helpId" placeholder="Tip produs" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Inchide</button>
                        <button type="submit" id="save" class="btn btn-primary">Creaza</button>
                        <button type="submit" id="update" style="display:none" class="btn btn-primary hidden">Modifica</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
