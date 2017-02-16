<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/mocha/3.2.0/mocha.css">
</head>
<body>
<div id="mocha"></div>

<script src="https://cdn.bootcss.com/mocha/3.2.0/mocha.js"></script>
<script src="https://cdn.bootcss.com/chai/4.0.0-canary.1/chai.min.js"></script>

<script src="{{ url('/script/jquery-3.1.0.min.js') }}"></script>
<script src="{{ url('/script/jquery-ui.js?x=1') }}"></script>


<script src="{{ url('/script/fileupload/plugins/canvas-to-blob.min.js?x=1') }}"></script>
<script src="{{ url('/script/fileupload/plugins/sortable.min.js?x=1') }}"></script>
<script src="{{ url('/script/fileupload/plugins/purify.min.js?x=1') }}"></script>

<script src="{{ url('/script/fileinput.min.js?x=1') }}"></script>

<script src="{{ url('/script/fileupload/themes/fa/theme.js?x=1') }}"></script>

<script src="{{ url('/script/sweetalert.js') }}"></script>
<script src="{{ url('/script/bootstrap.js') }}"></script>
<script src="{{ url('/script/jquery.blockUI.js') }}"></script>
<script src="{{ url('/script/jquery.form.min.js') }}"></script>
<script src="{{ url('/script/bootstrap-datetimepicker.min.js?x=2') }}"></script>
<script src="{{ url('/script/bootstrap-datetimepicker.zh-TW.js') }}"></script>
<script src="{{ url('/script/fileupload/locales/zh-TW.js?x=1') }}"></script>
<script src="{{ url('/script/bootstrap-suggest.min.js') }}"></script>
<script src="{{ url('/script/master.js?x=1') }}"></script>
<script src="{{ url('/script/vue.js') }}"></script>
<script src="{{ url('/script/Sortable.min.js') }}"></script>
<script src="{{ url('/script/select2.min.js') }}"></script>
<script src="{{ url('/script/vuejs-paginator.min.js') }}"></script>
<script src="{{ url('/script/jquery.mockjax.js') }}"></script>
<script src="{{ url('/script/jquery.mockjson.js') }}"></script>

<div class="container-fluid">
    <div class="content">
        <script>
            var url = "{{ url('/') }}";
            var assert = chai.assert;
            var expect = chai.expect;
        </script>
        <div id="mocha"></div>
        <script>mocha.setup('bdd')</script>
        @yield('content')

        <script>
            mocha.run();
        </script>
    </div>
</div>
</body>
</html>