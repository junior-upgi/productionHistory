@extends('layouts.masterpage')
@section('content')
    <script>
        $(document).ready(function () {
            $("#page_load").load("http://upgi.ddns.net:9004/productionHistory/isProdDataForm");
        });
    </script>
    <div id="page_load"></div>
    <!--
    <iframe src="http://upgi.ddns.net:9004/productionHistory/isProdDataForm" width="100%" height="100%" 
        frameborder="0" scrolling="no"></iframe>
    -->
@endsection