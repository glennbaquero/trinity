@extends('admin.master')

@section('pageTitle', 'Consultations #'. $consultation->consultation_number)

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Consultations #{{ $consultation->consultation_number }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.consultations.index') }}">Consultations</a></li>
                </ol>
            </div>
        </div>
    </section>
    
    <section class="content">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Information</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab">Prescription</a></li>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane show active" id="tab1">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="badge badge-primary">Consultation #{{ $consultation->consultation_number }}</h1>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12"><strong>Patient: </strong>{{ $consultation->user->renderName() }}</div>
                                        <div class="col-md-12"><strong>Doctor: </strong>{{ $consultation->doctor->renderName() }} | <strong>Ratings: </strong> <label class="badge badge-primary">{{ $consultation->doctor->computeRatings() }}</label></div>
                                        <div class="col-md-12"><strong>Consultation Fee: </strong><label class="badge badge-danger">Php {{ $consultation->consultation_fee }}</label></div>
                                        <div class="col-md-12"><strong>Schedule: </strong>{{ $consultation->renderScheduleDate() }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-header">
                                <h1 class="badge badge-primary">Conversation</h1>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        @foreach($consultation->chats as $chat)
                                            <div class="col-md-12">
                                                <strong>{{ $chat->renderFullName($chat->sender_type, $chat->sender_id) }}: </strong>{!! $chat->message !!}
                                                <br />
                                                @if($chat->file_path)
                                                    <a href="{{ $chat->renderImagePath('file_path') }}" target="_blank">
                                                        <img 
                                                        class="img img-thumbnail"
                                                        width="100px"
                                                        height="100px" 
                                                        src="{{ $chat->renderImagePath('file_path') }}">
                                                    </a>
                                                @endif
                                                <p><strong>Sent time: </strong> {{ $chat->created_at }}</p>
                                                <hr />
                                            </div>
                                        @endforeach
                                        @if(!count($consultation->chats))
                                            <div class="col-md-12 text-center">No conversation</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane show" id="tab2">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="badge badge-primary">Prescription</h1>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    @if($consultation->prescription)
                                        <div class="row">
                                            <div class="col-md-12"><strong>Next Action: </strong>{{ $consultation->prescription->next_action }}</div>
                                            <div class="col-md-12"><strong>Notes:</strong> {{ $consultation->prescription->notes }}</div>
                                            <div class="col-md-12"><strong>Date validity: </strong>{{ $consultation->prescription->date_validity }}</div>
                                        </div>
                                    @else
                                        <div class="col-md-12 text-center">
                                            No prescription
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($consultation->prescription)
                            <div class="card">
                                <div class="card-header">
                                    <h1 class="badge badge-primary">Medicines</h1>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="row">
                                            @foreach($consultation->prescription->meds as $med)
                                                <div class="col-md-12">
                                                    <strong>Product Name: </strong> {{ $med->product ? $med->product->name : $med->name }}
                                                </div>
                                                <div class="col-md-12">
                                                    <strong>Dosage: </strong> {{ $med->dosage }}
                                                </div>
                                                <div class="col-md-12">
                                                    <strong>Duration: </strong> {{ $med->duration }}
                                                </div>
                                                <div class="col-md-12">
                                                    <strong>Notes: </strong> {{ $med->notes }}
                                                </div>

                                                <div class="col-md-12">
                                                    <hr />                                                                         
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                    
                </div>
            </div>
        </div>
    </section>
</div>

@endsection