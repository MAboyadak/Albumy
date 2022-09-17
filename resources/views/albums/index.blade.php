<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Albums') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="float-right p-2 m-2">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#newAlbum">New Album +</button>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Album Name</th>
                                <th>Action</th>
                            <tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($albums as $album)
                            <tr>
                                <td>{{$i}}</td>
                                <td><a href="{{route('album.show',$album->id)}}">{{$album->name}}</a></td>
                                <td>
                                    <a class="btn btn-sm btn-info text-white" href="{{route('album.show',$album->id)}}">View</a>
                                    <a onclick="editAlbum({{$album->id}})" class="btn btn-sm text-white btn-success">Edit</a>
                                    <a onclick="checkAlbumEmpty({{$album->id}})" class="btn btn-sm text-white btn-danger">Delete</a>
                                </td>
                            </tr>
                              <?php $i ++ ;?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>







    {{-- Modals --}}
    @section('modals')
        <div class="modal fade" id="newAlbum" tabindex="-1" role="dialog" aria-labelledby="newAlbum" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="exampleModalCenterTitle">New Album</h5>
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{route('album.store')}}" method="post">

                            @csrf


                            <div class="form-group">
                              <label for="exampleInputEmail1">Album Name :</label>
                              <input type="text" name="name" class="form-control">
                            </div>
                            <div style="text-align: end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editAlbum" tabindex="-1" role="dialog" aria-labelledby="newAlbum" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Edit Album</h5>
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form action="{{route('album.update')}}" method="post">

                            @csrf

                            <div class="form-group">
                              <label for="exampleInputEmail1">Album Name :</label>
                              <input type="hidden" id="album_id" name="album_id" value="">
                              <input type="text" name="name" class="form-control">
                            </div>
                            <div style="text-align: end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteAlbum" tabindex="-2" role="dialog" aria-labelledby="deleteAlbum" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Delete Album</h5>
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active" id="delete-tab" data-toggle="tab" href="#delete" role="tab" aria-controls="home" aria-selected="true">Delete</a>
                            </li>

                            <li class="nav-item">
                              <a class="nav-link" id="move-tab" data-toggle="tab" href="#move" role="tab" aria-controls="move" aria-selected="false">Move</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">

                            {{-- Delete Tab --}}
                            <div class="tab-pane fade show active" id="delete"  aria-labelledby="delete-tab">
                                <h2 class="text-center">
                                    <span class="bg-danger text-white d-inline-block m-2" style="font-size:30px;font-weight:bold"> Warning!! </span> <br>
                                    (This album contains pictures, if you deleted it you will delete all pictures too).
                                </h2>
                                <input type="hidden" id="delete_album_id">
                                <div class="mt-3">
                                    <a onclick="deleteAlbum()" class="btn btn-sm text-white btn-info">Yes, Delete</a>
                                </div>

                            </div>

                            {{-- Move Tab --}}
                            <div class="p-4 tab-pane fade" id="move"  aria-labelledby="move-tab">
                                <h2 class="p-2">Transfers pictures to another album :</h2>

                                <form action="{{route('album.move')}}" method="POST">
                                    @csrf
                                    <select class="form-control" name="new_id" id="new_id">
                                        @foreach ($albums as $album)
                                            <option value="{{$album->id}}">{{$album->name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="current_id" id="current_id">

                                    <div class="mt-3">
                                        <button class="btn btn-info">Move now</button>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection


    {{-- Scripts --}}
    @section('scripts')
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

            function editAlbum(id)
            {
                $("#editAlbum").modal()
                $("#album_id").val(id);
            }

            function checkAlbumEmpty(id)
            {

                let url = "{{route('album.check',':id')}}"
                url = url.replace(':id',id);

                $.ajaxSetup({
                    headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                });

                $.ajax({
                    url: url,
                    type: "get",
                    success: function (data) {
                        if(data){
                            Swal.fire({
                                title:'Warning !!',
                                text:'Are you sure you wanna delete it?',
                                icon:'question',
                                showCancelButton: true
                            }).then( result => {
                                if (result.isConfirmed) {
                                    url = "{{route('album.delete',':id')}}";
                                    url = url.replace(':id',id)
                                    $.ajax({
                                        url: url,
                                        type: "post",
                                        success: function (data){
                                            Swal.fire('Deleted!', '', 'success')
                                            setTimeout(() => {
                                                window.location.reload();
                                            }, 1000);
                                        }
                                    })
                                }
                            })
                        }else{
                            showDeleteModal(id);
                        }
                    }
                });
            }

            function showDeleteModal(id)
            {
                // console.log(id);
                $('#deleteAlbum').modal('show');
                $('#delete_album_id').val(id);
                $('#current_id').val(id);
                $('select option[value="' + $('#current_id').val() + '"]').remove();
            }

            function deleteAlbum()
            {
                let album_id = $('#delete_album_id').val();

                let url = "{{route('album.delete',':id')}}"
                url = url.replace(':id',album_id);

                $.ajax({
                    url: url,
                    type: "post",
                    success: function (data) {
                        console.log(data)
                        if(data){
                            Swal.fire({
                                title:'Success!',
                                text:'Album Deleted Successfully',
                                icon:'success',
                            });
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }
                    }
                });
            }
        </script>
    @endsection
</x-app-layout>
