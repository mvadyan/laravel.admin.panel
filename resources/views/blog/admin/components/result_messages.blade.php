@if($errors->any())
    <div class="row justify-content-center alert_message">
        <div class="col-md-12">
            <div class="alert alert-danger text-center" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
                {{--{{$errors->first}}--}}
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif
@if(session('success'))
    <div class="row justify-content-center alert_message">
        <div class="col-md-12">
            <div class="alert alert-success text-center" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
                {{session()->get('success')}}
            </div>
        </div>
    </div>
@endif
