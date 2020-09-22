<!-- Modal -->
<div class="modal fade" id="editDetails" tabindex="-1" role="dialog" aria-labelledby="editDetails" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editeaza pozitie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <form id="editDetailsForm" action="/orders/{{ $order->id }}/details/update" method="POST">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="position" id="position">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-3">
                                <label for="edit_article_id">Articol</label>
                                <select class="form-control" name="edit_article_id" id="edit_article_id">
                                    @foreach ($articles as $article)
                                        <option value="{{ $article->id }}">{{ $article->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="edit_refinements_list">Finisaje</label>
                                    <select class="form-control" name="edit_refinements_list[]" id="edit_refinements_list" multiple>
                                        @foreach ($refinements as $refinement)
                                            <option value="{{ $refinement->id }}">{{ $refinement->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="edit_length">Lungime</label>
                                    <input type="number"
                                        class="form-control" name="edit_length" id="edit_length" placeholder="Lungime" min="0" max="8000" step="1">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="edit_pcs">Bucati</label>
                                    <input type="number"
                                        class="form-control" name="edit_pcs" id="edit_pcs" placeholder="Bucati" min="0" max="500" step="1">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="edit_pcs_height">Buc/Inaltime</label>
                                    <input type="text"
                                        class="form-control" name="edit_pcs_height" id="edit_pcs_height" placeholder="Bucati pe inaltime" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="edit_rows">Randuri</label>
                                    <input type="text"
                                        class="form-control" name="edit_rows" id="edit_rows" placeholder="Randuri" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="edit_label">Eticheta</label>
                                    <input type="text"
                                        class="form-control" name="edit_label" id="edit_label" placeholder="Eticheta" >
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="edit_foil">Folie</label>
                                  <select class="form-control" name="edit_foil" id="edit_foil">
                                    <option value="infoliat">Infoliat</option>
                                    <option value="neinfoliat">Neinfoliat</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                  <label for="edit_pal">Paletizare</label>
                                  <select class="form-control" name="edit_pal" id="edit_pal">
                                    <option value="palet">Palet</option>
                                    <option value="europalet">Europalet</option>
                                    <option value="tac">Tac</option>
                                    <option value="4wpal">4WPAL</option>
                                  </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="edit_pal_pcs">Paleti</label>
                                    <input type="number"
                                        class="form-control" name="edit_pal_pcs" id="edit_pal_pcs" placeholder="Paleti" min="0" max="100" step="1" disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="edit_details_fields_data" class="row">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div id="submit">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuleaza</button>
                    <button type="button" class="btn btn-primary" id="save_edit_details">Salveaza</button>
                </div>
            </div>
        </div>
    </div>
</div>
