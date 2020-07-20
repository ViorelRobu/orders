<!-- Modal -->
<div class="modal fade" id="newRefinement" tabindex="-1" role="dialog" aria-labelledby="newRefinement" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Finisaj nou</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newRefinementForm" action="/quality/add" method="post">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="">Finisaj</label>
                            <input type="text"
                                   class="form-control" name="name" id="name" aria-describedby="helpId" placeholder="Finisaj" required>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="">Descriere</label>
                            <input type="text"
                                   class="form-control" name="description" id="description" aria-describedby="helpId" placeholder="Descriere" required>
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
