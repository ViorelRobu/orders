<!-- Modal -->
<div class="modal fade" id="addDetails" tabindex="-1" role="dialog" aria-labelledby="addDetails" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adauga pozitie noua</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="addDetailsForm" action="/orders/{{ $order->id }}/details/add" method="POST">
                    @method('POST')
                    @csrf
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="article_id">Articol</label>
                                <select class="form-control" name="article_id" id="article_id">
                                    @foreach ($articles as $article)
                                        <option value="{{ $article->id }}">{{ $article->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="refinements_list">Finisaje</label>
                                    <select class="form-control" name="refinements_list[]" id="refinements_list" multiple>
                                        @foreach ($refinements as $refinement)
                                            <option value="{{ $refinement->id }}">{{ $refinement->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="length">Lungime</label>
                                    <input type="number"
                                        class="form-control" name="length" id="length" placeholder="Lungime" min="0" max="8000" step="1">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="pcs">Bucati</label>
                                    <input type="number"
                                        class="form-control" name="pcs" id="pcs" placeholder="Bucati" min="0" max="500" step="1">
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="pal">Paleti</label>
                                    <input type="number"
                                        class="form-control" name="pal" id="pal" placeholder="Paleti" min="0" max="100" step="1">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="details_fields_data" class="row">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div id="submit">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuleaza</button>
                </div>
            </div>
        </div>
    </div>
</div>
