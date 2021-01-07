<!-- Modal -->
<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="newUser" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Utilizator nou</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newUserForm" action="/users/add" method="post">
                    @method('POST')
                    @csrf
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="">Nume</label>
                            <input type="text"
                                   class="form-control" name="name" id="name" aria-describedby="helpId" placeholder="Nume" required>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="">Nume utilizator</label>
                            <input type="text"
                                   class="form-control" name="username" id="username" aria-describedby="helpId" placeholder="Nume utilizator" required>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="">Email</label>
                            <input type="email"
                                   class="form-control" name="email" id="email" aria-describedby="helpId" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="">Parola</label>
                            <input type="password"
                                   class="form-control" name="first_pass" id="first_pass" aria-describedby="helpId" placeholder="Parola">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="">Repeta Parola</label>
                            <input type="password"
                                   class="form-control" name="second_pass" id="second_pass" aria-describedby="helpId" placeholder="Repeta Parola">
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
