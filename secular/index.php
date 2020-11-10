<?php
    session_start();
    include('../../conn/function.php');
    if(!isset($_SESSION['user_id'])){
        $lworld_id = 2;
        if(!(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0)){
            if(isset($_COOKIE['mtworldPass'])&&isset($_COOKIE['mtworldKey'])){
                $sql="select * from usuario where email='{$_COOKIE['mtworldPass']}' and senha='{$_COOKIE['mtworldKey']}';";
                if($linha = (enviarComand($sql,'bd_mtworld'))->fetch_assoc()){
                $_SESSION['user_mtworld'] = $linha['id'];
                $_SESSION['user_mtworld_nome'] = $linha['nome'];
                $_SESSION['user_mtworld_email'] = $linha['email'];
                }else header('Location: ../sign-in/'); 
            }else header('Location: ../sign-in/');
        }
        if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){
            $sql = "select * from user_sites where sites_id='$lworld_id' and usuario_id='{$_SESSION['user_mtworld']}';";
            $linha = (enviarComand($sql,'bd_mtworld'))->fetch_assoc();
            if(isset($linha['status'])&&$linha['status']=='ativo'){
                $sql = "select * from tbluser where user_email='{$linha['login']}' and user_senha='{$linha['senha']}';";
                $res = (enviarComand($sql,'bd_lworld'))->fetch_assoc();
                if(isset($res['user_id'])){
                    $_SESSION['user_id'] = $res['user_id'];
                    $_SESSION['user_nome']= $res['user_nome'];
                    $_SESSION['user_email']= $res['user_email'];
                }
                else header("Location: ../sign-in/");
            }else header("Location: ../sign-in/");            
        }
    }
    if(isset($_GET['mtworld'])) header('Location: ../sign-in/index.php?mtworld');
    
    function nameImage($image='',$userPath = false){
        $path = "../img/";
        if($userPath){ $path.= ($_SESSION['user_id']+1000).'/'; }
        return $path.$image;
    }
