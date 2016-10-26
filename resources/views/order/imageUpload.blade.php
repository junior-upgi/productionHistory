@extends('layouts.masterpage')
@section('content')
    <form class="form-signin" role="form" action="{{url('/')}}/service/formSubmit/{{$table}}" method="POST" style="max-width:480px;padding:15px;margin:auto;" enctype="multipart/form-data">
        <h2>上傳生產成品圖片</h2>
        @for($i=0; $i < count($key); $i++)
            <input type="hidden" name="{{$key[$i]}}" value="{{$value[$i]}}">
        @endfor
        <div class="form-group">
            <input id="img" name="img" type="file" class="file-loading" data-show-upload="false" accept="image/*">
            <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off">完成</button>
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