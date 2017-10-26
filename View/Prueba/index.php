<!DOCTYPE html>
<html>
<head>
	<title>Empresa</title>
	<script src="../../Assets/js/jquery-2.1.4.min.js"></script>
</head>
<body>

	<script type="text/javascript">
		$(document).ready(function(){

				$.ajax({
				    type:'GET',
				    url: 'http://192.168.0.3/TEST/servicios/Intrial/tercero/lista',
				    data:'',
				    success:function(data){

				        console.log(data);
				    }
				});
			
			
        });
	</script>
</body>
</html>