?>
    <!DOCTYPE HTML>
    <html>
    <head>
        <title>Literary World > Secular</title>
        <meta charset="utf-8">
        <link rel="icon" href="../../img/icones/blur_on.png" type="image/png"/>
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="../progress/css-progress.css"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <style>
            body{ overflow: hidden; }
            .div-master{ height: 100vh }
            #menuLateral{ padding-top: 10px; }
            #menuLateral h1{
                text-align: center;
                font-size: 18pt;
                color: #eee;
            }
            #menuLateral hr{ background: #888; }
            #menuLateral{ background: url('../wallpaper/neve.jpg'); background-size: 100%;  padding: 0px; }
            #menuLateral div{
                padding: 15px;
                width: 100%;
                height: 100%;
                background: rgba(20,20,20,.2);
            }
            #menuLateral li a{ color: #ccc; }
            #menuLateral li a.disabled{ color: #777; }
            #menuLateral li a.active{
                color: #555;
                font-weight: 600;
                background: #ddd;
            }
            #conteudo section{
                overflow: auto;
                height: 96vh;
                margin-top: 8px;
                padding-bottom: 10px;
            }
            .grad-bw{ background-image: linear-gradient(to right,transparent,rgba(30,20,20,.09)); }
            .adesivo{ vertical-align: -6px;float: right;}
            #hSecular label{ font-weight: bold;}
            #hSecular #divSecular{ display: inline-block;vertical-align: 5px; padding: 5px 20px 2px 20px }
            #secAtual,#secList,#secQuadro{ display: none }
            #hSecular #divSecular div{ 
                margin: 5px;
                padding: 5px 20px 5px 20px;
                border-radius: 5px;
                border: 1px solid #bbb;
            }
            #hSecular i{ vertical-align: -6px;font-size: 20pt;}
            i#iSecQuadro{ vertical-align: -7.5px;}
            .pointer{  
                cursor: pointer;
                background-image: linear-gradient(to right,rgba(130,120,120,.5),rgba(30,20,20,.0));
            }
            .pointer:hover{
                background-image: linear-gradient(to left,rgba(130,120,120,.5),rgba(30,20,20,.0));
                box-shadow: 1px 2px 6px black;
            }
            img.tamanho{ height:30px; width: auto; }
            img.tamanho2{
                margin: auto;
                margin-left: 1px;
                margin-right: 1px;
                height:60px;
                width: 45px;
                transition: height 1s;
            }
            img.tamanho2-active{
                height:260px;
                width: auto;
                transition: height 1s;
            }
            .n-button{
                border: none;
                background: transparent;
                font-size: inherit;
                color: inherit;
                font-weight: inherit;
            }
            #divQuadro a:hover{ color: #888; }
            #headerQuadro{
                font-weight: 700;
                text-align: center;
            }
            #headerQuadro label{ cursor: pointer; }
            #headerQuadro i{
                vertical-align: -7px;
                cursor: pointer;
            }
            #headerQuadro div{
                padding: 5px 30px 0px 30px;
                border: 1px solid rgba(250,250,250,.6);
                border-radius: 10px;
                cursor: pointer;
                transition: background .6s;
            }
            #headerQuadro div:hover{
                background-image: linear-gradient(to left,transparent,rgba(30,20,20,.6));
                box-shadow: 1px 1px 10px black;
            }
            #divQuadro img{ box-shadow: 5px 1px 20px #111; }
            #divQuadro img:hover{ box-shadow: 11px 11px 10px #111; }
            #v-pills-tab{ max-height: 300px; overflow: auto; }
            #v-pills-tab a{ width: 100%; }
            .divActive{
                background: rgba(250,250,250,.9);
                color: black;
            }
            .box-shadow{ box-shadow: 1px 1px 5px black; transition: box-shadow 1s;}
            th.box-shadow:hover{ box-shadow: 10px 10px 15px black }
            .box-shadow-light{ box-shadow: 1px 1px 35px black }
            .text-shadow{ text-shadow: 1px 1px 35px black }
            .cursor-p{ cursor: pointer; }
            .list-p:hover{
                background-image: linear-gradient(to left,rgba(130,120,120,.5),rgba(30,20,20,.0));
                box-shadow: 4px 5px 7px black;
            }
            .f-negrito{ font-weight: 650; }
        </style>
        <!--ProgressBar-Horizontal-->
        <style>
            .progress-horizontal {
            vertical-align: baseline;
            display: -ms-flexbox;
            display: flex;
            height: 1rem;
            overflow: hidden;
            font-size: 0.75rem;
            background-color: #e9ecef;
            border-radius: 0.25rem;
            margin-top: -10px;
            }
            .progress-bar-horizontal {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            -ms-flex-pack: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            background-color: #007bff;
            transition: width 0.6s ease;
            width: 1%;
            }
        </style>
        <!--Scroll Personalizado-->
        <style>
            ::-webkit-scrollbar { width: 6px; } /* width */
            ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 5px; } /* Track */
            ::-webkit-scrollbar-thumb { background: #888; border-radius: 5px; } /* Handle */
            ::-webkit-scrollbar-thumb:hover { background: #555; } /* Handle on hover */
        </style>
        <script src="../js/jquery/jquery-3.4.1.min.js"></script>
        <script src="../progress/altNivel.js"></script>
        <!--Variáveis-->
        <script>
            var inputF = "";
            var preview = "";
            var quadro = "";
            var localImg = "../img/";
            var reDimens = false;
            var tblLivro = [];
            var editoraDistinct = [];
            var escritorDistinct = [];
            var sagaDistinct = [];
            var sessao = [];
            <?php
                $sql = "select * from detalheLivros where Usuario='{$_SESSION['user_id']}' order by livro_id;";
                $data = enviarComand($sql,'bd_lworld');
                $sql = "select * from tbleditora where editora_user_id='{$_SESSION['user_id']}';";
                $data1 = enviarComand($sql,'bd_lworld');
                $sql = "select * from tblescritor where escritor_user_id='{$_SESSION['user_id']}';";
                $data2 = enviarComand($sql,'bd_lworld');
                $sql = "select * from tblsaga where saga_user_id='{$_SESSION['user_id']}';";
                $data3 = enviarComand($sql,'bd_lworld');
                $entrou = 0;
                //tblLivro
                while($resultado = $data->fetch_array()){
            ?>
            tblLivro[<?php echo $entrou; ?>] = {
                id: "<?php echo $resultado['livro_id']; ?>",
                livro: "<?php echo $resultado['Livro']; ?>",
                saga: "<?php echo $resultado['Saga']; ?>",
                escritor: "<?php echo $resultado['Escritor']; ?>",
                editora: "<?php echo $resultado['Editora']; ?>",
                status: "<?php echo $resultado['Status']; ?>",
                comprado: "<?php echo $resultado['Comprado']; ?>",
                paginas: "<?php echo $resultado['Paginas_Lidas']; ?>",
                img: "<?php echo nameImage($resultado['Img']); ?>"
            };
            <?php $entrou++; } $entrou=0; while($resultado = $data1->fetch_array()){ ?>
            editoraDistinct[<?php echo $entrou; ?>] = "<?php echo $resultado['editora_nome']; ?>";
            <?php $entrou++; } $entrou=0; while($resultado = $data2->fetch_array()){ ?>
            escritorDistinct[<?php echo $entrou; ?>] = "<?php echo $resultado['escritor_nome']; ?>";
            <?php $entrou++; } $entrou=0; while($resultado = $data3->fetch_array()){ ?>
            sagaDistinct[<?php echo $entrou; ?>] = "<?php echo $resultado['saga_nome']; ?>";
            <?php $entrou++; } ?>
            sessao['cadNomeLivro'] = '<?php echo isset($_SESSION['cadNomeLivro'])?$_SESSION['cadNomeLivro']:""; ?>';
            sessao['cadNomeSaga'] = '<?php echo isset($_SESSION['cadNomeSaga'])?$_SESSION['cadNomeSaga']:""; ?>';
            sessao['cadNomeEscritor'] = '<?php echo isset($_SESSION['cadNomeEscritor'])?$_SESSION['cadNomeEscritor']:""; ?>';
            sessao['cadNomeEditora'] = '<?php echo isset($_SESSION['cadNomeEditora'])?$_SESSION['cadNomeEditora']:""; ?>';
        </script>
        <!--Functions-->
        <script>
            function trataErro(p){
                switch(p){
                    case 0:                                                           break;
                    case 1: alert('A Imagem selecionada Excede o Tamanho Permitido'); break;
                    case 2: alert('Erro ao carregar a Imagem');                       break;
                }
            }
            function alterTela(p){
                retorno="";
                switch(p){
                    case 1: retorno = "../";         break;
                    case 2: retorno = "../secular/"; break;
                    case 3: retorno = "../biblico/"; break;
                    case 4: retorno = "../autoral/"; break;
                }
                window.location.href = retorno;
            }
            function altList(p,s){
                v="Estante de Livros";
                switch(p){
                    case 0: v="Estante de Livros";     break;
                    case 1: v="Compras!";              break;
                    case 2: v="Linha do Tempo";        break;
                    case 3: v=tblLivro[s]['saga'];     break;
                }
                v=v.length>15?v.substring(0,16)+"...":v;
                carregaLista(p,s);
                $('#listTitle').html(v);
            }
            function alterArticle(p){
                $('.showArt').hide();
                $('#'+p).toggle('slow');
                $('#'+p).addClass('showArt');
                $('#divSecular div').removeClass('divActive');
                $('#'+"i"+ucfirst(p)).closest( "div" ).addClass('divActive');
            }
            function recarrega(){ window.location.href = "index.php"; }
            function teste(){ alert("Teste"); }
            function retornaDescr(p){
                retorno="";
                switch(p){
                    case 0: retorno = "Lista de Livros para Ler...";        break;
                    case 1: retorno = "Lista de Livros para Comprar...";    break;
                    case 2: retorno = "Lista contendo todos os Livros...";  break;
                    case 3: retorno = "Lista de Livros desta Saga!";        break;
                }
                return retorno;
            }
            function condicaoList(p,i,s){
                retorno = false;
                switch(p){
                    case 0: retorno = tblLivro[i]['status']=='Lendo';           break;
                    case 1: retorno = tblLivro[i]['comprado']=="Não";           break;
                    case 2: retorno = true;                                     break;
                    case 3: retorno = tblLivro[i]['saga']==tblLivro[s]['saga']; break;
                    case 4: retorno = tblLivro[i]['status']=='Em Espera';       break;
                }
                return retorno;
            }
            function carregaSecular(i){
                if(i==-1){
                    $('#progressoDeLeitura div,#progressoDeLeitura a,#progressoDeLeitura b').hide();
                    $('#estanteVazia').show();
                }
                else{
                    $('#progressoDeLeitura div,#progressoDeLeitura a,#progressoDeLeitura b').show();
                    $('#estanteVazia').hide();
                    $('#idLivroSelected').val(tblLivro[i]['id']);
                    $('#secularProgressTitulo b').html(tblLivro[i]['livro']);
                    $('#secularDetalhes table tr:nth-child(1) td').html(tblLivro[i]['escritor']);
                    $('#secularDetalhes table tr:nth-child(2) td').html(tblLivro[i]['saga']);
                    $('#secularDetalhes table tr:nth-child(3) td').html(tblLivro[i]['status']);
                    $('#secularDetalhes table tr:nth-child(4) td').html(tblLivro[i]['paginas']);
                    $('#secularDetalhes table tr:nth-child(5) td').html(tblLivro[i]['comprado']=='Sim'?'Comprado':'Na Lista de Compras');
                    $('#secularDetalhes img').attr('src',tblLivro[i]['img']);
                    r = Math.round(mediaPorc(i));
                    altNivel(r,4);
                }
            }
            function carregaTbl(){
                conteudo = "";
                for(i=0;i<(sagaDistinct.length);i++){
                    conteudo += "<a class='dropdown-item' href='#' onclick='filtroTbl(3,\""+sagaDistinct[i]+"\")'>"+sagaDistinct[i]+"</a> ";
                }
                $('#ddmBtnSagaDiv').append(conteudo);
                conteudo = "";
                for(i=0;i<(escritorDistinct.length);i++){
                    conteudo += "<a class='dropdown-item' href='#' onclick='filtroTbl(4,\""+escritorDistinct[i]+"\")'>"+escritorDistinct[i]+"</a> ";
                }
                $('#ddmBtnEscritorDiv').append(conteudo);
                
                conteudo = "";

                if(tblLivro.length==0){
                    $('#divModalPesquisaLivro').hide();
                    conteudo="<tr><th colspan='5' class='text-center'>Não há Livros Cadastrados</th></tr>";
                }
                else for(i=0;i<(tblLivro.length);i++){
                    imagem = "<img class='card-img-top tamanho' src='"+tblLivro[i]['img']+"'> ";
                    conteudo += "<tr class='cursor-p list-p' onclick='altList(3,\""+i+"\");alterArticle(\"secAtual\");'><th scope='row'>"+(i+1)+"</th><td>"+imagem+tblLivro[i]['livro']+"</td><td>"+tblLivro[i]['saga']+"</td><td>"+tblLivro[i]['escritor']+"</td><td>"+adesivoTbl(i)+"</td></tr>";
                }
                $('#secList table tbody').append(conteudo);
            }
            function carregaLista(p,s){
                conteudo1 = "";
                conteudo2 = "";
                valor = -1;
                if(p==3&&s>-1) valor = s;
                for(i=0;i<(tblLivro.length);i++){
                    if(condicaoList(p,i,s)){
                        nomeDoLivro=tblLivro[i]['livro'].length>26?tblLivro[i]['livro'].substring(0,24)+"...":tblLivro[i]['livro'];
                        conteudo1 += "<a class='nav-link' id='v-pills-"+i+"-tab' data-toggle='pill' href='#v-pills-"+i+"' role='tab' aria-controls='v-pills-"+i+"' aria-selected='true' onclick='carregaSecular("+i+")'>"+nomeDoLivro+adesivoTbl(i)+"</a>";
                        conteudo2 += "<div class='tab-pane fade' id='v-pills-"+i+"' role='tabpanel' aria-labelledby='v-pills-"+i+"-tab'>"+retornaDescr(p)+"</div>";
                        valor = valor==-1?i:valor;
                    }
                }
                if(p==0){
                    p=4;
                    for(i=0;i<(tblLivro.length);i++){
                        if(condicaoList(p,i,s)){
                            nomeDoLivro=tblLivro[i]['livro'].length>26?tblLivro[i]['livro'].substring(0,24)+"...":tblLivro[i]['livro'];
                            conteudo1 += "<a class='nav-link' id='v-pills-"+i+"-tab' data-toggle='pill' href='#v-pills-"+i+"' role='tab' aria-controls='v-pills-"+i+"' aria-selected='true' onclick='carregaSecular("+i+")'>"+nomeDoLivro+adesivoTbl(i)+"</a>";
                            conteudo2 += "<div class='tab-pane fade' id='v-pills-"+i+"' role='tabpanel' aria-labelledby='v-pills-"+i+"-tab'>"+retornaDescr(p)+"</div>";
                            valor = valor==-1?i:valor;
                        }
                    }   
                }
                carregaSecular(valor);
                $('#v-pills-tab').empty();
                $('#v-pills-tabContent').empty();
                $('#v-pills-tab').append(conteudo1);
                if(s>-1) $('#v-pills-'+s+'-tab').addClass("active");
                else $('#v-pills-tab a:first').addClass("active");
                $('#v-pills-tabContent').append(conteudo2);
                $('#v-pills-tabContent div:first').addClass("show active");
            }
            function carregaDtList(){
                lista = "<datalist id='dlLivros'>";            
                for(i=0;i<(tblLivro.length);i++){ lista += " <option value='"+tblLivro[i]['livro']+"'> "; }
                lista += "</datalist>";
                $('#menuLateral').append(lista);
            }
            function carregaQuadro(p){
                $('#divQuadro div').remove();
                $('#h5saga').remove();
                if(p=="Saga"){
                    cqSaga();
                    quadro="Saga";	
                    $( "i#i_view_list" ).closest( "div" ).addClass('divActive');
                    $( "i#i_list" ).closest( "div" ).removeClass('divActive');
                }
                else if(p=="Livro"){
                    cqLivro();
                    quadro="Livro";
                    $( "i#i_list" ).closest( "div" ).addClass('divActive');
                    $( "i#i_view_list" ).closest( "div" ).removeClass('divActive');
                }
            }
            function carregaQuadroFiltro(p){
                conteudo="";
                $( "i#i_view_list" ).closest( "div" ).removeClass('divActive');
                $('#divQuadro div').remove();
                cont=0;
                for(i=0;i<tblLivro.length;i++){
                    if(tblLivro[i]['saga']==p){
                        nomeLivro = tblLivro[i]['livro'].length>19?tblLivro[i]['livro'].substr(0,18)+"...":tblLivro[i]['livro'];
                        nomeSaga = tblLivro[i]['saga'].length>19?tblLivro[i]['saga'].substr(0,18)+"...":tblLivro[i]['saga'];
                        conteudo += "<div class='col-lg-3 col-md-6' title='"+tblLivro[i]['livro']+" - "+tblLivro[i]['saga']+"'><a href='#' class='d-block mb-4 h-100' onclick='altList(3,\""+i+"\");alterArticle(\"secAtual\");'><img class='img-fluid img-thumbnail' src='"+tblLivro[i]['img']+"' alt=''><div class='card-body text-center pt-3'><h5 class='card-title mb-0 text-light'>"+nomeLivro+"</h5><div class='card-text text-muted'>"+nomeSaga+"</div></div></a></div>";
                        cont++;
                    }
                }
                complemento="<h5 class='d-block mt-2' id='h5saga'>"+p+" - "+cont+" Livros</h5>";
                $('#headerQuadro').append(complemento);
                $('#divQuadro').append(conteudo);
            }
            function carregaNovoLivro(p){
                switch(p){
                    case '1':
                        $('#part1').removeClass('d-none');
                        $('.progress-bar-horizontal').css('width','25%');
                        $('.progress-bar-horizontal').attr('aria-valuenow','25');    
                        $('#addLivroCancel').addClass('d-none');
                        $('#addLivroProx').val('1');
                        conteudo = "";
                        if(sagaDistinct.length==0){
                            outraSagaEd('1'); $('#addNomeSagaInput').focus();
                        }else for(i=0;i<(sagaDistinct.length);i++){ conteudo += "<option>"+sagaDistinct[i]+"</option> "; }
                        $('#addNomeSaga').append(conteudo);
                        conteudo = "";
                        if(escritorDistinct.length==0){
                            outraSagaEd('2'); $('#addNomeEscritorInput').focus();
                        }else for(i=0;i<(escritorDistinct.length);i++){ conteudo += "<option>"+escritorDistinct[i]+"</option> "; }
                        $('#addNomeEscritor').append(conteudo);
                        conteudo = "";
                        if(editoraDistinct.length==0){
                            outraSagaEd('3'); $('#addNomeEditoraInput').focus();
                        }else for(i=0;i<(editoraDistinct.length);i++){ conteudo += "<option>"+editoraDistinct[i]+"</option> "; }
                        $('#addNomeEditora').append(conteudo);
                        break;
                    case '2':
                        $('#part2').removeClass('d-none');
                        $('.progress-bar-horizontal').css('width','50%');
                        $('.progress-bar-horizontal').attr('aria-valuenow','50');
                        $('#addLivroCancel').html('Anterior');
                        $('#addLivroCancel').attr('href','index.php?nLivro=1');
                        $('#part2 div h5').html(sessao['cadNomeLivro']);
                        $('#part2 div h6').html(sessao['cadNomeSaga']+" - "+sessao['cadNomeEditora']);
                        $('#addNomeLivro').val(sessao['cadNomeLivro']);
                        $('#addNomeSaga').val(sessao['cadNomeSaga']);
                        $('#addNomeEscritor').val(sessao['cadNomeEscritor']);
                        $('#addNomeEditora').val(sessao['cadNomeEditora']);
                        $('#addLivroProx').val('2');
                        break;
                    case '3':
                        $('#part3').removeClass('d-none');
                        $('.progress-bar-horizontal').css('width','75%');
                        $('.progress-bar-horizontal').attr('aria-valuenow','75');    
                        $('#addLivroCancel').html('Anterior');
                        $('#addLivroCancel').attr('href','index.php?nLivro=2');
                        $('#part3 div h5').html(sessao['cadNomeLivro']);
                        $('#part3 div h6').html(sessao['cadNomeSaga']+" - "+sessao['cadNomeEditora']);
                        $('#addNomeLivro').val(sessao['cadNomeLivro']);
                        $('#addNomeSaga').val(sessao['cadNomeSaga']);
                        $('#addNomeEscritor').val(sessao['cadNomeEscritor']);
                        $('#addNomeEditora').val(sessao['cadNomeEditora']);
                        $('#addLivroProx').val('3');
                        break;
                    case '4':
                        $('#part4').removeClass('d-none');
                        $('.progress-bar-horizontal').css('width','100%');
                        $('.progress-bar-horizontal').attr('aria-valuenow','100');
                        $('#addLivroCancel').addClass('d-none');
                        $('#addLivroProx').addClass('d-none');
                        document.getElementById('inputPesqLivro').value=sessao['cadNomeLivro'];
                        break;
                }
            }
            function updateImageDisplay(){
                curFiles = inputF.files;
                if(curFiles.length!=0){
                    $('.preview p').html(curFiles[0].name);
                    $('.preview img').remove();
                    image = document.createElement('img');
                    image.src = window.URL.createObjectURL(curFiles[0]);
                    $('.preview').prepend(image);
                    $('.preview img').addClass('rounded mx-auto d-block img-thumbnail box-shadow pb-1');
                    $('.preview img').css('height','200');
                    
                }
                
            }
            function carregaPart3(p){
                retorno = "";
                switch(p){
                    case 'inlineRadio1':
                        if(!hasNone('#part3file'))    $('#part3file').addClass('d-none');
                        if(!hasNone('#part3galeria')) $('#part3galeria').addClass('d-none');
                        if(hasNone('#part3figure'))   $('#part3figure').removeClass('d-none');
                        $('#part3img').attr('src','../img/padrao.jpg');
                        break;
                    case 'inlineRadio2':
                        if(!hasNone('#part3file'))    $('#part3file').addClass('d-none');
                        if(!hasNone('#part3galeria')) $('#part3galeria').addClass('d-none');
                        if(hasNone('#part3figure'))   $('#part3figure').removeClass('d-none');
                        $('#part3img').attr('src','../img/capa.jpg');
                        break;
                    case 'inlineRadio3':
                        if(!hasNone('#part3figure')) $('#part3figure').addClass('d-none');
                        if(!hasNone('#part3file'))   $('#part3file').addClass('d-none');
                        if(hasNone('#part3galeria')) $('#part3galeria').removeClass('d-none');
                        break;
                    case 'inlineRadio4':
                        if(!hasNone('#part3figure')) $('#part3figure').addClass('d-none');
                        if(!hasNone('#part3galeria'))$('#part3galeria').addClass('d-none');
                        if(hasNone('#part3file'))    $('#part3file').removeClass('d-none');
                        break;
                }
            }
            function hasNone(p){ return $(p).hasClass('d-none'); }
            function cqSaga(){
                conteudo="";
                if(sagaDistinct.length==0) conteudo="<h3 class='text-center w-100'>Não há Sagas Cadastradas</h3>";
                else for(i=0;i<(sagaDistinct.length);i++){
                    indexSaga = imgSaga(sagaDistinct[i]);
                    imgDaSaga=indexSaga>-1?tblLivro[indexSaga]['img']:(localImg+"padrao.jpg");
                    nomeSaga = sagaDistinct[i].length>19?sagaDistinct[i].substr(0,18)+"...":sagaDistinct[i];
                    conteudo += "<div class='col-lg-3 col-md-6' title='"+sagaDistinct[i]+"'><a href='#' class='d-block mb-4 h-100' onclick='carregaQuadroFiltro(\""+sagaDistinct[i]+"\")'><img class='img-fluid img-thumbnail' src='"+imgDaSaga+"' alt=''><div class='card-body text-center pt-3'><h5 class='card-title mb-0 text-light'>"+nomeSaga+"</h5><div class='card-text text-muted'>...</div></div></a></div>";
                }
                $('#divQuadro').append(conteudo);
            }
            function cqLivro(){
                conteudo="";
                if(tblLivro.length==0) conteudo="<h3 class='text-center w-100'>Não há Livros Cadastradas</h3>";
                else for(i=0;i<(tblLivro.length);i++){
                    nomeLivro = tblLivro[i]['livro'].length>19?tblLivro[i]['livro'].substr(0,18)+"...":tblLivro[i]['livro'];
                    nomeSaga = tblLivro[i]['saga'].length>19?tblLivro[i]['saga'].substr(0,18)+"...":tblLivro[i]['saga'];
                    conteudo += "<div class='col-lg-3 col-md-4 col-6' title='"+tblLivro[i]['livro']+" - "+tblLivro[i]['saga']+"'><a href='#' class='d-block mb-4 h-100' onclick='altList(3,\""+i+"\");alterArticle(\"secAtual\");'><img class='img-fluid img-thumbnail' src='"+tblLivro[i]['img']+"' alt=''><div class='card-body text-center pt-3'><h5 class='card-title mb-0 text-light'>"+nomeLivro+"</h5><div class='card-text text-muted'>"+nomeSaga+"</div></div></a></div>";
                }
                $('#divQuadro').append(conteudo);
            }
            function adesivo(i){
                rTitle = "";rIcon = "";retorno= "";
                if(secLivro[i]['comprado']=="Na Lista de Compra"){
                    rTitle += "Comprar/";
                    rIcon += "new_releases ";
                }
                if(secLivro[i]['status']=="Lido"){
                    rTitle += "Lido/";
                    rIcon += "beenhere ";
                }
                if(secLivro[i]['status']=="Lendo"){
                    rTitle += "Lendo/";
                    rIcon += "import_contacts ";
                }
                if(rTitle.length!=0){
                    rTitle = rTitle.substr(0,(rTitle.length-1));
                    retorno = "<i class='material-icons adesivo' title='"+rTitle+"'>"+rIcon+"</i>";
                }
                return retorno;
            }
            function adesivoTbl(i){
                rTitle = "";rIcon = "";retorno= "";
                if(tblLivro[i]['comprado']=="Não"){
                    rTitle += "Comprar/";
                    rIcon += "new_releases ";
                }
                if(tblLivro[i]['status']=="Lido"){
                    rTitle += "Lido/";
                    rIcon += "beenhere ";
                }
                if(tblLivro[i]['status']=="Lendo"){
                    rTitle += "Lendo/";
                    rIcon += "import_contacts ";
                }
                if(rTitle.length!=0){
                    rTitle = rTitle.substr(0,(rTitle.length-1));
                    retorno = "<i class='material-icons adesivo' title='"+rTitle+"'>"+rIcon+"</i>";
                }
                return retorno;
            }
            function filtroTbl(col,valor){
                if(col==0){
                    $("#trFiltro").hide("slow");
                    $('#secList table tbody tr').show("slow");
                }else{
                    qtd=0;
                    for(i=1;i<=($('#secList table tbody tr').length);i++){
                        condicao=col==5?" i[title*='"+valor+"']":condicao=":contains('"+valor+"')";
                        if($("#secList table tbody tr:nth-child("+i+") td:nth-child("+col+")"+condicao).length){
                            $("#secList table tbody tr:nth-child("+i+")").show("slow");
                            qtd++;
                        }else{
                            $("#secList table tbody tr:nth-child("+i+")").hide();
                        }
                    }
                    $("#trFiltro").show("slow");
                    $("#trFiltro th span").html("Filtrado por '" + valor + "' <span style='font-weight: 300;font-size: 8.5pt;'>("+qtd+" resultados)</span>");
                }
            }
            function imgSaga(p){
                retorno=-1;
                for(cont=0;cont<tblLivro.length;cont++){
                    if(tblLivro[cont]['saga']==p){
                        retorno=cont;
                        cont=tblLivro.length;
                    }
                }
                
                return retorno;
            }
            function ucfirst(str) { return str.substr(0,1).toUpperCase()+str.substr(1); }
            function reloadModal(p){ window.location.href="index.php?nLivro="+p+""; }
            function ativaModal(p){
                carregaNovoLivro(p);
                document.getElementById("btnModalAddLivro").click();
            }
            function mediaPorc(p){
                if(tblLivro[p]['paginas'].indexOf("N")==(-1)){
                    indexPag = tblLivro[p]['paginas'].indexOf(" de ");
                    pag1 = tblLivro[p]['paginas'].substr(0,indexPag);
                    pag2 = tblLivro[p]['paginas'].substr((indexPag+4),tblLivro[p]['paginas'].length);
                    media = pag2==0?0:(pag1*100)/pag2;
                }else media = 0;
                return media;   
            }
            function outraSagaEd(p){
                switch(p){
                    case '1':   //Saga
                        $('#addNomeSaga').addClass('d-none');
                        $('#addNomeSagaInput').closest('div').removeClass('d-none');
                        $('#outrasSagas').addClass('d-none');
                        break;
                    case '2': //Escritor
                        $('#addNomeEscritor').addClass('d-none');
                        $('#addNomeEscritorInput').closest('div').removeClass('d-none');
                        $('#outrosEscritores').addClass('d-none');
                        break;
                    case '3': //Editora
                        $('#addNomeEditora').addClass('d-none');
                        $('#addNomeEditoraInput').closest('div').removeClass('d-none');
                        $('#outrasEditoras').addClass('d-none');
                        break;
                    case '4': //Saga
                        $('#addNomeSaga').removeClass('d-none');
                        $('#addNomeSagaInput').closest('div').addClass('d-none');
                        $('#addNomeSagaInput').val('');
                        $('#outrasSagas').removeClass('d-none');
                        break;
                    case '5': //Escritor
                        $('#addNomeEscritor').removeClass('d-none');
                        $('#addNomeEscritorInput').closest('div').addClass('d-none');
                        $('#addNomeEscritorInput').val('');
                        $('#outrosEscritores').removeClass('d-none');
                        break;
                    case '6': //Editora
                        $('#addNomeEditora').removeClass('d-none');
                        $('#addNomeEditoraInput').closest('div').addClass('d-none');
                        $('#addNomeEditoraInput').val('');
                        $('#outrasEditoras').removeClass('d-none');
                        break;
                }
            }
            function comprarLivro(){
                if($('#addCompraLivro').val()=="off"){
                    $('#addCompraLivro').val("on");
                    $('#addCompraLivro').closest('label').addClass('btn-danger');
                    $('#addCompraLivro').closest('label').removeClass('btn-dark');
                    $('#spanCompraLivro').html("Comprado!");
                }else if($('#addCompraLivro').val()=="on"){
                    $('#addCompraLivro').val("off");
                    $('#addCompraLivro').closest('label').addClass('btn-dark');
                    $('#addCompraLivro').closest('label').removeClass('btn-danger');
                    $('#spanCompraLivro').html("Na Lista de Compras");
                }
            }
            function redimens(){
                if($('#menuLateral').width()>140&&reDimens==true){
                    $('#menuLateral h1').html("Literary World");
                    $('#menuLateral li a').removeClass("text-center");
                    $('#menuLateral li a').removeClass("f-negrito");
                    $('#menuLateral li:nth-child(1) a').html("Dashboard");
                    $('#menuLateral li:nth-child(2) a').html("Secular");
                    $('#menuLateral li:nth-child(3) a').html("Bíblico");
                    $('#menuLateral li:nth-child(4) a').html("Autoral");
                    $('#menuLateral li:nth-child(5) a').html("Sair");
                    reDimens=false;
                }else if($('#menuLateral').width()<=140&&reDimens==false){
                    $('#menuLateral h1').html("LW");
                    $('#menuLateral li a').addClass("text-center");
                    $('#menuLateral li a').addClass("f-negrito");
                    $('#menuLateral li:nth-child(1) a').html("D");
                    $('#menuLateral li:nth-child(2) a').html("S");
                    $('#menuLateral li:nth-child(3) a').html("B");
                    $('#menuLateral li:nth-child(4) a').html("A");
                    $('#menuLateral li:nth-child(5) a').html("X");
                    reDimens=true;
                }
            }
            function localizarLivro(p,indice){
                retorno = -1;
                for(i=0;i<tblLivro.length;i++){
                    if(tblLivro[i][indice]==p){
                        retorno = i;
                        i = tblLivro.length;
                    }
                }
                return retorno;
            }
            function pesquisarLivro(novo){
                l = document.getElementById('inputPesqLivro').value;
                v = localizarLivro(l,'livro');
                if(v==-1){
                    $('#spanPesquisaLivro').removeClass("d-none");
                    $('#spanPesquisaLivro').html("O livro '"+l+"' não foi Localizado!");
                }else{
                    $('#btnPesquisaX').click();
                    $('#spanPesquisaX').click();
                    altList(3,v);
                    alterArticle('secAtual');
                }
                if(novo){ $('#closeAddLivro').click(); $('#closeAddLivro span').click(); }
            }
            function tamanho2active(e){
                $('.tamanho2-active').removeClass('tamanho2-active');
                if(e!='x'){
                    $('#'+e).addClass('tamanho2-active');
                    document.getElementById('addImgGaleria').value = ($('#'+e).attr('src')).substr(7);
                    $('#part3galeria h1 b').html('1 Livro Selecionado');
                    $('#part3galeria h1').removeClass('btn-light');
                    $('#part3galeria h1').addClass('btn-success');
                }
                else{
                    $('#part3galeria h1 b').html('Selecione um Livro');
                    document.getElementById('addImgGaleria').value = "";
                    $('#part3galeria h1').addClass('btn-light');
                    $('#part3galeria h1').removeClass('btn-success');
                }
            }
            function maxSize(){
                if($('#addImgLivro')[0].files[0].size>1000000){
                    msg(0,['O Tamanho da Imagem excede o limite permitido. Redimensione-a para um tamanho menor que 1000kb']);
                }
            }
            function msg(indice,arr){ $('#msgTexto').html(arr[indice]); $('#btnChamaMsg').click(); }
            function finalizaForm(p){
                $('#formMaisPag').attr('action','../back/cadastro.php?'+p);
                $('#formMaisPag').submit();
            }
            function config(p){ location.href= '../index.php?config='+p; }
            function minMaxPag(p){
                $('#tdQtdPag').html(p);
                parr = p.split(' de ');
                $('#inputQtdPag').attr('value',parr[0]).attr('max',parr[1]);
            }
            window.onload = function(){
                $('[name=inlineRadioOptions]').click(function(e){ carregaPart3(this.id); });
                carregaLista(0,-1);
                carregaTbl();
                carregaQuadro('Saga');
                alterArticle('secAtual');
                carregaDtList();
                <?php if(isset($_GET['nLivro'])){ ?> ativaModal('<?php echo $_GET['nLivro'];?>'); <?php } ?>
                <?php if(isset($_GET['error'])){ ?> trataErro(<?php echo $_GET['error'];?>); <?php } ?>
                redimens();
                $(window).resize(function(e){ redimens(); });
                //Teste
                inputF = document.getElementById('addImgLivro');
                preview = document.querySelector('.preview');
                inputF.style.opacity = 0;
                inputF.addEventListener('change', updateImageDisplay);
                $('[for=addImgLivro]').addClass('btn btn-danger mt-2 mb-0 btn-block');
                <?php if(isset($_GET['llendo'])){?>
                altList(3,localizarLivro(<?php echo $_GET['llendo']; ?>,'id'));
                <?php }else ?>
                <?php if(isset($_GET['errlendo'])){?>
                msg(0,['Houve um Erro ao Registra a Leitura deste Livro']);
                altList(3,localizarLivro(<?php echo $_GET['errlendo']; ?>,'id'));
                <?php } ?>
                
            };
        </script>
    </head>
    <body>
        <div class="container-fluid bg-dark text-light">
        <div class="row">
            <!--Menu Lateral-->
            <div class="col-lg-2 col-md-1 col-sm-1 col-xs-1 div-master" id="menuLateral">
                <div>
                    <h1 style="cursor: pointer;" onclick="recarrega()">Literary World</h1><hr/>
                    <ul class="nav nav-pills flex-column">
                    <li class="nav-item" onclick="alterTela(1);">
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item" onclick="alterTela(2);">
                        <a class="nav-link active" href="#">Secular</a>
                    </li>
                    <li class="nav-item" onclick="alterTela(3);">
                        <a class="nav-link" href="#">Bíblico</a>
                    </li>
                    <li class="nav-item" onclick="alterTela(4);">
                        <a class="nav-link" href="#">Autoral</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="../sign-in/">Sair</a>
                    </li>
                    </ul>
                    <?php if(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0){ ?>
                        <a class="mb-3 text-light position-absolute nav-item d-flex w-100 px-3" style="justify-content: center; align-items: center; opacity: .9; bottom: 0; left: 0;" id="aMatthNavigate" onclick="$('#matthNavigate').modal('show');" href="#">
                            <span class="material-icons align-middle p-1 w-100 text-center border rounded">ac_unit</span>
                        </a>
                    <?php } ?>
                </div>
            </div>
            <!--Conteudo-->
            <div class="col-lg-10 col-md-11 col-sm-11 col-xs-11 bg-light div-master pt-1" id="conteudo">
            <!--Secular-->
            <section class="container-fluid rounded bg-dark">
                <header class="p-2 mb-2" id="hSecular">
                    <h1 class="d-inline">Secular</h1>
                    <div id="divSecular">
                        <div class="d-inline pointer" onclick="alterArticle('secAtual')">
                            <label for="iSecAtual">Atual</label>
                            <i class="material-icons" id="iSecAtual">font_download</i>
                        </div>
                        <div class="d-inline pointer" onclick="alterArticle('secList')">
                            <label for="iSecList">Lista</label>
                            <i class="material-icons" id="iSecList">line_weight</i>
                        </div>
                        <div class="d-inline pointer" onclick="alterArticle('secQuadro')">
                            <label for="iSecQuadro">Quadro</label>
                            <i class="material-icons" id="iSecQuadro">view_module</i>
                        </div>
                        <div class="d-inline pointer" onclick="reloadModal(1);">
                            <label for="iSecAdd">Adicionar</label>
                            <i class="material-icons" id="iSecAdd">add_circle_outline</i>
                        </div>
                    </div>
                    <div style="float: right;font-size: 25pt;cursor: pointer" data-toggle="modal" data-target="#modalPesquisaLivro" id="divModalPesquisaLivro"><i class="material-icons">pageview</i></div>
                </header>
                <article id="secAtual">
                    <div class="row ml-1 mr-1">
                    <!--Progresso e Detalhes-->
                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 mb-3" >
                        <div class="container-fluid card bg-light text-dark pt-3" id="progressoDeLeitura">
                            <div id='estanteVazia'><h2 class='text-center'>Não há Livros na Estante</h2><button class='btn btn-danger font-weight-bold mb-2 w-100' onclick='reloadModal(1);'>Adicionar</button></div>
                            <div style="display: block;margin-bottom: -18px;">
                                <i class="material-icons float-right cursor-p" onclick="config($('#idLivroSelected').val());">settings</i>
                            </div>
                            <div class="progress red">
                                <span class="progress-left"><span class="progress-bar" id="pblSecSecular"></span></span>
                                <span class="progress-right"><span class="progress-bar" id="pbrSecSecular"></span></span>
                                <div class="progress-value" id="objPorcBarSecSecular"></div>
                            </div>
                            <p class="text-center" id="secularProgressTitulo"><b></b></p>
                            <div class="media" id="secularDetalhes">
                                <table class="table text-right">
                                    <tr><th scope="row" class="text-left">Escritor(a)</th><td class="grad-bw"></td></tr>
                                    <tr><th scope="row" class="text-left">Saga/Série</th><td class="grad-bw"></td></tr>
                                    <tr><th scope="row" class="text-left">Status do Livro</th><td class="grad-bw"></td></tr>
                                    <tr><th scope="row" class="text-left">Páginas</th><td class="grad-bw"></td></tr>
                                    <tr><th scope="row" class="text-left">Status de Compra</th><td class="grad-bw"></td></tr>
                                    <input type="hidden" id="idLivroSelected">
                                </table>
                                <img src="" class="rounded" style="height: 250px;width: auto;"/>
                            </div>
                            <a href="#" class="text-center btn btn-danger mt-2 mb-3" data-toggle="modal" data-target="#modalMaisPag" onclick="$('#tblPag thead tr th').html($('#secularProgressTitulo b').html()); minMaxPag($('#secularDetalhes table tr:nth-child(4) td').html()); $('#idLivroLendo').val($('#idLivroSelected').val());"> + 1 Página </a>
                        </div>
                    </div>
                    <!--Estante-->
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="container-fluid card bg-light text-dark pt-3">
                            <!-- Default dropleft button -->
                            <div class="btn-group dropleft mr-3" style="position: absolute;right: 0px;">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                            <div class="dropdown-menu">
                                <button class="dropdown-item" type="button" onclick="altList(0,-1);">Estante de Livros</button>
                                <button class="dropdown-item" type="button" onclick="altList(1,-1);">Carrinho de Compras</button>
                                <button class="dropdown-item" type="button" onclick="altList(2,-1);">Linha do Tempo</button>
                            </div>
                            </div>
                            <h1 class="h3 pt-0" id="listTitle">Estante de Livros</h1>
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical"></div>
                            <div class="tab-content p-2 text-muted" id="v-pills-tabContent"></div>
                        </div>
                    </div>
                </div>
                </article>
                <article id="secList">
                    <div class="row ml-1 mr-1">
                        <div class="table-responsive">
                        <table class="table">
                            <thead style="background-image: linear-gradient(to left,rgba(250,250,250,.8),rgba(233,233,233,.8));color: #000">
                                <tr style="display:none;" id="trFiltro">
                                    <th colspan="5" class="table-dark text-center"><span></span><i class="material-icons adesivo pointer" onclick="filtroTbl(0,'')">close</i></th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th class="text-nowrap">Nome</th>
                                    <th class="text-nowrap">
                                        <div class="dropdown">
                                        <button class="n-button dropdown-toggle" type="button" id="ddmBtnSaga" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Saga</button>
                                        <div class="dropdown-menu" aria-labelledby="ddmBtnSaga" id="ddmBtnSagaDiv"></div>
                                        </div>
                                    </th>
                                    <th class="text-nowrap">
                                        <div class="dropdown">
                                        <button class="n-button dropdown-toggle" type="button" id="ddmBtnEscritor" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Escritor</button>
                                        <div class="dropdown-menu" aria-labelledby="ddmBtnEscritor" id="ddmBtnEscritorDiv"></div>
                                        </div>
                                    </th>
                                    <th class="text-right text-nowrap">
                                        <div class="dropdown">
                                        <button class="n-button dropdown-toggle" type="button" id="ddmBtnStatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Status</button>
                                        <div class="dropdown-menu" aria-labelledby="ddmBtnStatus">
                                            <a class="dropdown-item" href="#" onclick="filtroTbl(5,'Lido')">Lido</a>
                                            <a class="dropdown-item" href="#" onclick="filtroTbl(5,'Lendo')">Lendo</a>
                                            <a class="dropdown-item" href="#" onclick="filtroTbl(5,'Comprar')">Comprar</a>
                                            <a class="dropdown-item" href="#" onclick="filtroTbl(5,'Comprar/Lido')">Comprar/Lido</a>
                                        </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </article>
                <article id="secQuadro">
                    <!-- Page Content -->
                    <div class="container-fluid">
                    <header id="headerQuadro">
                        <div class="d-inline-block" onclick="carregaQuadro('Saga');">
                            <label for="i_view_list">Sagas</label>
                            <i class="material-icons" id="i_view_list">view_list</i>
                        </div>
                        <div class="d-inline-block" onclick="carregaQuadro('Livro');">
                            <label for="i_list">Livros</label>
                            <i class="material-icons" id="i_list">list</i>
                        </div>
                    </header>
                    <hr class="mt-2 mb-3">
                    <div class="row text-center text-lg-left" id="divQuadro">
                        
                    </div>
                    </div>
                    <!-- /.container -->
                </article>
                <!-- Modal Adicionar Livro -->
                <button type="button" class="d-none" id="btnModalAddLivro" data-toggle="modal" data-target="#modalAddLivro"></button>
                <div class="modal fade" id="modalAddLivro" tabindex="-1" role="dialog" aria-labelledby="modalAddLivroLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header text-dark">
                        <h5 class="modal-title" id="modalAddLivroLabel">Novo Livro</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeAddLivro">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="../back/cadastro.php" enctype="multipart/form-data">
                        <div class="modal-body text-dark">
                            <!--Percentual-->
                            <div class="progress-horizontal">
                                <div class="progress-bar-horizontal" role="progressbar-horizontal" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-none" id="part1">
                                <div class="form-group mt-2 mb-1">
                                    <label for="addNomeLivro">Nome do Livro</label>
                                    <input type="text" class="form-control" name="addNomeLivro" id="addNomeLivro" placeholder="Digite o nome do Livro..." required>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="addNomeSaga">Nome da Saga</label>
                                    <select class="form-control" id="addNomeSaga" name="addNomeSaga" aria-describedby="outrasSagas"></select>
                                    <div class="input-group mb-2 d-none cursor-p">
                                        <input type="text" class="form-control" id="addNomeSagaInput" name="addNomeSagaInput" placeholder="Digite o nome da Saga...">
                                        <div class="input-group-append" onclick="outraSagaEd('4');">
                                            <span class="input-group-text" id="basic-addon1">&times</span>
                                        </div>
                                    </div>
                                    <small id="outrasSagas" class="form-text text-muted">Para adicionar uma nova Saga <a href="#" onclick="outraSagaEd('1'); $('#addNomeSagaInput').focus();">click aqui!</a></small>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="addNomeEscritor">Nome do Escritor</label>
                                    <select class="form-control" id="addNomeEscritor" name="addNomeEscritor" aria-describedby="outrosEscritores"></select>
                                    <div class="input-group mb-2 d-none cursor-p">
                                        <input type="text" class="form-control" id="addNomeEscritorInput" name="addNomeEscritorInput" placeholder="Digite o nome do Escritor...">   
                                        <div class="input-group-append" onclick="outraSagaEd('5');">
                                            <span class="input-group-text" id="basic-addon1">&times</span>
                                        </div>
                                    </div>
                                    <small id="outrosEscritores" class="form-text text-muted">Para adicionar uma nova Editora <a href="#" onclick="outraSagaEd('2'); $('#addNomeEscritorInput').focus();">click aqui!</a></small>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="addNomeEditora">Nome da Editora</label>
                                    <select class="form-control" id="addNomeEditora" name="addNomeEditora" aria-describedby="outrasEditoras"></select>
                                    <div class="input-group mb-2 d-none cursor-p">
                                        <input type="text" class="form-control" id="addNomeEditoraInput" name="addNomeEditoraInput" placeholder="Digite o nome da Editora...">   
                                        <div class="input-group-append" onclick="outraSagaEd('6');">
                                            <span class="input-group-text" id="basic-addon1">&times</span>
                                        </div>
                                    </div>
                                    <small id="outrasEditoras" class="form-text text-muted">Para adicionar uma nova Editora <a href="#" onclick="outraSagaEd('3'); $('#addNomeEditoraInput').focus();">click aqui!</a></small>
                                </div>
                            </div>
                            <div class="d-none" id="part2">
                                <div class="form-group mt-2 mb-1 p-1 text-center box-shadow rounded grad-bw">
                                    <h5 class="card-title mb-1">Nome do Livro...</h5>
                                    <h6 class="card-subtitle mb-1 text-muted">Nome da Saga - Nome da Editora</h6>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="addStatus">Status Literário</label>
                                    <select class="form-control" id="addStatus" name="addStatus">
                                    <option>Em Espera</option>
                                    <option>Lendo</option>
                                    <option>Lido</option>
                                    </select>
                                </div>
                                <div class="input-group mb-1">
                                    <div class="input-group-prepend">
                                    <div class="input-group-text" id="qtdPag">Pag.</div>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Quantidade de Páginas..." min="0" aria-label="Quantidade de Páginas" aria-describedby="qtdPag" id="addQtdPag" name="addQtdPag">
                                </div>
                                <div class="btn-group-toggle mt-2" data-toggle="buttons">
                                <label class="btn btn-dark btn-block" onclick="comprarLivro()">
                                    <input type="hidden" id="addCompraLivro" name="addCompraLivro" value="off">
                                    <span id="spanCompraLivro">Na Lista de Compras</span>
                                </label>
                                </div>
                            </div>
                            <div class="d-none" id="part3">
                                <div class="form-group mt-2 mb-1 p-1 text-center box-shadow rounded grad-bw">
                                    <h5 class="card-title mb-1">Livro</h5>
                                    <h6 class="card-subtitle mb-1 text-muted">Nome da Saga</h6>
                                </div>
                                <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" checked>
                                        <label class="form-check-label" for="inlineRadio1">Padrão</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="2">
                                        <label class="form-check-label" for="inlineRadio2">Personalizado</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="3">
                                        <label class="form-check-label" for="inlineRadio3">Galeria</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio4" value="4">
                                        <label class="form-check-label" for="inlineRadio4">Exterior</label>
                                    </div>
                                </div>
                                <div class="form-group mt-2 mb-1" id="part3figure">
                                    <figure>
                                        <img class="img-fluid img-thumbnail" src="../img/padrao.jpg" id="part3img" width="180">
                                    </figure>
                                </div>
                                <div class="form-group mt-2 mb-1 btn btn-dark d-none" id="part3galeria">
                                    <div class="row text-center p-1 m-auto">
                                        <h1 class="btn btn-sm btn-light btn-block" onclick="tamanho2active('x');"><b>Selecione um Livro</b></h1>
                                    <?php
                                        $path = nameImage('',true);
                                        if(!is_dir($path)) mkdir($path);
                                        $diretorio = dir($path);
                                        $entra=0;
                                        while($arquivo = $diretorio -> read()){ if($arquivo!='.'&&$arquivo!='..'){
                                    ?>
                                        <img class='img-fluid tamanho2' id='tamanho2<?php echo $entra; ?>' onclick="tamanho2active('tamanho2<?php echo $entra; ?>');" src='<?php echo $path.$arquivo; ?>'>  
                                    <?php } $entra++; } ?>
                                    </div>
                                    <input type="hidden" name="addImgGaleria" id="addImgGaleria" placeholder="Livro Selecionado..." required/>
                                </div>
                                <div class="form-group mt-2 mb-1 d-none" id="part3file">
                                    <div>
                                        <label for="addImgLivro">Adicionar Imagem:</label>
                                        <input type="file" accept="image/*" class="form-control-file" id="addImgLivro" name="addImgLivro" onchange="maxSize();">
                                    </div>
                                    <div class="preview pt-0" style="margin-top:-18px;">
                                        <p class="text-center text-muted">Nenhuma Imagem Selecionada</p> 
                                    </div>
                                </div>
                            </div>
                            <div class="d-none" id="part4">
                                <div class="mt-3" role="group" aria-label="Basic example">
                                    <a href="index.php?nLivro=1" class="btn btn-block box-shadow">Novo Livro</a>
                                    <a href="#" class="btn btn-dark btn-block box-shadow" onclick="pesquisarLivro(true)">Concluir</a>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group">
                                <a href="#" class="btn" style="border: 1px solid #ddd" id="addLivroCancel">Cancelar</a>
                                <button type="submit" class="btn btn-primary" id="addLivroProx" name="addLivroProx">Próximo</button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
                </div>
                <!-- Modal Pesquisa Livro -->
                <div class="modal fade" id="modalPesquisaLivro" tabindex="-1" role="dialog" aria-labelledby="inputPesqLivro" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content bg-dark">
                    <div class="modal-body bg-dark pt-2 rounded">
                        <button type="button" class="close float-right text-muted mt-2" id="btnPesquisaX" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" id="spanPesquisaX">&times;</span>
                        </button>
                        <h2 class="pt-0 mt-0 pb-1" id="modalPesquisaLivroLabel">Pesquisar</h2>
                        <div class="input-group">
                        <input type="text" class="form-control" placeholder="Digite o nome do Livro..." id="inputPesqLivro" name="inputPesqLivro" list="dlLivros" autofocus>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="pesquisarLivro(false)">
                                <i class="material-icons adesivo" style="color: #aaa;">search</i>
                            </button>
                        </div>
                        </div>
                        <span class="text-muted pl-1 d-none" id="spanPesquisaLivro">Localizando o Livro...</span>
                    </div>
                    </div>
                </div>
                </div>
                <!-- Modal + 1 Página -->
                <div class="modal fade" id="modalMaisPag" tabindex="-1" role="dialog" aria-labelledby="modalMaisPagLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-body pt-2 rounded text-dark">
                        <button type="button" class="close float-right text-muted mt-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <h2 class="pt-0 mt-0 mb-0 pb-1 text-center text-shadow" id="modalMaisPagLabel">+ 1 Página</h2>
                        <hr/>
                        <table class="table text-center mb-1" id="tblPag">
                        <thead><tr><th scope="col">Nome do Livro</th></tr></thead>
                        <tbody>
                            <tr>
                            <td id="tdQtdPag">120 de 220</td>
                            </tr>
                        </tbody>
                        </table>
                        <form method="POST" id="formMaisPag">
                            <div class="input-group mb-2 rounded">
                                <input type="hidden" id="idLivroLendo" name="idLivroLendo"/>
                                <input type="number" id="inputQtdPag" name="inputQtdPag" class="form-control" value='0' min='0'/>
                                <div class="input-group-append">
                                <button type="button" class="btn btn-light border font-weight-bold" onclick="finalizaForm('pagina');">Página Atual</button>
                                </div>
                            </div>
                            <div class="input-group">
                                <button type="button" class="btn btn-dark btn-block mt-2" onclick="finalizaForm('lido');">
                                <b>- Marca como Lido! -</b></button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
                </div>
                <!-- Modal Msg -->
                <button type="button" class="d-none" data-toggle="modal" data-target="#modalMsg" id="btnChamaMsg"></button>
                <div class="modal fade" id="modalMsg" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content bg-dark">
                    <div class="modal-body bg-dark pt-2 rounded">
                        <button type="button" class="close float-right text-muted mt-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" id="spanPesquisaX">&times;</span>
                        </button>
                        <h2 class="pt-0 mt-0 pb-1">Literary World <span class="material-icons">textsms</span></h2>
                        <div class="text-light mt-2 border rounded px-2 pb-3 pt-0 text-center">
                            <span class="text-muted m-0 p-0 align-top">...</span>
                            <div id="msgTexto">
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </section>
            </div>
        </div>
        </div>
        <?php
            include('../../function/ctrlm.php');
            include('../../function/mnav.php');
            include('../../function/arty.php');
            include('../../function/wmatth.php');
        ?>
    </body>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
    <script type="text/javascript" src="../js/bootstrap.bundle.js"></script>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
    </html>