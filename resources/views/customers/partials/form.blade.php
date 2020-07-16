<!-- Modal -->
<div class="modal fade" id="newCustomer" tabindex="-1" role="dialog" aria-labelledby="newCustomer" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Creaza client nou</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="/customers/add" method="POST" id="newCustomerForm">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="fibu">FIBU</label>
                        <input type="text"
                               class="form-control" name="fibu" id="fibu" aria-describedby="fibu" placeholder="FIBU">
                    </div>
                    <div class="form-group">
                        <label for="name">Nume client</label>
                        <input type="text"
                               class="form-control" name="name" id="name" aria-describedby="name" placeholder="Nume client">
                    </div>
                    <div class="form-group">
                        <label for="">Tara</label>
                        <select class="form-control" name="country_id" id="country_id">
                           @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                           @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Inchide</button>
                    <button type="submit" id="save" class="btn btn-primary">Salveaza</button>
                    <button type="submit" id="update" style="display:none" class="btn btn-primary hidden">Modifica</button>
                </form>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
