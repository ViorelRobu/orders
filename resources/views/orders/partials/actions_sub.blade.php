<a href="" class="editPos" data-toggle="modal" data-target="#editDetails" onclick="fetchPosition({{ $detail->position }})"><i class="fas fa-edit"></i></a>
<a href="" class="editPos" data-toggle="modal" data-target="#copyDetail" onclick="setCopyId({{ $detail->id }})"><i class="fas fa-copy" style="color: green"></i></a>
<a href="" class="deletePos" data-toggle="modal" data-target="#deletePos" onclick="setIdPos({{ $detail->position }})"><i class="fas fa-minus-circle" style="color: red"></i></a>
<a href="" class="deletePak" data-toggle="modal" data-target="#deletePak" onclick="setIdPak({{ $detail->id }})"><i class="fas fa-trash-alt" style="color: red"></i></a>
