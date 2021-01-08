<!-- Modal -->
<div class="modal fade" id="userStatus" tabindex="-1" role="dialog" aria-labelledby="userStatus" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schimba status utilizator</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div id="message">

                </div>
            </div>
            <form id="changeUserStatusForm" action="" method="POST">
                @method('POST')
                @csrf
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuleaza</button>
                    <button type="submit" class="btn btn-primary">Salveaza</button>
                </div>
            </form>
        </div>
    </div>
</div>
