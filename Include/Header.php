<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
<meta http-equiv="expires" content="Sun, 19 Nov 1978 05:00:00 GMT">
<meta http-equiv="expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--CSS -->
<link rel="stylesheet" type="text/css" href="../Assets/bootstrap-3.3.7/dist/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="../Assets/css/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="../Assets/css/bootstrap-datepicker3.min.css">
<link rel="stylesheet" type="text/css" href="../Assets/font-awesome-4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="../Assets/bootstrap-slider/css/bootstrap-slider.min.css" />
<link rel="stylesheet" href="../Assets/chosen/chosen.css">
<link rel="stylesheet" type="text/css" href="../Assets/css/style.css">
<!--Fin CSS-->

<!--JS-->
<script src="../Assets/js/jquery-2.1.4.min.js"></script>
<script src="../Assets/bootstrap-3.3.7/dist/js/bootstrap.min.js"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>
<script type="text/javascript" src="../Assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../Assets/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="../Assets/bootstrap-slider/js/bootstrap-slider.min.js"></script>
<script type="text/javascript" src="../Assets/js/jquery.blockUI.js"></script>
<script type="text/javascript" src="../Assets/chosen/chosen.jquery.js"></script>
<!--FIN JS-->
<script>
$(document).ready(function(){
    var config = {
        '.chosen'                  : {},
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : { allow_single_deselect: true },
        '.chosen-select-no-single' : { disable_search_threshold: 10 },
        '.chosen-select-no-results': { no_results_text: 'Oops, nothing found!' },
        '.chosen-select-rtl'       : { rtl: true },
        '.chosen-select-width'     : { width: '95%' }
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
});
</script>