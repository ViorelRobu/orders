<!-- Modal -->
<div class="modal fade" id="newOrder" tabindex="-1" role="dialog" aria-labelledby="newOrder" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Creaza comanda noua</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/orders/add" method="POST" id="newOrderForm">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="form-grou col-lg-6">
                            <label for="customer_id">Client</label>
                            <select class="form-control" name="customer_id" id="customer_id" required>
                               @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                               @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="customer_order">Comanda client</label>
                            <input type="text"
                                class="form-control" name="customer_order" id="customer_order" placeholder="Comanda client">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="auftrag">Auftrag</label>
                            <input type="text"
                                class="form-control" name="auftrag" id="auftrag" placeholder="Auftrag">
                        </div>
                        <div class="form-grou col-lg-3">
                            <label for="country_id">Tara destinatie</label>
                            <select class="form-control" name="country_id" id="country_id" required>
                               @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                               @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="destination_id" name="destination_id">
                        <div class="form-group col-lg-9 ui-widget">
                            <label for="address">Adresa livrare</label>
                                <input type="text"
                                    class="form-control" name="address" id="address" placeholder="Adresa livrare" data-toggle="dropdown" autocomplete="off">
                                <div class="dropdown-menu" id="autocomplete">
                                    <a class="dropdown-item" href="#">Se incarca...</a>
                                </div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="customer_kw">Saptamana cf client</label>
                            <input type="text"
                                class="form-control" name="customer_kw" id="customer_kw" placeholder="Saptamana livrare ceruta de client" autocomplete="off">
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="production_kw">Saptamana productie</label>
                            <input type="text"
                                class="form-control" name="production_kw" id="production_kw" placeholder="Saptamana de productie" autocomplete="off">
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="delivery_kw">Saptamana livrare</label>
                            <input type="text"
                                class="form-control" name="delivery_kw" id="delivery_kw" placeholder="Saptamana de livrare" autocomplete="off">
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="eta">ETA</label>
                            <input type="text"
                                class="form-control" name="eta" id="eta" placeholder="ETA" autocomplete="off">
                        </div>
                    </div>
                    <div id="submit" class="float-right"></div>
                    <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Inchide</button>
                </form>
            </div>
        </div>
    </div>
</div>
