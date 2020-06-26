<!-- Modal -->
<div class="modal fade" id="newOrder" tabindex="-1" role="dialog" aria-labelledby="newOrder" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comanda noua</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/orders/add" method="post">
                    @method('POST')
                    @csrf
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="customer_id">Client</label>
                            <select class="custom-select" name="customer_id" id="customer_id" required>
                                <option selected>Selectati un client</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="">Comanda client</label>
                            <input type="text"
                                   class="form-control" name="customer_order" id="customer_order" aria-describedby="helpId" placeholder="Comanda client">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="au">Auftrag</label>
                            <input type="text"
                                   class="form-control" name="au" id="au" aria-describedby="helpId" placeholder="Auftrag">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="destination">Destinatie</label>
                            <input type="text"
                                   class="form-control" name="destination" id="destination" aria-describedby="helpId" placeholder="Destinatie">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="production">Saptamana productie</label>
                            <input type="date"
                                   class="form-control" name="production" id="production" aria-describedby="helpId" placeholder="Productie">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="loading">Saptamana incarcare</label>
                            <input type="date"
                                   class="form-control" name="loading" id="loading" aria-describedby="helpId" placeholder="Incarcare">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="month">Luna</label>
                            <select class="form-control" name="month" id="month">
                                <option value="IANUARIE">IANUARIE</option>
                                <option value="FEBRUARIE">FEBRUARIE</option>
                                <option value="MARTIE">MARTIE</option>
                                <option value="APRILIE">APRILIE</option>
                                <option value="MAI">MAI</option>
                                <option value="IUNIE">IUNIE</option>
                                <option value="IULIE">IULIE</option>
                                <option value="AUGUST">AUGUST</option>
                                <option value="SEPTEMBRIE">SEPTEMBRIE</option>
                                <option value="OCTOMBRIE">OCTOMBRIE</option>
                                <option value="NOIEMBRIE">NOIEMBRIE</option>
                                <option value="DECEMBRIE">DECEMBRIE</option>

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
