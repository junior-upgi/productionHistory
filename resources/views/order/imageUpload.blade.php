@extends('layouts.masterpage')
@section('content')
    <form class="form-signin" role="form" action="service/formSubmit/{{$table}}" method="POST" style="max-width:480px;padding:15px;margin:auto;">
        <h2>上傳生產成品圖片</h2>
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
        @for($i=0; $i < count($key); $i++)
            <input type="hidden" name="{{$key[$i]}}" value="{{$value[$i]}}">
        @end
        <div class="form-group">
            <input id="img" name="img" type="file" class="file-loading" data-show-upload="false" accept="image/*">
            <script>
                $("#img").fileinput({
                    language: 'zh-TW',
                    previewFileType: "image",
                    allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
                    previewClass: "bg-warning",
                    browseClass: "btn btn-success",
                    browseLabel: "選擇圖片",
                    browseIcon: "<i class=\"glyphicon glyphicon-picture\"></i> ",
                    removeClass: "btn btn-danger",
                    removeLabel: "移除",
                    removeIcon: "<i class=\"glyphicon glyphicon-trash\"></i> ",
                    fileActionSettings: {
                        showZoom: false,
                        showDrag: false,
                    }
                });
            </script>
        </div>
    <form>
@endsection