<a href="" class="edit" data-toggle="modal" data-target="#newUser" onclick="fetch({{ $user->id }})"><i class="fas fa-edit"></i></a>
@if ($user->is_active == 1)
    <a href="#" class="deactivate" style="color: red" onclick="changeStatus('inactive', {{ $user->id }})"><i class="fas fa-times"></i></a>
@else
    <a href="#" class="activate" style="color: green" onclick="changeStatus('active', {{ $user->id }})"><i class="fas fa-check"></i></a>
@endif

