<!DOCTYPE>
<html lang="en">
    <head>
    <?php include('../Include/Header.php'); ?>
    </head>
    
    <body>
        <?php include('../Include/Menu.php');?>
        <div class="container">
            <h3 class="title">DEPOSITO A PLAZO</h3>
            
            <div class="tabbable">
            <?php
 
                $post_data = array("currency"=>"MN","balance"=>"S/ 50,000","days"=>"360 días","geo"=>"LI","exclude"=>"off","email"=>"otros@gmail.com","news"=>"on","source"=>"Compara","recaptcha_response"=>"");

                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_AUTOREFERER, TRUE );
                curl_setopt( $ch, CURLOPT_HEADER, 0 );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $ch, CURLOPT_URL, "https://comparabien.com.pe/producto/depositos-plazo/fondesurco-plazo-fijo-mn?prod_id=160&type=DEPOSITOS" );
                curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    /*":authority: comparabien.com.pe",
                    ":method: POST",
                    ":path: /depositos-plazo/result",
                    "accept-encoding: gzip, deflate, br",
                    "accept-language: es-US,es;q=0.9,es-419;q=0.8,en;q=0.7,gl;q=0.6",*/
                    "content-type: application/x-www-form-urlencoded",
                    "origin: https://comparabien.com.pe",
                    "referer: https://comparabien.com.pe/depositos-plazo",
                    "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36"
                ));
                
                $data = curl_exec( $ch );
                //echo $data;
                //$data = str_get_html($data);
                curl_close( $ch );
            ?>
            </div>
        </div>
    </body>
</html>

