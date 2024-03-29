<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>產品履歷系統-@yield('title')</title>

	<!-- 最新編譯和最佳化的 CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

	<!-- 選擇性佈景主題 -->
	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">-->

	<!-- 最新編譯和最佳化的 JavaScript -->
	<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>-->

	<link rel="stylesheet" href="{{ url('/css/bootstrap-theme.min.css') }}">
	<!--<link rel="stylesheet" href="{{ url('/css/bootstrap.css') }}">-->
	
	<link rel="stylesheet" href="{{ url('/css/bootstrap-datetimepicker.min.css?x=2') }}">
	<link rel="stylesheet" href="{{ url('/css/jquery-ui.css') }}">
	<link rel="stylesheet" href="{{ url('/css/sweetalert.css') }}">
	<link rel="stylesheet" href="{{ url('/css/fileinput.min.css') }}">
	<link rel="stylesheet" href="{{ url('/css/select2.min.css') }}">
	<!-- Fonts -->
	<!--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>-->

	<script src="https://google.github.io/traceur-compiler/bin/traceur.js"></script>
	<script src="https://google.github.io/traceur-compiler/bin/BrowserSystem.js"></script>
	<script src="https://google.github.io/traceur-compiler/src/bootstrap.js"></script>

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

	<!--
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.4.2/Sortable.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<script src="https://cdn.jsdelivr.net/vuejs-paginator/2.0.0/vuejs-paginator.min.js"></script>
	-->
	<script>
		var url = "{{ url('/') }}";
		$(function () {
    		$("[data-toggle='tooltip']").tooltip();
		});
	</script>
</head>
<body>
	<nav class="navbar navbar-default" style="margin-bottom: 0px;">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="#" style="padding:5px;">
					<img alt="UPGI" src="{{ url('/img/upgi.png') }}" style="height:40px;"/>
				</a>
			</div>
			<div class="collapse navbar-collapse navbar-left">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/Duty/DutyList') }}">生產職班表</a></li>
					<li><a href="{{ url('/History/HistoryList') }}">產品履歷表</a></li>
					<li><a href="http://upgi.ddns.net:9004/universalForm/index.html?formReference=isProdDataForm" target="_blank">生產條件記錄表</a></li>
					<li><a href="{{ url('/QC/QCList') }}">品管管制表</a></li>
					<li><a href="{{ url('/Report/ProductionMeeting') }}" target="_blank">生產資訊報表</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
							生產缺點記錄<span class="caret"></span>
						</a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{{ url('/nav/check.list') }}">檢查表</a></li>
							<li class="divider"></li>
							<li><a href="{{ url('/nav/defect.template') }}">缺點套板管理</a></li>
							<li><a href="{{ url('/nav/defect.item') }}">缺點上層項目管理</a></li>
							<li><a href="{{ url('/nav/defect.defect') }}">缺點項目管理</a></li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="collapse navbar-collapse navbar-right">
			</div>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="content">
			@yield('content')
		</div>
	</div>
</body>
</html>