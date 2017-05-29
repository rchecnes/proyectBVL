<!DOCTYPE>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
        <meta http-equiv="expires" content="Sun, 19 Nov 1978 05:00:00 GMT">
        <meta http-equiv="expires" content="-1">
        <meta http-equiv="Cache-Control" content="no-cache">
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
        <meta name="robots" content="noindex">

        <title>moving background login form - Bootsnipp.com</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <style type="text/css">
            body{
                background-color: #444;
                background: #fff; 
            }

            .vertical-offset-100{
                padding-top:100px;
            }
        </style>
        <script src="../Assets/js/jquery-2.1.4.min.js"></script>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>
    </head>
<body>

<div class="container">
    <div class="row vertical-offset-100">
        <div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h1 class="panel-title">Login</h1>
			 	</div>
			  	<div class="panel-body">
			    	<form accept-charset="UTF-8" role="form" method="POST" id="form_login" action="../Controller/LoginC.php?accion=valid">
                        <fieldset>
                            <div class="form-group">
                                <?php
                                if(isset($error) && $error=='si'){
                                echo '<div class="alert alert-danger">
                                    '.$msj.'<strong>!</strong>
                                </div>';
                                }?>
                                <p></p>
                            </div>

    			    	  	<div class="form-group">
    			    		    <input class="form-control" placeholder="E-mail ó User name" name="email" id="email" type="text">
    			    		</div>
    			    		<div class="form-group">
    			    			<input class="form-control" placeholder="Password" name="password" id="password" type="password" value="">
    			    		</div>
    			    		<!--<div class="checkbox">
    			    	    	<label>
    			    	    		<input name="remember" type="checkbox" value="Remember Me"> Recordar
    			    	    	</label>
    			    	    </div>-->
    			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
                            
    			    	</fieldset>
			      	</form>
                    <input class="btn btn-lg btn-default btn-block" type="submit" value="Crear Cuenta">
			    </div>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript">
    $(document).mousemove(function(e){

        $('#form_login').bootstrapValidator({
            // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                email: {
                    validators: {
                            notEmpty: {
                            message: 'Ingrese Email'
                        }
                    }
                },
                 password: {
                    validators: {
                        notEmpty: {
                            message: 'Ingrese Password'
                        }
                    }
                }
            }
            
        });
    });
	</script>


</body></html>