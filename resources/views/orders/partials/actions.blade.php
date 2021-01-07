@can('planificare')
    <a href="" class="edit" data-toggle="modal" data-target="#audits" onclick="audit({{ $order->id }})"><i class="fas fa-history"></i></a>
    <a href="" class="edit" data-toggle="modal" data-target="#newOrder" onclick="fetch({{ $order->id }})"><i class="fas fa-edit"></i></a>
    <a href="" class="edit" data-toggle="modal" data-target="#copyOrder" onclick="setOrder({{ $order->id }})"><i class="fas fa-copy"></i></a>
@endcan
