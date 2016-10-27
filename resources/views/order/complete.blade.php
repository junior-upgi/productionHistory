<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>產品履歷</title>
	<script>
		var url = "{{url('/')}}";
	</script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="{{url('/')}}/css/bootstrap.css">
	
	<link rel="stylesheet" href="{{url('/')}}/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" href="{{url('/')}}/css/jquery-ui.css">

	<script src="{{url('/')}}/script/jquery-3.1.0.min.js"></script>
	<script src="{{url('/')}}/script/jquery-ui.js?x=1"></script>
	
	<script src="{{url('/')}}/script/bootstrap.js"></script>
	<script src="{{url('/')}}/script/bootstrap-datetimepicker.min.js"></script>
	<script src="{{url('/')}}/script/bootstrap-datetimepicker.zh-TW.js"></script>
</head>
<body>
	<div class="container" style="width: 100%;">
		<div class="content text-center" style="max-width:480px;padding:15px;margin:auto;">
			<h3>資料已儲存成功</h3>
		</div>
	</div>
</body>
</html>