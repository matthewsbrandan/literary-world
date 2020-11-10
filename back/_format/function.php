<?php
    function print_pre($p){ 
        echo "<pre class='box-shadow p-2 rounded'><h6 class='font-weight-bold'>Print Pré-Formatado</h6>";
        print_r($p);
        echo "<textarea class='d-none'>"; print_r($p); echo "</textarea>";
        echo "<span class='material-icons float-right' style='cursor: pointer' onclick='copiar($(this).prev())'>content_copy</span>";
        echo "</pre>";
    }

    function nameImage($image=''){
        $path = $_SESSION['user_id']+1000;
        return $path.'/'.$image;
    }
    
    function verificaNome($nome){
        $c=0;
        $path = "../img/".nameImage();

        if(!is_dir($path)) mkdir($path);

        do{
            $retorno=true;
            $diretorio = dir($path);
            while($arquivo = $diretorio -> read()){
                if($arquivo==$c.$nome){ $retorno=false; break; }
            }
            if(!$retorno){ $c++; } 
            $diretorio -> close();
        }while(!$retorno);
        return nameImage($c.$nome);
    }
?>
