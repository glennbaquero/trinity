@extends('web.master')

@section('content')
<div class="container">
    <div class="row justify-content-center my-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Privacy Policy</div>

                <div class="card-body">
					
                    {!! $content !!}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
