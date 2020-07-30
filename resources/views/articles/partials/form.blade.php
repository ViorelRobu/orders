<!-- Modal -->
<div class="modal fade" id="newArticle" tabindex="-1" role="dialog" aria-labelledby="newArticle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Articol nou</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newArticleForm" action="/articles/add" method="post">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="">Articol</label>
                            <input type="text"
                                   class="form-control" name="name" id="name" aria-describedby="helpId" placeholder="Articol" required>
                        </div>
                        <div class="form-group col-lg-3">
                          <label for="species_id">Specie</label>
                          <select class="form-control" name="species_id" id="species_id" required>
                            @foreach ($species as $spec)
                                <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group col-lg-3">
                          <label for="quality_id">Calitate</label>
                          <select class="form-control" name="quality_id" id="quality_id" required>
                            @foreach ($quality as $q)
                                <option value="{{ $q->id }}">{{ $q->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group col-lg-3">
                          <label for="product_type_id">Tip produs</label>
                          <select class="form-control" name="product_type_id" id="product_type_id" required>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group col-lg-3">
                          <label for="default_refinements">Finisaje</label>
                          <select class="form-control" name="default_refinements[]" id="default_refinements" multiple required>
                            @foreach ($refinements as $refinement)
                                <option value="{{ $refinement->id }}">{{ $refinement->name }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="form-group col-lg-3">
                          <label for="thickness">Grosime</label>
                          <input type="number" class="form-control" name="thickness" id="thickness" aria-describedby="thickness" min="0" max="99" step="0.1" placeholder="Grosime (mm)" required>
                        </div>
                        <div class="form-group col-lg-3">
                          <label for="width">Latime</label>
                          <input type="number" class="form-control" name="width" id="width" aria-describedby="width" min="0" max="1600" step="0.1" placeholder="Latime (mm)" required>
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
