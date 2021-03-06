@extends('adminlte::page')

@section('title', 'Comanda ' . $order->order . $order->archived_text)

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card-title">
                        <h5>Comanda {{ $order->order }}
                            @if ($order->loading_date != null)
                                <span class="badge badge-pill badge-success">Incarcata in {{ $loading_date }}</span>
                            @endif
                            @if ($order->archived === 1)
                                <span class="badge badge-pill badge-info">Arhivata</span>
                            @else
                                <span class="badge badge-pill badge-success" id="priority">
                                    Prioritate <span id="priority_text">{{ $order->priority }}</span>
                                    <input style="display:none" id="priority_value" type="text" value="{{ $order->priority }}">
                                </span>
                            @endif
                        </h5>
                    </div>
                    @if ($order->archived == 0)
                        <i style="margin-left: 10px" class="fas fa-file-archive fa-2x" data-toggle="modal" data-target="#archiveOrder"></i>
                    @endif
                </div>
                <div class="col-lg-3">
                    @if ($order->archived == 0)
                        @can('planificare')
                            <div class="card-title">
                                <span>Livrare</span>
                                <span><button class="btn btn-light" onclick="shipComplete()">Completa</button></span>
                                <span><button class="btn btn-light" onclick="shipPartial()">Partiala</button></span>
                            </div>
                        @endcan
                    @endif
                </div>
                @can('planificare')
                    <div class="col-lg-3">
                        <form method="POST" action="/orders/{{ $order->id }}/documents/upload" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="form-group">
                                    <input type="file" class="form-control-file" name="docs_file" id="docs_file" placeholder="">
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div>
                                        <button class="btn btn-primary" type="submit">Incarca</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endcan
                <div class="col-lg-2">
                    @can('productie')
                        <a href="/orders/{{ $order->id }}/export" target="_blank" style="color: white">
                            <i id="export" class="far fa-file-excel float-right fa-2x" style="margin-left:10px"></i>
                        </a>
                    @endcan
                    @can('planificare')
                        <i class="fas fa-paperclip fa-2x fa-rotate-180 float-right" style="margin-left: 10px" data-toggle="modal" data-target="#documents"></i>
                    @endcan
                    <a href="/orders/{{ $order->id }}/print/portrait" target="_blank" style="color: white">
                        <i id="print_pdf" class="far fa-file-pdf float-right fa-2x" style="margin-left:10px"></i>
                    </a>
                    <a href="/orders/{{ $order->id }}/print/landscape" target="_blank" style="color: white">
                        <i id="print_pdf" class="far fa-file-pdf float-right fa-2x fa-rotate-270" style="margin-left:10px"></i>
                    </a>
                    @can('planificare')
                        <i id="edit_details" class="fas fa-edit float-right fa-2x" style="margin-left:10px"></i>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group"><strong>Client</strong></div>
                    <div class="form-group"><strong>Comanda client</strong></div>
                    <div class="form-group"><strong>Auftrag</strong></div>
                    <div class="form-group"><strong>Tara de livrare</strong></div>
                    <div class="form-group"><strong>Adresa de livrare</strong></div>
                </div>
                <div class="col-lg-2" id="order_text">
                    <div>
                        <div id="customer" class="form-group">
                            {{ $customer->name }}
                        </div>
                        <input type="hidden" name="customer__id" id="customer__id" value="{{ $order->customer_id }}">
                        <select class="form-control" name="customer_id" id="customer_id" style="display: none">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div id="customer_order_text" class="form-group">
                            {{ $order->customer_order }}
                        </div>
                        <input type="text"
                            class="form-control" name="customer_order" id="customer_order" placeholder="Comanda clientului" style="display: none">
                    </div>
                    <div>
                        <div id="auftrag_text" class="form-group">
                            {{ $order->auftrag == null ? 'inexistent' : $order->auftrag }}
                        </div>
                        <input type="text"
                            class="form-control" name="auftrag" id="auftrag" placeholder="Auftrag" style="display: none">
                    </div>
                    <div>
                        <div id="country_text" class="form-group">
                            {{ $country->name }}
                        </div>
                        <input type="hidden" name="country__id" id="country__id" value="{{ $destination->country_id }}">
                        <select class="form-control" name="country_id" id="country_id" style="display: none">
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div id="address_text" class="form-group">
                            {{ $destination->address}}
                        </div>
                        <input type="hidden" name="destination_id" id="destination_id" value="{{ $order->destination_id }}">
                        <input type="text"
                            class="form-control" name="address" id="address" placeholder="Adresa de livrare" style="display: none" data-toggle="dropdown" autocomplete="off">
                        <div class="dropdown-menu" id="autocomplete">
                            <a class="dropdown-item" href="#">Se incarca...</a>
                        </div>
                    </div>
                    <div class="input-group">
                        <button class="btn btn-secondary float-right form-control" id="cancel_details" style="display: none">Anuleaza</button>
                        <button class="btn btn-primary float-right form-control" id="save_details" style="display: none">Salveaza</button>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div id="observations_title">
                        <strong>Observatii</strong>
                        @can('planificare')
                            <i id="edit_observations" class="fas fa-edit"></i>
                        @endcan
                    </div>
                    <hr>
                    <div id="observations_text">
                        {!! $order->observations !!}
                    </div>
                    <textarea name="observations" id="observations" cols="20" rows="4" style="display: none"></textarea>
                    <div class="input-group">
                        <button class="btn btn-secondary float-right form-control" id="cancel_observations" style="display: none">Anuleaza</button>
                        <button class="btn btn-primary float-right form-control" id="save_observations" style="display: none">Salveaza</button>
                    </div>
                    <br>
                    <div>
                        <strong>Campuri extra</strong>
                        @can('planificare')
                            <i class="fas fa-edit" data-toggle="modal" data-target="#addFields"></i>
                        @endcan
                    </div>
                    <hr>
                    <div id="details_fields_text">
                        {{ $order->details_fields }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-lg-11">
                    <div class="card-title">
                        <h5>
                            Sumar comanda
                        </h5>
                    </div>
                </div>
                <div class="col-lg-1">
                    @can('planificare')
                        <i id="save_dates" class="fas fa-save float-right fa-2x" style="margin-left: 10px; color: rgb(35, 231, 35); display: none"></i>
                        <i id="cancel_dates" class="fas fa-window-close float-right fa-2x" style="margin-left: 10px; color: rgb(243, 14, 14); display: none"></i>
                        <i id="edit_dates" class="fas fa-edit float-right fa-2x" style="margin-left: 10px"></i>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-1">
                    <p class="text-center"><strong>KW client</strong></p>
                    <p id="customer_kw_text" class="text-center">{{ $customer_kw }}</p>
                    <div>
                        <input type="hidden" name="customer__kw" id="customer__kw" value="{{ $order->customer_kw }}">
                        <input type="text"
                            class="form-control" name="customer_kw" id="customer_kw" style="display: none" placeholder="KW client" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>KW productie</strong></p>
                    <p id="production_kw_text" class="text-center">{{ $production_kw }}</p>
                    <div>
                        <input type="hidden" name="production__kw" id="production__kw" value="{{ $order->production_kw }}">
                        <input type="text"
                            class="form-control" name="production_kw" id="production_kw" style="display: none" placeholder="KW productie" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>KW livrare</strong></p>
                    <p id="delivery_kw_text" class="text-center">{{ $delivery_kw }}</p>
                    <div>
                        <input type="hidden" name="delivery__kw" id="delivery__kw" value="{{ $order->delivery_kw }}">
                        <input type="text"
                            class="form-control" name="delivery_kw" id="delivery_kw" style="display: none" placeholder="KW livrare" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Luna</strong></p>
                    <p id="month" class="text-center">{{ $order->month }}</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>ETA</strong></p>
                    <p id="eta_text" class="text-center">{{ $eta }}</p>
                    <div>
                        <input type="hidden" name="eta__" id="eta__" value="{{ $order->eta }}">
                        <input type="text"
                            class="form-control" name="eta" id="eta" style="display: none" placeholder="ETA" autocomplete="off">
                    </div>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Total comanda</strong></p>
                    <p class="text-center">{{ $order_total }} mc</p>
                    <p class="text-center small" style="color: red">{{ $volume_by_type }}</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Rest de produs</strong></p>
                    <p class="text-center">{{ $rest_to_produce }} mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Livrat</strong></p>
                    <p class="text-center">{{ $delivered }} mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Rest de livrat</strong></p>
                    <p class="text-center">{{ $order_total - $delivered }} mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Gata livrare</strong></p>
                    <p class="text-center">{{ $ready_for_delivery }} mc</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Finalizat</strong></p>
                    <p class="text-center">{{ $finished }}%</p>
                </div>
                <div class="col-lg-1">
                    <p class="text-center"><strong>Livrat</strong></p>
                    <p class="text-center">{{ $percentage_delivered }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order details -->
        <div class="card">
            <div class="card-header bg-dark">
                <div class="row">
                    <div class="col-lg-11">
                        <div class="card-title">
                            <h5>
                                Detalii comanda
                            </h5>
                        </div>
                    </div>
                    <div class="col-lg-1">
                        @can('planificare')
                            <i id="addNewDetail" class="fas fa-plus float-right fa-2x" data-toggle="modal" data-target="#addDetails"></i>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body" style="overflow-x: auto; white-space: nowrap;">
                <table id="order_details" class="table table-bordered table-hover table-sm">
                    <thead class="thead-dark">
                        <td style="border: 1px solid black"><i class="fas fa-chevron-up"></i></td>
                        <td style="border: 1px solid black">Poz</td>
                        <td style="border: 1px solid black">Articol</td>
                        <td style="border: 1px solid black">Finisaje</td>
                        <td style="border: 1px solid black">Gr</td>
                        <td style="border: 1px solid black">Lat</td>
                        <td style="border: 1px solid black">Lung</td>
                        <td style="border: 1px solid black">Buc</td>
                        <td style="border: 1px solid black">Vol</td>
                        <td style="border: 1px solid black">Ticom</td>
                        <td style="border: 1px solid black">Lot</td>
                        <td style="border: 1px solid black">Prod</td>
                        <td style="border: 1px solid black">Buc/H</td>
                        <td style="border: 1px solid black">Rand</td>
                        <td style="border: 1px solid black">Etich</td>
                        <td style="border: 1px solid black">Fol</td>
                        <td style="border: 1px solid black">Pal</td>
                        <td style="border: 1px solid black">Incarcare</td>
                        @if ($fields != [])
                            @foreach ($fields as $field)
                                <td style="border: 1px solid black">{{ $field }}</td>
                            @endforeach
                        @endif
                        <td style="border: 1px solid black"></td>
                    </thead>
                </table>
            </div>
        </div>

    @can('planificare')
        @include('orders.partials.arch')
        @include('orders.partials.ship')
        @include('orders.partials.details')
        @include('orders.partials.edit_details')
        @include('orders.partials.fields')
        @include('orders.partials.delete_pos')
        @include('orders.partials.delete_pak')
        @include('orders.partials.copy')
        @include('orders.partials.documents')
        @include('orders.partials.delete_file')
        @include('audits')
    @endcan

@stop

@section('footer')
    @include('footer')
@stop

@section('js')
    <script>
        // save and update buttons markup
        const save = '<input id="save" type="submit" class="btn btn-primary float-right" value="Adauga">';
        const update = '<button type="submit" id="update" class="btn btn-primary">Modifica</button>';
        const save_edit_details = '<button type="button" class="btn btn-primary" id="save_edit_details">Salveaza pozitiile</button>';
        const save_edit_package = '<button type="button" class="btn btn-primary" id="save_edit_package">Salveaza pachet</button>';

        // initialize the TinyMCE editor
        tinymce.init({
            selector: '#observations'
        });

        // select function for address autocomplete
        const select = (value) => {
            $('#address').val(value);
            document.getElementById('address').focus();
        }

        // datepickers
        $('#loading_date').datepicker({
            showWeek: true,
            firstDay: 1,
            dateFormat: 'dd.mm.yy'
        });

        $('#customer_kw').datepicker({
            showWeek: true,
            firstDay: 1,
            dateFormat: 'dd.mm.yy'
        });

        $('#production_kw').datepicker({
            showWeek: true,
            firstDay: 1,
            dateFormat: 'dd.mm.yy'
        });

        $('#delivery_kw').datepicker({
            showWeek: true,
            firstDay: 1,
            dateFormat: 'dd.mm.yy'
        });

        $('#eta').datepicker({
            showWeek: true,
            firstDay: 1,
            dateFormat: 'dd.mm.yy'
        });

        // add select 2 to all selects
        $('#country_id').select2({
            width: '100%'
        });

        $('#customer_id').select2({
            width: '100%'
        });

        // hide all select2 after rendering
        $('.select2').hide();

        // select2 for adding details
        $('#article_id').select2({
            width: '100%'
        });
        $('#refinements_list').select2({
            width: '100%'
        });

        // select2 for editing details
        $('#edit_article_id').select2({
            width: '100%'
        });
        $('#edit_refinements_list').select2({
            width: '100%'
        });

        // ship complete
        const shipComplete = () => {
            $('#loadingDate').modal('show');
            $('#comment_input').hide();
            $('#shipForm').attr('action', '/orders/{{ $order->id }}/ship');
        }

        // ship partial
        const shipPartial = () => {
            $('#loadingDate').modal('show');
            $('#comment_input').show();
            $('#shipForm').attr('action', '/orders/{{ $order->id }}/ship/partial');
        }

        // fetch position details
        const fetchPosition = id => {
            $('#save_edit_details').remove();
            $('#submit_edit').append(save_edit_details);
            $('#save_edit_package').remove();
            $.ajax({
                url: `/orders/{{ $order->id }}/details/${id}/fetch`,
                dataType: 'json',
                type: 'GET',
                success: function(response){
                    $('#position').val(id);
                    $('#edit_article_id').val(response.data[0].article_id);
                    $('#select2-edit_article_id-container').html(response.data[0].article);
                    $('#select2-edit_article_id-container').attr('title', response.data[0].article);
                    $('#edit_refinements_list').val(response.data[0].refinements_list).trigger('change');
                    $('#edit_length').val(response.data[0].length)
                    $('#edit_pcs').val(response.data[0].pcs)
                    $('#edit_pcs_height').val(response.data[0].pcs_height)
                    $('#edit_rows').val(response.data[0].rows)
                    $('#edit_label').val(response.data[0].label)
                    $('#edit_foil').val(response.data[0].foil)
                    $('#edit_pal').val(response.data[0].pal)
                    $('#edit_pal_pcs').val(response.data[0].pallets)
                    // add the inputs for the custom position details
                    const details = response.data[0].details;
                    if (details !== {}) {
                        for (let detail in details) {
                            let name = detail.split('_').join(' ');
                            let label = name.charAt(0).toUpperCase() + name.slice(1);
                            let html = `<div class="col-lg-4">
                                            <label for="${detail}">${label}</label>
                                            <input type="text"
                                                class="form-control" name="${detail}" id="${detail}" placeholder="${label}" value="${details[detail]}">
                                        </div>`;
                            $('#edit_details_fields_data').append(html);
                        }
                    }
                }
            });
        }

        // fetch package details
        const fetchPackage = id => {
            $('#save_edit_package').remove();
            $('#submit_edit').append(save_edit_package);
            $('#save_edit_details').remove();
            $.ajax({
                url: `/orders/{{ $order->id }}/details/${id}/fetch/one`,
                dataType: 'json',
                type: 'GET',
                success: function(response){
                    $('#position').val(id);
                    $('#edit_article_id').val(response.data[0].article_id);
                    $('#select2-edit_article_id-container').html(response.data[0].article);
                    $('#select2-edit_article_id-container').attr('title', response.data[0].article);
                    $('#edit_refinements_list').val(response.data[0].refinements_list).trigger('change');
                    $('#edit_length').val(response.data[0].length)
                    $('#edit_pcs').val(response.data[0].pcs)
                    $('#edit_pcs_height').val(response.data[0].pcs_height)
                    $('#edit_rows').val(response.data[0].rows)
                    $('#edit_label').val(response.data[0].label)
                    $('#edit_foil').val(response.data[0].foil)
                    $('#edit_pal').val(response.data[0].pal)
                    $('#edit_pal_pcs').val(response.data[0].pallets)
                    // add the inputs for the custom position details
                    const details = response.data[0].details;
                    if (details !== {}) {
                        for (let detail in details) {
                            let name = detail.split('_').join(' ');
                            let label = name.charAt(0).toUpperCase() + name.slice(1);
                            let html = `<div class="col-lg-4">
                                            <label for="${detail}">${label}</label>
                                            <input type="text"
                                                class="form-control" name="${detail}" id="${detail}" placeholder="${label}" value="${details[detail]}">
                                        </div>`;
                            $('#edit_details_fields_data').append(html);
                        }
                    }
                }
            });
        }

        const audit = id => {
        $.ajax({
            url: `{{ route('details.audits') }}`,
            dataType: 'json',
            data: {id: id},
            type: 'GET',
            success: function(response){
                response.forEach(element => {
                    let old_values = [];
                    for (let key in element.old_values) {
                        old_values.push(`${key}: ${element.old_values[key]}|`);
                    }
                    let new_values = [];
                    for (let key in element.new_values) {
                        new_values.push(`${key}: ${element.new_values[key]}|`);
                    }
                    let html = `
                    <tr>
                        <td>
                            ${element.user}<br>
                            <small class="text-muted">
                                ${element.event}<br>
                                ${new Date(element.created_at)}
                            </small>
                        </td>
                        <td>${old_values.toString().split('|,').join('<br>').replace('|','')}</td>
                        <td>${new_values.toString().split('|,').join('<br>').replace('|','')}</td>
                    </tr>
                    `;

                    $('#audits-table').append(html);
                });
            }
        });
    }

    $('#audits').on('hidden.bs.modal', function () {
        $('#audits-table').empty();
    });

        // set the id of the package to be copied
        const setCopyId = id => {
            $('#copy_position').attr('onclick', `copy(${id})`);
        }

        // copy the package
        const copy = id => {
            let copies = $('#copies').val();
            $.ajax({
                url: `/orders/{{ $order->id }}/details/copy`,
                dataType: 'json',
                type: 'POST',
                data: {
                    id, copies,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response){
                    $('#copyDetail').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    table.draw();
                }
            });
        }

        // reset the copy details form on closing the modal
        $('#copyDetail').on('hidden.bs.modal', function () {
            $('#copies').val('');
        });

        // set the id of the package to be deleted
        const setDocId = id => {
            $('#delete_file').attr('onclick', `deleteFile(${id})`);
        }

        // set the id of the package to be deleted
        const setIdPak = id => {
            $('#deletePackage').attr('onclick', `deletePackage(${id})`);
        }

        // set the position for the delete modal
        const setIdPos = id => {
            $('#deletePosition').attr('onclick', `deletePosition(${id})`);
        }

        // delete all the positions
        const deletePosition = id => {
            $.ajax({
                url: `/orders/{{ $order->id }}/details/${id}/delete`,
                dataType: 'json',
                type: 'DELETE',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response){
                    $('#deletePos').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    table.draw();
                }
            });
        }

        // delete the document
        const deleteFile = id => {
            $.ajax({
                url: `/orders/{{ $order->id }}/documents/${id}/delete`,
                dataType: 'json',
                type: 'DELETE',
                data: {
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response){
                    $('#deleteDocument').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    documents.draw();
                }
            });
        }

        // delete all the positions
        const deletePackage = id => {
            $.ajax({
                url: `/orders/{{ $order->id }}/details/package/delete`,
                dataType: 'json',
                type: 'DELETE',
                data: {
                    id,
                    '_token': '{{ csrf_token() }}',
                },
                success: function(response){
                    $('#deletePak').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    table.draw();
                }
            });
        }

        // reset the edit details form on closing the modal
        $('#editDetails').on('hidden.bs.modal', function () {
            $('#edit_details_fields_data').empty();
        });

        // order documents datatable
        let documents = $('#docsTable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: "/orders/{{ $order->id }}/documents/fetch",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'file', name: 'file'},
                {data: 'actions', name: 'actions'},
            ]
        });

        // order details datatable
        let table = $('#order_details').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            ajax: "/orders/{{ $order->id }}/details",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'position', name: 'position'},
                {data: 'article', name: 'article'},
                {data: 'refinements_list', name: 'refinements_list'},
                {data: 'thickness', name: 'thickness'},
                {data: 'width', name: 'width'},
                {data: 'length', name: 'length'},
                {data: 'pcs', name: 'pcs'},
                {data: 'volume', name: 'volume'},
                {data: 'produced_ticom', name: 'produced_ticom'},
                {data: 'batch', name: 'batch'},
                {data: 'produced_batch', name: 'produced_batch'},
                {data: 'pcs_height', name: 'pcs_height'},
                {data: 'rows', name: 'rows'},
                {data: 'label', name: 'label'},
                {data: 'foil', name: 'foil'},
                {data: 'pal', name: 'pal'},
                {data: 'loading_date', name: 'loading_date'},
        @if ($fields != [])
            @foreach ($fields as $field)
                {data: '{{ $field }}', name: '{{ $field }}'},
            @endforeach
        @endif
                {data: 'actions', name: 'actions'},
            ],
            rowCallback: function(row, data, index) {
                $('td:eq(0)', row).css('border', '1px solid black');
                $('td:eq(1)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(2)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(3)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(4)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(5)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(6)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(7)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(8)', row).addClass('table-success').css('border', '1px solid black');
                $('td:eq(9)', row).addClass('table-secondary').css('border', '1px solid black');
                $('td:eq(10)', row).addClass('table-secondary').css('border', '1px solid black');
                $('td:eq(11)', row).addClass('table-secondary').css('border', '1px solid black');
                $('td:eq(12)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(13)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(14)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(15)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(16)', row).addClass('table-warning').css('border', '1px solid black');
                $('td:eq(17)', row).addClass('table-warning').css('border', '1px solid black');
                @if ($fields != [])
                    @for ($i = 1; $i <= count($fields); $i++)
                        $('td:eq({{ 17 + $i }})', row).addClass('table-warning').css('border', '1px solid black');
                    @endfor
                @endif
                $('td:eq({{ 18 + count($fields) }})', row).css('border', '1px solid black');
            },
        });

        $(document).ready(function() {

            // configure button for a add new event
            $('#addNewDetail').click(function() {
                $('#update').remove();
                $('#submit').append(save);
                $('#details_fields_data').empty();
                let fields = $('#details_fields_text').html().trim();
                if (fields != '') {
                    let fields_arr = fields.split('|');
                    fields_arr.forEach( element => {
                        let el = element.trim();
                        let name = element.split('_').join(' ');
                        let label = name.charAt(0).toUpperCase() + name.slice(1);
                        let html =  `<div class="col-lg-4">
                                        <label for="${el}">${label}</label>
                                        <input type="text"
                                            class="form-control" name="${el}" id="${el}" placeholder="${label}">
                                </div>`;
                            $('#details_fields_data').append(html);
                    });
                }
            });

        // add the details to the DB
        $(document).on('click', '#save', function(event) {
            event.preventDefault();
            let article_id = $('#article_id').val();
            let refinements_list = $('#refinements_list').val();
            let length = $('#length').val();
            let pcs = $('#pcs').val();
            let pal_pcs = $('#pal_pcs').val();
            let pcs_height = $('#pcs_height').val();
            let rows = $('#rows').val();
            let label = $('#label').val();
            let foil = $('#foil').val();
            let pal = $('#pal').val();
            let json_data = {};
            $('#save').prop('disabled', true);

            $('#details_fields_data').children().each(function() {
                $(this).children().each(function() {
                    json_data[$(this).attr('id')] = $(this).val();
                    delete json_data.undefined;
                })
            });

            $.ajax({
                url: '/orders/{{ $order->id }}/details/add',
                method: 'POST',
                dataType: 'json',
                data: {
                '_token': '{{ csrf_token() }}',
                article_id, refinements_list, length, pcs, pcs_height, rows, label, foil, pal, pal_pcs,
                details_json: JSON.stringify(json_data)
                },
                error: function(err) {
                    console.log(err);
                    let errors = err.responseJSON.message;
                    let errors_arr = [];
                    for (let error in errors) {
                        errors[error].forEach(el => {
                            errors_arr.push(el + '<br>');
                        });
                    }
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Eroare',
                        html: errors_arr.toString().split(',').join(''),
                        showConfirmButton: false,
                        timer: 10000,
                        toast: true
                    });
                    $('#save').prop('disabled', false);
                },
                success: function(response) {
                    $('#addDetails').modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    table.draw()
                }
            });
        });

        // edit the details in the DB for the selected position
        $(document).on('click', '#save_edit_details', function(event) {
            event.preventDefault();
            let position = $('#position').val();
            let edit_article_id = $('#edit_article_id').val();
            let edit_refinements_list = $('#edit_refinements_list').val();
            let edit_length = $('#edit_length').val();
            let edit_pcs = $('#edit_pcs').val();
            let edit_pcs_height = $('#edit_pcs_height').val();
            let edit_rows = $('#edit_rows').val();
            let edit_label = $('#edit_label').val();
            let edit_foil = $('#edit_foil').val();
            let edit_pal = $('#edit_pal').val();
            let json_data = {};
            $('#save_edit_details').prop('disabled', true);

            $('#edit_details_fields_data').children().each(function() {
                $(this).children().each(function() {
                    json_data[$(this).attr('id')] = $(this).val();
                    delete json_data.undefined;
                })
            });

            $.ajax({
                url: `/orders/{{ $order->id }}/details/${position}/update`,
                method: 'PATCH',
                dataType: 'json',
                data: {
                '_token': '{{ csrf_token() }}',
                edit_article_id, edit_refinements_list, edit_length, edit_pcs, edit_pcs_height,
                edit_rows, edit_label, edit_foil, edit_pal,
                edit_details_json: JSON.stringify(json_data)
                },
                error: function(err) {
                    console.log(err);
                    let errors = err.responseJSON.message;
                    let errors_arr = [];
                    for (let error in errors) {
                        errors[error].forEach(el => {
                            errors_arr.push(el + '<br>');
                        });
                    }
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Eroare',
                        html: errors_arr.toString().split(',').join(''),
                        showConfirmButton: false,
                        timer: 10000,
                        toast: true
                    });
                    $('#save_edit_details').prop('disabled', false);
                },
                success: function(response) {
                    console.log(response.error);
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    $('#save_edit_details').prop('disabled', false);
                    $('#editDetails').modal('hide');
                    table.draw()
                }
            });
        });

        // edit the details in the DB for the selected package
        $(document).on('click', '#save_edit_package', function(event) {
            event.preventDefault();
            let position = $('#position').val();
            let edit_article_id = $('#edit_article_id').val();
            let edit_refinements_list = $('#edit_refinements_list').val();
            let edit_length = $('#edit_length').val();
            let edit_pcs = $('#edit_pcs').val();
            let edit_pcs_height = $('#edit_pcs_height').val();
            let edit_rows = $('#edit_rows').val();
            let edit_label = $('#edit_label').val();
            let edit_foil = $('#edit_foil').val();
            let edit_pal = $('#edit_pal').val();
            let json_data = {};
            $('#save_edit_details').prop('disabled', true);

            $('#edit_details_fields_data').children().each(function() {
                $(this).children().each(function() {
                    json_data[$(this).attr('id')] = $(this).val();
                    delete json_data.undefined;
                })
            });

            $.ajax({
                url: `/orders/{{ $order->id }}/details/${position}/update/one`,
                method: 'PATCH',
                dataType: 'json',
                data: {
                '_token': '{{ csrf_token() }}',
                edit_article_id, edit_refinements_list, edit_length, edit_pcs, edit_pcs_height,
                edit_rows, edit_label, edit_foil, edit_pal,
                edit_details_json: JSON.stringify(json_data)
                },
                error: function(err) {
                    console.log(err);
                    let errors = err.responseJSON.message;
                    let errors_arr = [];
                    for (let error in errors) {
                        errors[error].forEach(el => {
                            errors_arr.push(el + '<br>');
                        });
                    }
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Eroare',
                        html: errors_arr.toString().split(',').join(''),
                        showConfirmButton: false,
                        timer: 10000,
                        toast: true
                    });
                    $('#save_edit_package').prop('disabled', false);
                },
                success: function(response) {
                    console.log(response.error);
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    $('#save_edit_package').prop('disabled', false);
                    $('#editDetails').modal('hide');
                    table.draw()
                }
            });
        });

        // reset the add details form on closing the modal
        $('#addDetails').on('hidden.bs.modal', function () {
            $('#update').remove();
            $('#save').remove();
            $('#details_fields_data').empty();
        });

        // add details fields
        $(document).on('click', '#save_fields_details', function(e) {
            e.preventDefault();
            let details_fields = $('#details_fields').val().trim();
            let fields_text = $('#details_fields_text').html().trim();
            $('#save_fields_details').prop('disabled', true);

            $.ajax({
                url: '/orders/{{ $order->id }}/fields',
                method: 'POST',
                dataType: 'json',
                data: {
                '_token': '{{ csrf_token() }}',
                details_fields
                },
                error: function(err) {
                    console.log(err);
                    let errors = err.responseJSON.message;
                    let errors_arr = [];
                    for (let error in errors) {
                        errors[error].forEach(el => {
                            errors_arr.push(el + '<br>');
                        });
                    }
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Eroare',
                        html: errors_arr.toString().split(',').join(''),
                        showConfirmButton: false,
                        timer: 10000,
                        toast: true
                    });
                    $('#save_fields_details').prop('disabled', false);
                },
                success: function(response) {
                    Swal.fire({
                        position: 'top-end',
                        type: response.type,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    $('#save_fields_details').prop('disabled', false);
                    $('#addFields').modal('hide');
                    $('#details_fields_text').html(response.data);
                    location.reload();
                }
            });
        })

        @can('planificare')
            // allow editing of priority
            $('#priority').dblclick(function() {
                $('#priority_value').show(100);
                $('#priority_text').hide(100);
            })

            // save the priority and display the value
            $('#priority_value').keyup(function(e) {
                if(e.keyCode == 13) {
                    $('#priority_value').hide(100);
                    $('#priority_text').show(100);
                    $.ajax({
                        url: '/orders/{{ $order->id }}/update/priority',
                        method: 'PATCH',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            priority: $('#priority_value').val()
                        },
                        error: function(err) {
                            console.log(err);
                            let errors = err.responseJSON.message;
                            let errors_arr = [];
                            for (let error in errors) {
                                errors[error].forEach(el => {
                                    errors_arr.push(el + '<br>');
                                });
                            }
                            Swal.fire({
                                position: 'top-end',
                                type: 'error',
                                title: 'Eroare',
                                html: errors_arr.toString(),
                                showConfirmButton: false,
                                timer: 10000,
                                toast: true
                            });
                        },
                        success: function(response) {
                            Swal.fire({
                                position: 'top-end',
                                type: response.status,
                                title: 'Succes',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 5000,
                                toast: true
                            });
                            $('#priority_text').html(response.value)
                        }
                    });
                };
            });
        @endcan

        // allow editing of the main details
        $('#edit_details').click(function() {
            $('#customer').hide(100);
            $('#customer_id').show(100);
            $('#customer_id').val($('#customer__id').val());
            $('#customer_order_text').hide(100);
            $('#customer_order').show(100);
            $('#customer_order').val($('#customer_order_text').html().trim());
            $('#auftrag_text').hide(100);
            $('#auftrag').show(100);
            if ($('#auftrag_text').html().trim() === 'inexistent') {
                $('#auftrag').val('');
            } else {
                $('#auftrag').val($('#auftrag_text').html().trim());
            }
            $('#country_text').hide(100);
            $('#country_id').show(100);
            $('#country_id').val($('#country__id').val());
            $('#address_text').hide(100);
            $('#address').show(100);
            $('#address').val($('#address_text').html().trim());
            $('#save_details').show(100);
            $('#cancel_details').show(100);
            $('#edit_details').hide(100);
            $('.select2').show();
            $('#select2-country_id-container').html($('#country_text').html());
            $('#select2-country_id-container').attr('title', $('#country_text').html());
            $('#select2-customer_id-container').html($('#customer').html());
            $('#select2-customer_id-container').attr('title', $('#customer').html());
        })

        // cancel the editing of the main details
        $('#cancel_details').click(function() {
            $('#customer').show(100);
            $('#customer_id').hide(100);
            $('#customer_order_text').show(100);
            $('#customer_order').hide(100);
            $('#auftrag_text').show(100);
            $('#auftrag').hide(100);
            $('#country_text').show(100);
            $('#country_id').hide(100);
            $('#address_text').show(100);
            $('#address').hide(100);
            $('#save_details').hide(100);
            $('#cancel_details').hide(100);
            $('#edit_details').show(100);
            $('.select2').hide();
        })

        // save the main details
        $('#save_details').click(function() {
            $('#save_details').prop('disabled', true);
            $.ajax({
                url: '/orders/{{ $order->id }}/update/details',
                method: 'PATCH',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    customer_id: $('#customer_id').val(),
                    customer_order: $('#customer_order').val(),
                    auftrag: $('#auftrag').val(),
                    destination_id: $('#destination_id').val(),
                },
                error: function(err) {
                    console.log(err);
                    let errors = err.responseJSON.message;
                    let errors_arr = [];
                    for (let error in errors) {
                        errors[error].forEach(el => {
                            errors_arr.push(el + '<br>');
                        });
                    }
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Eroare',
                        html: errors_arr.toString().split(',').join(''),
                        showConfirmButton: false,
                        timer: 10000,
                        toast: true
                    });
                    $('#save_details').prop('disabled', false);
                },
                success: function(response) {
                    Swal.fire({
                        position: 'top-end',
                        type: response.status,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    $('#customer__id').val(response.order.customer_id);
                    $('#customer').show(100).html(response.customer.name);
                    $('#customer_id').hide(100);
                    $('#customer_order_text').show(100).html(response.order.customer_order);
                    $('#customer_order').hide(100);
                    $('#auftrag_text').show(100).html(response.order.auftrag);
                    $('#auftrag').hide(100);
                    $('#country__id').val(response.country.id);
                    $('#country_text').show(100).html(response.country.name);
                    $('#country_id').hide(100);
                    $('#destination_id').val(response.order.destination_id);
                    $('#address_text').show(100).html(response.destination.address);
                    $('#address').hide(100);
                    $('#save_details').prop('disabled', false);
                    $('#save_details').hide(100);
                    $('#cancel_details').hide(100);
                    $('#edit_details').show(100);
                    $('.select2').hide();
                }
            });
        });

        // confirm existing destination or add a new one for the current customer
        $('#address').blur(function() {
            let customer_id = $('#customer_id').val();
            let address = $('#address').val();
            let country_id = $('#country_id').val();
            $.ajax({
                url: `/customers/${customer_id}/destinations/find`,
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    customer_id, address, country_id
                },
                type: 'POST',
                success: function(response) {
                    $('#destination_id').val(response.data);
                }
            });
        });

        // return all destinations for the selected customer and country
        $('#address').focus(function() {
            let customer_id = $('#customer_id').val();
            let country_id = $('#country_id').val();
            $.ajax({
                url: `/customers/${customer_id}/destinations/search`,
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    customer_id, country_id
                },
                type: 'POST',
                success: function(response) {
                    $('#autocomplete').html('');
                    if (response.count > 0) {
                        response.data.forEach(element => {
                            let string = `<a class="dropdown-item" onclick="select(this.innerHTML)" href="#">${element}</a>`
                            $('#autocomplete').append(string);
                        });
                    } else {
                            let string = `<a class="dropdown-item" href="#">Nu exista nici o adresa pentru clientul si tara selectata!</a>`
                            $('#autocomplete').append(string);
                    }

                }
            });
        });

        // allow editing of the observations
        $('#edit_observations').click(function() {
            $('#observations').val($('#observations_text').html().trim());
            tinymce.get('observations').show(100);
            $('#save_observations').show(100);
            $('#cancel_observations').show(100);
            $('#observations_text').hide(100);
            $('#edit_observations').hide(100);
        });

        // cancel editing of the observations
        $('#cancel_observations').click(function() {
            tinymce.get('observations').hide(100);
            $('#save_observations').hide(100);
            $('#cancel_observations').hide(100);
            $('#observations_text').show(100);
            $('#edit_observations').show(100);
        });

        // save the main details
        $('#save_observations').click(function() {
            $('#save_observations').prop('disabled', true);
            $.ajax({
                url: '/orders/{{ $order->id }}/update/observations',
                method: 'PATCH',
                dataType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    observations: tinymce.activeEditor.getContent(),
                },
                error: function(err) {
                    console.log(err);
                    Swal.fire({
                        position: 'top-end',
                        type: 'error',
                        title: 'Eroare',
                        titleText: err.responseJSON.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    $('#save_observations').prop('disabled', false);
                },
                success: function(response) {
                    Swal.fire({
                        position: 'top-end',
                        type: response.status,
                        title: 'Succes',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 5000,
                        toast: true
                    });
                    $('.tox-tinymce').hide(100);
                    tinymce.get('observations').hide(100);
                    $('#observations_text').html(response.order.observations);
                    $('#save_observations').prop('disabled', false);
                    $('#save_observations').hide(100);
                    $('#cancel_observations').hide(100);
                    $('#observations_text').show(100);
                    $('#edit_observations').show(100);
                }
            });
        });
        @can('planificare')
            // allow editing of kw for customer, production, delivery and eta
            $('#edit_dates').click(function() {
                $('#customer_kw_text').hide(100);
                $('#customer_kw').show(100).val($('#customer__kw').val().split('-').reverse().join('.'));
                $('#production_kw_text').hide(100);
                $('#production_kw').show(100).val($('#production__kw').val().split('-').reverse().join('.'));
                $('#delivery_kw_text').hide(100);
                $('#delivery_kw').show(100).val($('#delivery__kw').val().split('-').reverse().join('.'));
                $('#eta_text').hide(100);
                $('#eta').show(100).val($('#eta__').val().split('-').reverse().join('.'));
                $('#edit_dates').hide(100);
                $('#save_dates').show(100);
                $('#cancel_dates').show(100);
            });

            // cancel the edition of kw for customer, production, delivery and eta
            $('#cancel_dates').click(function() {
                $('#customer_kw_text').show(100);
                $('#customer_kw').hide(100);
                $('#production_kw_text').show(100);
                $('#production_kw').hide(100);
                $('#delivery_kw_text').show(100);
                $('#delivery_kw').hide(100);
                $('#eta_text').show(100);
                $('#eta').hide(100);
                $('#edit_dates').show(100);
                $('#save_dates').hide(100);
                $('#cancel_dates').hide(100);
            })

            // save the kw for customer, production, delivery and eta
                $('#save_dates').click(function() {
                    let customer_kw = $('#customer_kw').val();
                    let production_kw = $('#production_kw').val();
                    let delivery_kw = $('#delivery_kw').val();
                    let eta = $('#eta').val();
                    $('#save_dates').hide(100);
                    $('#cancel_dates').hide(100);
                $.ajax({
                    url: '/orders/{{ $order->id }}/update/dates',
                    method: 'PATCH',
                    dataType: 'json',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        customer_kw: customer_kw.split('.').reverse().join('-'),
                        production_kw: production_kw.split('.').reverse().join('-'),
                        delivery_kw: delivery_kw.split('.').reverse().join('-'),
                        eta: eta.split('.').reverse().join('-')
                    },
                    error: function(err) {
                        console.log(err);
                        let errors = err.responseJSON.message;
                        let errors_arr = [];
                        for (let error in errors) {
                            errors[error].forEach(el => {
                                errors_arr.push(el + '<br>');
                            });
                        }
                        Swal.fire({
                            position: 'top-end',
                            type: 'error',
                            title: 'Eroare',
                            html: errors_arr.toString().split(',').join(''),
                            showConfirmButton: false,
                            timer: 10000,
                            toast: true
                        });
                        $('#save_dates').show(100);
                        $('#cancel_dates').show(100);
                    },
                    success: function(response) {
                        Swal.fire({
                            position: 'top-end',
                            type: response.status,
                            title: 'Succes',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 5000,
                            toast: true
                        });
                        $('#customer_kw_text').html(response.order.customer_kw_text);
                        $('#customer_kw_text').show(100);
                        $('#customer_kw').hide(100);
                        $('#customer__kw').val(response.order.customer_kw);
                        $('#production_kw_text').html(response.order.production_kw_text);
                        $('#production_kw_text').show(100);
                        $('#production_kw').hide(100);
                        $('#production__kw').val(response.order.production_kw);
                        $('#month').html(response.order.month);
                        $('#delivery_kw_text').html(response.order.delivery_kw_text);
                        $('#delivery_kw_text').show(100);
                        $('#delivery_kw').hide(100);
                        $('#delivery__kw').val(response.order.delivery_kw);
                        $('#eta_text').html(response.order.eta_text);
                        $('#eta_text').show(100);
                        $('#eta').hide(100);
                        $('#eta__').val(response.order.eta);
                        $('#edit_dates').show(100);
                        $('#save_dates').hide(100);
                        $('#cancel_dates').hide(100);
                    }
                });
            });
        @endcan


    });
    </script>
@stop

