<x-app-layout>
    @section('styles')
        <link rel="stylesheet" href="{{asset('css/dropzone.css')}}">
        <style>
            /* img{
                cursor: pointer;
            } */
            .album-img{
                width: 350px;
                height: 350px;
                max-width: 350px;
                max-height: 350px;
                border-radius: 5px
            }
        </style>
    @endsection


        <x-slot name="header">
            <h2 class="text-center font-semibold text-xl text-gray-800 leading-tight">
                {{Str::ucfirst(($album->name)) . ' Album' }}
            </h2>
        </x-slot>

        <div class="card shadow my-4">
            <div class="card-header py-3">
                <a class="btn btn-sm btn-primary text-white float-right" data-toggle="modal" data-target="#newPic">New Picture +</a>
            </div>
            <div class="card-body">

                {{-- Flashed msgs  --}}

                @error('name')
                    <div class="mb-3 alert alert-danger">{{ $message }}</div>
                @enderror

                @if (session()->has('error'))
                    <div class="mb-3  alert alert-danger">{{ session()->get('error') }}</div>
                @endif

                @if (session()->has('success'))
                    <div class="mb-3 alert alert-success">{{ session()->get('success') }}</div>
                @endif

                <div class="row">
                    @foreach ($pictures as $pic)
                        <div class="col-lg-4">
                            <img class="album-img mb-4" src="{{asset('images/'.$album->name.'/'.$pic->name)}}" alt="">
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    {{-- Modals --}}
    @section('modals')
        <div class="modal fade" id="newPic" tabindex="-1" role="dialog" aria-labelledby="newPic" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="exampleModalCenterTitle">New Picture</h5>
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <form class="dropzone dz-clickable" id="add_form" enctype="multipart/form-data" action="{{route('picture.store')}}" method="post">
                            @csrf
                            <input type="hidden" name="album_name" value="{{$album->name}}">
                            <input type="hidden" name="album_id" value="{{$album->id}}">
                            {{-- <div ></div> --}}
                            {{-- <div class="dz-default dz-message">
                                <span>Drop Files Here To Upload</span>
                            </div> --}}

                        </form>
                        <div class="pt-3">
                            <input type="button" class="btn btn-primary" id="submitBtn" value="Upload">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- <div class="modal fade" id="newPic" tabindex="-1" role="dialog" aria-labelledby="newPic" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-info text-white">
                        <button class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center">
                        <img style="width:100%;" id="modal_img" alt="">
                    </div>

                </div>
            </div>
        </div> --}}
    @endsection



    {{-- Scripts --}}
    @section('scripts')
        <script src="{{asset('js/dropzone-min.js')}}"></script>
        <script>
            // url = "{{route('picture.store')}}";
            let myDropzone = new Dropzone("#add_form", {
                // url: url,
                autoProcessQueue: false,
                parallelUploads: 2,
            });
            console.log(myDropzone);

            $('#submitBtn').click( () => {
                myDropzone.processQueue();
            });

            myDropzone.on("complete", file => {
                window.location.reload();
            });
        </script>
    @endsection
</x-app-layout>
