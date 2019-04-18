@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @component('components.file-upload', [
                    'action' => action("UploadController@uploadICD9Phecode"),
                    'title' => 'Upload ICD9 Phecode'
                ])
                @endcomponent
            </div>
        </div>
    </div>
@endsection
