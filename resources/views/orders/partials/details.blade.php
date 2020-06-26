<!-- Modal -->
<div class="modal fade" id="newPos" tabindex="-1" role="dialog" aria-labelledby="newPos" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pozitie noua</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/orders/details/add" method="post">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="row">
                        <div class="form-group col-lg-5">
                            <label for="article_id">Articol</label>
                            <select class="form-control" name="article_id" id="article_id" required>
                                <option>Selecteaza un articol</option>
                                @foreach($articles as $article)
                                    <option value="{{ $article->id }}">{{ $article->article }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-5">
                            <label for="finisaje">Finisaje</label>
                            <input type="text"
                                   class="form-control" name="finisaje" id="finisaje" aria-describedby="helpId" placeholder="Finisaje">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="pachete">Pachete</label>
                            <input type="number"
                                   class="form-control" name="pachete" id="pachete" aria-describedby="helpId" placeholder="Numar pachete" min="1" step="1" required>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="grosime">Grosime</label>
                            <input type="number"
                                   class="form-control" name="grosime" id="grosime" aria-describedby="helpId" placeholder="Grosime" min="0" step="0.1">
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="latime">Latime</label>
                            <input type="number"
                                   class="form-control" name="latime" id="latime" aria-describedby="helpId" placeholder="Latime" min="0" step="0.1">
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="lungime">Lungime</label>
                            <input type="number"
                                   class="form-control" name="lungime" id="lungime" aria-describedby="helpId" placeholder="Lungime" min="0" step="1">
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="bucati">Bucati</label>
                            <input type="number"
                                   class="form-control" name="bucati" id="bucati" aria-describedby="helpId" placeholder="Bucati" min="0" step="1">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="eticheta">Eticheta</label>
                            <input type="text"
                                   class="form-control" name="eticheta" id="eticheta" aria-describedby="helpId" placeholder="Eticheta">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="stick_panou">Sticker panou</label>
                            <input type="text"
                                   class="form-control" name="stick_panou" id="stick_panou" aria-describedby="helpId" placeholder="Sticker panou">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="ean_pal">EAN Palet</label>
                            <input type="text"
                                   class="form-control" name="ean_pal" id="ean_pal" aria-describedby="helpId" placeholder="EAN Palet">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="ean_picior">EAN picior</label>
                            <input type="text"
                                   class="form-control" name="ean_picior" id="ean_picior" aria-describedby="helpId" placeholder="EAN picior">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="paletizare">Paletizare</label>
                            <select class="form-control" name="paletizare" id="paletizare" required>
                                <option>Selecteaza paletizare</option>
                                <option value="4WAY">4WAY</option>
                                <option value="TAC">TAC</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Inchide</button>
                        <button type="submit" class="btn btn-primary">Salveaza</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
