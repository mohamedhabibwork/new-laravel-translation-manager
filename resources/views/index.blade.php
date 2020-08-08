@extends('layouts::layouts.layout')
@push('js')
{{--    <script src="{{asset('vendor/translation_manager/js/jquery.js')}}"></script>--}}

<script>
    var namespaceInput = document.getElementById("namespace");
    var fileInput = document.getElementById("file");
    var languageInput = document.getElementById("language");

    namespaceInput.addEventListener('change',function () {
        var namespace = $("option:selected", this).val();
        fileInput.innerHTML='';
        $.ajax({
            type: 'GET',
            url: '{{ route('translation_manager.files') }}',
            data: {
                namespace: namespace
            },
            dataType: 'json'
        }).then(function (result) {
            for (var i = 0; i < result.length; i++) {
                fileInput.innerHTML+='<option value="' + result[i] + '">' + result[i] + '</option>';
            }
        })
            .catch(function (error) { console.log(error); });
    });

    $("#submit").on('click', function () {
        var route = '{{ route('translation_manager.edit', ['|LANGUAGE|', '|FIlE|', '|NAMESPACE|']) }}';

        var language = languageInput.value;
        var file = $("option:selected", fileInput).val();
        var namespace = $("option:selected", namespaceInput).val();

        if (!language || !file) {
            alert("Language and File are required!");

            return;
        }

        route = route
            .replace('|LANGUAGE|', language)
            .replace('|FIlE|', file)
            .replace('|NAMESPACE|', namespace);

        window.location = route;
    });
</script>
@endpush
@section('content')
    @if($errors->count())
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

            <p>
                {{ session('message') }}
            </p>
        </div>
    @endif
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <label for="language">
                    Language
                </label>

                <input name="language" id="language" class="form-control" placeholder="Language" value="{{app()->getLocale()}}">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <select name="namespace" id="namespace" class="form-control" size="6">
                    <option value=""></option>

                    @foreach($namespaces as $namespace)
                        <option value="{{ $namespace }}">
                            {{ $namespace }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <select name="file" id="file" class="form-control" size="6"></select>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <button id="submit" class="btn btn-block btn-primary">
                Submit
            </button>
        </div>
    </div>

@endsection
