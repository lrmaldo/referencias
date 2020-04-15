<?php



//supongamos que el cliente pone 2 ceros al inicio 
$cadena = "0134";

$cadena_formateada2 ='';


//busca los dos primeros ceros
$posicion_coincidencia = strpos($cadena, '0',0);
$posicion_0 = strpos($cadena,'0',1);
///se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
if ($posicion_coincidencia === false) {
    //si al inicio no hay ceros devuelve false
    echo "NO se ha encontrado la palabra deseada!!!!";
    echo $cadena;
    } else {
            //si hay devolvera la posicion de esos 0 
            echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia."-".$posicion_0;
            
            echo "<br>";
            // se quita los dos ceros  y se para a una variable 

            $cadena_formateada2 = substr($cadena, 2);
             echo $cadena_formateada2;
            }