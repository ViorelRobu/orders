<a href="" class="edit" data-toggle="modal" data-target="#audits" onclick="audit({{ $customer->id }})"><i class="fas fa-history"></i></a>
<a href="" class="edit" data-toggle="modal" data-target="#newCustomer" onclick="fetch({{ $customer->id }})"><i class="fas fa-edit"></i></a>
<a href="" class="destinations" data-toggle="modal" data-target="#allDestinations" onclick="getDestinations({{ $customer->id }})"><i class="fas fa-map-marked-alt"></i></a>
