<?php
  session_start();
  include('../conn/function.php');
  if(!isset($_SESSION['user_id'])){
    $lworld_id = 2;
    if(!(isset($_SESSION['user_mtworld'])&&$_SESSION['user_mtworld']>0)){
        if(isset($_COOKIE['mtworldPass'])&&isset($_COOKIE['mtworldKey'])){
            $sql="select * from usuario where email='{$_COOKIE['mtworldPass']}' and senha='{$_COOKIE['mtworldKey']}';";
            if($linha = (enviarComand($sql,'bd_mtworld'))->fetch_assoc()){
              $_SESSION['user_mtworld'] = $linha['id'];
              $_SESSION['user_mtworld_nome'] = $linha['nome'];
              $_SESSION['user_mtworld_email'] = $linha['email'];
            }else header('Location: sign-in/'); 
        }else header('Location: sign-in/');
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
            else header("Location: sign-in/");
        }else header("Location: sign-in/");
        
    }
  }else{
    if(!isset($_SESSION['user_nome'])  || empty($_SESSION['user_nome']) ||
       !isset($_SESSION['user_email']) || empty($_SESSION['user_email'])){
        $sql = "select * from tbluser where user_id='{$_SESSION['user_id']}';";
        $res = (enviarComand($sql,'bd_lworld'))->fetch_assoc();
        if(isset($res['user_id'])){
            $_SESSION['user_nome']= $res['user_nome'];
            $_SESSION['user_email']= $res['user_email'];
        }
    }
  } 
  if(isset($_GET['mtworld'])) header('Location: sign-in/index.php?mtworld');
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Literary World</title>
    <meta charset="utf-8">
    <link rel="icon" href="../img/icones/blur_on.png" type="image/png"/>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	<link rel="stylesheet" type="text/css" href="progress/css-progress.css"/>
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
        #menuLateral{ background: url('wallpaper/neve.jpg'); background-size: 100%;  padding: 0px; }
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
        .pointer{  
            cursor: pointer;
            background-image: linear-gradient(to right,rgba(130,120,120,.5),rgba(30,20,20,.0));
        }
        .pointer:hover{
            background-image: linear-gradient(to left,rgba(130,120,120,.5),rgba(30,20,20,.0));
            box-shadow: 1px 2px 6px black;
        }
        img.tamanho{ height:30px; width: auto; }
        .n-button{
            border: none;
            background: transparent;
            font-size: inherit;
            color: inherit;
            font-weight: inherit;
        }
        .box-shadow{ box-shadow: 1px 1px 5px black }
        .cursor-p{ cursor: pointer; }
        .f-negrito{ font-weight: 650; }
        [onclick] { cursor: pointer; }
        #configuracao{ display: none; }
        #configuracao hgroup h2{
            margin: -10px 10px 10px 3px;
            font-size: 16pt;
            color: rgba(250,250,250,.9);
        }
        img.tamanho2{
            margin: auto;
            margin-left: 1px;
            margin-right: 1px;
            height:60px;
            width: 45px;
            transition: height 1s;
        }
    </style>
    <!--Scroll Personalizado-->
    <style>
        ::-webkit-scrollbar { width: 6px; } /* width */
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 5px; } /* Track */
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 5px; } /* Handle */
        ::-webkit-scrollbar-thumb:hover { background: #555; } /* Handle on hover */
    </style>
    <script src="js/jquery/jquery-3.4.1.min.js"></script>
    <script src="progress/altNivel.js"></script>
    <!--Iniciando Variáveis-->
    <script>
        <?php
            $sql = "select ";
            $sql .= "(select count(*) from tbllivro where livro_comprado=1 and livro_user_id='{$_SESSION['user_id']}') tenho,";
            $sql .= "(select count(*) from tbllivro where livro_status='Lido' and livro_user_id='{$_SESSION['user_id']}') lido,"; 
            $sql .= "(select count(*) from tbllivro where livro_status='Lendo' and livro_user_id='{$_SESSION['user_id']}') lendo,"; 
            $sql .= "(select count(*) from tbllivro where livro_status='Em Espera' and livro_comprado=1 and livro_user_id='{$_SESSION['user_id']}') espera,";
            $sql .= "(select count(*) from tbllivro where livro_comprado=0 and livro_status='Lido' and livro_user_id='{$_SESSION['user_id']}') compraL,";
            $sql .= "(select count(*) from tbllivro where livro_comprado=0 and livro_status!='Lido' and livro_user_id='{$_SESSION['user_id']}') compra;";
            $dataDash = enviarComand($sql,'bd_lworld');
            $resDash = $dataDash->fetch_assoc();
        ?>
        var reDimens = false;
        var dash = [];
        dash['sec'] = {
            tenho: '<?php echo $resDash['tenho']; ?>',
            lido: '<?php echo $resDash['lido']; ?>',
            lendo: '<?php echo $resDash['lendo']; ?>',
            espera: '<?php echo $resDash['espera']; ?>',
            compraL: '<?php echo $resDash['compraL']; ?>',
            compra: '<?php echo $resDash['compra']; ?>'
        };
        // dash['bib'] = { total: 66, lido: 17, espera: 49 };
        dash['bib'] = { total: 66, lido: 0, espera: 66 };
        // dash['aut'] = { pronto: 1, desenvolvendo: 1, projeto: 4 };
        dash['aut'] = { pronto: 0, desenvolvendo: 0, projeto: 1 };
    </script>
    <!--Functions-->
    <script>
        var topFormAlt = 0;
        function alterTela(p){
            retorno="";
            switch(p){
                case 1: retorno = "index.php"; break;
                case 2: retorno = "secular/"; break;
                case 3: retorno = "biblico/"; break;
                case 4: retorno = "autoral/"; break;
            }
            window.location.href = retorno;
        }
        function recarrega(){ window.location.href = "index.php"; }
        function teste(){ msg(0,["Testando Funcionalidade!"]); }
        function carregaDash(){
            //Data Tables
            $('#tblDashSec tr:nth-child(1) td').html(dash['sec']['tenho']);
            $('#tblDashSec tr:nth-child(2) td').html(dash['sec']['lido']);
            $('#tblDashSec tr:nth-child(3) td').html(dash['sec']['lendo']);
            $('#tblDashSec tr:nth-child(4) td').html(dash['sec']['espera']);
            $('#tblDashSec tr:nth-child(5) td').html("("+dash['sec']['compraL']+") +"+dash['sec']['compra']);
            $('#tblDashBib tr:nth-child(1) td').html(dash['bib']['total']);
            $('#tblDashBib tr:nth-child(2) td').html(dash['bib']['lido']);
            $('#tblDashBib tr:nth-child(3) td').html(dash['bib']['espera']);
            $('#tblDashAut tr:nth-child(1) td').html(dash['aut']['pronto']);
            $('#tblDashAut tr:nth-child(2) td').html(dash['aut']['desenvolvendo']);
            $('#tblDashAut tr:nth-child(3) td').html(dash['aut']['projeto']);
            //ProgressBar Cicle

            r = Math.round(((dash['sec']['lido']-dash['sec']['lendo']-dash['sec']['espera'])*100)
                /(dash['sec']['tenho']==0?1:dash['sec']['tenho']));
            
            altNivel(r,1);
            r = Math.round((dash['bib']['lido']*100)/66);altNivel(r,2);
            r = Math.round((dash['aut']['pronto']*100)/dash['aut']['projeto']);altNivel(r,3);
        }
        function ativaConfig(p){
            switch(p){
                case 'secular':
                    console.log(dash['sec']);
                    if(dash['sec']['lido']==0 &&
                       dash['sec']['lendo']==0&&
                       dash['sec']['espera']==0&&
                       dash['sec']['compra']==0){
                        msg(0,['Não há Livros Cadastrados.<br/>Vá até Secular e adicione um novo Livro! ']);
                    }
                    else{
                        $('#configuracao').show();
                        target_offset = $('#configuracao').offset();
                        target_top = target_offset.top;
                        $('html, body, section').animate({ scrollTop: (target_top-25) }, 1000);
                        $('#ulistBook li:first').click();
                        $('#ulistBook .badge').html($('#ulistBook li').length);
                    }
                    break;
                case 'biblico': msg(0,['Configuração de Gerenciamento Bíblico em Manutenção']); break;
                case 'autoral': msg(0,['Configuração de Gerenciamento Autoral em Manutenção']); break;
            }
        }
        function carregaEdit(arr){
            if(topFormAlt==0){
                target_offset = $('#formAltLivro').offset();
                topFormAlt = target_offset.top;
            }
            target_top = topFormAlt;
            $('html, body, section').animate({ scrollTop: (target_top) }, 1000);
            $('#imgImg').attr('src','img/'+arr['livro_img']).attr('title',arr['livro_img']); $('#altImg').val(arr['livro_img']);
            $('#altNome').val(arr['livro_nome']); $('#altLivroId').val(arr['livro_id']);
            $('#altSaga').val(arr['saga_id']);
            $('#altEscritor').val(arr['escritor_id']);
            $('#altEditora').val(arr['editora_id']);
            $('#altPag').val(arr['livro_qtdPag']); $('#altPagAtual').val(arr['livro_pagAtual']);
            $('#btnStatus').html(arr['livro_status']); $('#altStatus').val(arr['livro_status']);
            $('#btnComprado').html(arr['livro_comprado']==1?'Comprado':'À Comprar'); $('#altComprado').val(arr['livro_comprado']);
        }
        function ucfirst(str) { return str.substr(0,1).toUpperCase()+str.substr(1); }
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
        function enviaForm(p,c){
            $(p).attr('action',c);
            $(p).submit();
        }
        function maxSize(){
            if($('#addImgLivro')[0].files[0].size>1000000){
                msg(0,['O Tamanho da Imagem excede o limite permitido. Redimensione-a para um tamanho menor que 1000kb']);
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
        function msg(indice,arr){ $('#msgTexto').html(arr[indice]); $('#btnChamaMsg').click(); }
        function msgConfirm(funct,param,txt){
            parametro = "";
            if(param.length>0){
                parametro = "'"+param[0]+"'";
                for(i=1;i<param.length;i++){
                    parametro+= ",'"+param[i]+"'";
                    
                }
            }
            funct = funct+"("+parametro+");";
            msg(0,[txt+"<br/><button type='button' class='btn btn-primary m-1' onclick=\""+funct+"\">Sim</button><button type='button' class='btn btn-danger m-1' data-dismiss='modal'>Não</button>"]);
        }
        function encontra(valor,indice){
            $retorno = -1;
            for(i=0;i<ulBook.length;i++) {
                if(ulBook[i][indice]==valor){
                    $retorno = i;
                    i = ulBook.length;
                }
            }
            return $retorno;
        }
        window.onload = function(){
            carregaDash();
            redimens();
            $(window).resize(function(e){ redimens(); });
            inputF = document.getElementById('addImgLivro');
            preview = document.querySelector('.preview');
            inputF.style.opacity = 0;
            inputF.addEventListener('change', updateImageDisplay);
            $('[for=addImgLivro]').addClass('btn btn-danger mt-2 mb-0 btn-block');
            <?php 
            if(isset($_GET['update'])){
                echo " msg(".$_GET['update'].",['Alteração realizada com Sucesso!','Houve um Erro ao realizar Alteração!']); ";
            }else
            if(isset($_GET['delete'])){
                echo " msg(".$_GET['delete'].",['Exclusão realizada com Sucesso!','Houve um Erro ao realizar a Exclusão!']); ";
            }else
            if(isset($_GET['config'])){
                echo " ativaConfig('secular'); ";
                echo " carregaEdit(ulBook[encontra({$_GET['config']},'livro_id')]); $('#ulistBook li').removeClass('active'); ";
            }
            if(isset($_GET['dev'])){
                echo " msg(0,['Função em Desenvolvimento']); ";
            }
            if(isset($_GET['vinculado'])&&$_GET['vinculado']<2){
                echo " msg({$_GET['vinculado']},['Houve um erro ao tentar vincular com MatthewsWorld!','Vinculado ao MatthewsWorld!']); ";
            }
            ?>
        };
    </script>
</head>
<body>
    <div class="container-fluid bg-dark text-light">
      <div class="row">
        <!--Menu Lateral-->
        <div class="col-lg-2 col-md-1 col-sm-1 col-xs-1 div-master position-relative" id="menuLateral">
            <div>
                <h1 style="cursor: pointer;" onclick="recarrega()">Literary World</h1><hr/>
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item" onclick="alterTela(1);"><a class="nav-link active" href="#" title="Dashboard">Dashboard</a></li>
                  <li class="nav-item" onclick="alterTela(2);"><a class="nav-link" href="#" title="Secular">Secular</a></li>
                  <li class="nav-item" onclick="alterTela(3);"><a class="nav-link" href="#" title="Bíblico">Bíblico</a></li>
                  <li class="nav-item" onclick="alterTela(4);"><a class="nav-link" href="#" title="Autoral">Autoral</a></li>                  
                  <li class="nav-item"><a class="nav-link disabled" href="sign-in/" title="Sair">Sair</a></li>
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
          <section class="container-fluid rounded bg-dark pb-2">
            <div class="rounded-circle bg-dark text-light border dropleft pointer" 
                style="position: absolute;top:25px;right:25px; width: 35px; height: 35px; display: flex; align-items: center;justify-content: center;font-family: serif; z-index: 100;">
                <div role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <b class=""><?php echo substr($_SESSION['user_nome'],0,1);?></b>
                    <div class="dropdown-menu text-center p-3" aria-labelledby="dropdownMenuLink">
                        <div>
                            <?php echo $_SESSION['user_nome']; ?>
                            <hr class="m-0 p-0"/>
                            <small class="text-muted"><?php echo $_SESSION['user_email']; ?></small>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm mt-2" onclick="location.href='sign-in/';">Sair</button>
                    </div>
                </div>
            </div>
            <header class="p-2 mb-2"><h1>Dashboard</h1></header>
            <div class="row">
                <!--Secular-->
                <div class="col-lg-4 col-md-12 pb-3">
                    <div class="container-fluid card text-dark pt-3">
                        <div style="display: block;margin-bottom: -18px;"><i class="material-icons float-right cursor-p" onclick="ativaConfig('secular');">settings</i></div>
                        <div class="progress red">
                            <span class="progress-left"><span class="progress-bar" id="pblSecular"></span></span>
                            <span class="progress-right"><span class="progress-bar" id="pbrSecular"></span></span>
                            <div class="progress-value" id="objPorcBarSecular"></div>
                        </div>
                        <p class="text-center"><b>Secular</b></p>
                        <table class="table text-center" id="tblDashSec">
                            <tr><th scope="row" class="text-left">Livros que eu Tenho</th><td></td></tr>
                            <tr><th scope="row" class="text-left">Livros Lidos</th><td></td></tr>
                            <tr><th scope="row" class="text-left">Livros que estou Lendo</th><td></td></tr>
                            <tr><th scope="row" class="text-left">Livros em Espera</th><td></td></tr>
                            <tr><th scope="row" class="text-left">Livros à Comprar (Lidos)</th><td></td></tr>
                        </table>
                        <a href="#" class="text-center btn btn-danger mb-3" onclick="alterTela(2);">+ Detalhes</a>
                    </div>
                </div>
                <!--Bíblico-->
                <div class="col-lg-4 col-md-12 pb-3">
                    <div class="container-fluid card text-dark pt-3">
                        <div style="display: block;margin-bottom: -18px;"><i class="material-icons float-right cursor-p" onclick="ativaConfig('biblico');">settings</i></div>
                        <div class="progress red">
                            <span class="progress-left"><span class="progress-bar" id="pblBiblico"></span></span>
                            <span class="progress-right"><span class="progress-bar" id="pbrBiblico"></span></span>
                            <div class="progress-value" id="objPorcBarBiblico"></div>
                        </div>
                        <p class="text-center"><b>Bíblico</b></p>
                        <table class="table text-center" id="tblDashBib">
                            <!-- <tr><th scope="row" class="text-left">Total de Livros</th><td></td></tr> -->
                            <!-- <tr><th scope="row" class="text-left">Livros Lidos</th><td></td></tr> -->
                            <!-- <tr><th scope="row" class="text-left">Livros em Espera</th><td></td></tr> -->
                            <tr><th scope="row" class="text-center">Em Desenvolvimento</th></tr>
                        </table>
                        <!-- <a href="#" class="text-center btn btn-danger mb-3" onclick="alterTela(3);">+ Detalhes</a> -->
                    </div>
                </div>
                <!--Autoral-->
                <div class="col-lg-4 col-md-12 pb-3">
                    <div class="container-fluid card text-dark pt-3">
                        <div style="display: block;margin-bottom: -18px;"><i class="material-icons float-right cursor-p" onclick="ativaConfig('autoral');">settings</i></div>
                        <div class="progress red">
                            <span class="progress-left"><span class="progress-bar" id="pblAutoral"></span></span>
                            <span class="progress-right"><span class="progress-bar" id="pbrAutoral"></span></span>
                            <div class="progress-value" id="objPorcBarAutoral"></div>
                        </div>
                        <p class="text-center"><b>Autoral</b></p>
                        <table class="table text-center" id="tblDashAut">
                            <!-- <tr><th scope="row" class="text-left">Livros Prontos</th><td></td></tr> -->
                            <!-- <tr><th scope="row" class="text-left">Em Desenvolvimento</th><td></td></tr> -->
                            <!-- <tr><th scope="row" class="text-left">Total Projetado</th><td></td></tr> -->
                            <tr><th scope="row" class="text-center">Em Desenvolvimento</th></tr>
                        </table>
                        <!-- <a href="#" class="text-center btn btn-danger mb-3" onclick="alterTela(4);">+ Detalhes</a> -->
                    </div>
                </div>
            </div>
            <!--Configurações-->
            <div class="row m-3 p-3 bg-warning rounded" id="configuracao">
                <hgroup><h1>Configuração</h1><h2>Secular</h2></hgroup>
                <!--Select Livro-->
                <div class="input-group mb-3">
                  <input type="text" class="form-control" id="selLivro" placeholder="Digite o nome do Livro..." name="selLivro" list="dlLivros">
                  <datalist id="dlLivros">
                      <?php
                        $sql = "select livro_id,livro_nome from tbllivro where livro_user_id='{$_SESSION['user_id']}';";
                        $dSel = enviarComand($sql,'bd_lworld');
                        while($rSel = $dSel->fetch_assoc()){
                      ?><option value="<?php echo $rSel['livro_nome']; ?>"><?php } ?>
                  </datalist>
                  <div class="input-group-append bg-light rounded-right">
                    <button class="btn btn-outline-dark" type="button" onclick="carregaEdit(ulBook[encontra($('#selLivro').val(),'livro_nome')]); $('#ulistBook li').removeClass('active');">Selecionar</button>
                  </div>
                </div>
                <!--Lista de Livros-->
                <ul class="list-group text-dark w-100" id="ulistBook">
                  <div class="card bg-dark text-light text-center">
                    <h3>Livros<span class="badge badge-danger ml-2" style="font-size: 10pt;"></span></h3>
                  </div>
                  <div  style="max-height: 345px; overflow: auto;">
                  <script> var ulBook = [];</script>
                <?php
                    $dataLi = enviarComand("select * from tbllivro inner join tblsaga on livro_saga_id=saga_id inner join tblescritor on livro_escritor_id=escritor_id inner join tbleditora on livro_editora_id=editora_id where livro_user_id='{$_SESSION['user_id']}';",'bd_lworld');
                    $entrou = 0;
                    while($resLi = $dataLi->fetch_assoc()){
                ?>
                  <li class="list-group-item" onclick="carregaEdit(ulBook[<?php echo $entrou; ?>]); $('#ulistBook li').removeClass('active'); $(this).addClass('active');">
                      <?php echo $resLi['livro_nome'];?>
                      <script>
                          ulBook[<?php echo $entrou; ?>] = {
                              livro_id:         '<?php echo $resLi['livro_id']; ?>',
                              livro_nome:       '<?php echo $resLi['livro_nome']; ?>',
                              livro_qtdPag:     '<?php echo $resLi['livro_qtdPag']; ?>',
                              livro_pagAtual:   '<?php echo $resLi['livro_pagAtual']; ?>',
                              livro_status:     '<?php echo $resLi['livro_status']; ?>',
                              livro_comprado:   '<?php echo $resLi['livro_comprado']; ?>',
                              livro_img:        '<?php echo $resLi['livro_img']; ?>',
                              saga_id:          '<?php echo $resLi['saga_id']; ?>',
                              saga_nome:        '<?php echo $resLi['saga_nome']; ?>',
                              escritor_id:      '<?php echo $resLi['escritor_id']; ?>',
                              escritor_nome:    '<?php echo $resLi['escritor_nome']; ?>',
                              editora_id:       '<?php echo $resLi['editora_id']; ?>',
                              editora_nome:     '<?php echo $resLi['editora_nome']; ?>'
                          }
                      </script>
                  </li>
                <?php $entrou++; } ?>
                  </div>
                </ul>
                <!--Formulário-->
                <div class="card p-3 pb-0 mt-3 text-dark w-100 grad-bw box-shadow">
                    <form class="m-auto" method="POST" id="formAltLivro">
                        <figure class="float-left"  data-toggle="modal" data-target="#modalImg">
                            <img class="rounded box-shadow" style="height: 365px;width: auto;" id="imgImg"/>
                            <input type="hidden" id="altImg" name="altImg"/>
                        </figure>
                        <div class="float-left mt-1 pl-3  pb-0">
                            <div class="card mb-2 bg-dark text-light text-center">
                                <div>
                                <span class="material-icons align-middle" title="Mais Informação" onclick="if($(this).html()=='loupe'){ $(this).html('remove_circle').attr('title','Menos Informação'); $('.collapse-subinfo').show('slow'); }else{ $(this).html('loupe').attr('title','Mais Informação'); $('.collapse-subinfo').hide('slow'); }">loupe</span>
                                <span class="material-icons align-middle" title="Salvar Alteração" onclick="enviaForm('#formAltLivro','back/cadastro.php?updateLivro');">autorenew</span>
                                <span class="material-icons align-middle" title="Excluir Livro" onclick="msgConfirm('enviaForm',['#formAltLivro','back/cadastro.php?deleteLivro'],'Confirmar Exclusão Definitiva do Livro?');">delete</span>
                                <span class="material-icons align-middle" title="Ver na Estante" onclick="location.href='secular/index.php?llendo='+$('#altLivroId').val();">library_books</span>
                                </div>
                            </div>
                            <div style="max-height: 325px; overflow: auto;">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="..." aria-label="Nome do Livro" id="altNome" name="altNome">
                                    <input type="hidden" id="altLivroId" name="altLivroId">
                                    <div class="input-group-append"><span class="input-group-text">Nome</span></div>
                                </div><!--Nome-->
                                <div class="input-group mb-3">
                                    <select class="custom-select" id="altSaga" name="altSaga">
                                        <?php
                                            $dSaga = enviarComand("select saga_id,saga_nome from tblsaga where saga_user_id='{$_SESSION['user_id']}';",'bd_lworld');
                                            while($rSaga = $dSaga->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo $rSaga['saga_id'] ?>"><?php echo $rSaga['saga_nome'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="input-group-append"><span class="input-group-text">Saga</span></div>
                                </div><!--Saga-->
                                <div class="input-group mb-3 collapse-subinfo" style="display: none;">
                                    <select class="custom-select" id="altEscritor" name="altEscritor">
                                        <?php
                                            $dEscritor = enviarComand("select escritor_id,escritor_nome from tblescritor where escritor_user_id='{$_SESSION['user_id']}';",'bd_lworld');
                                            while($rEscritor = $dEscritor->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo $rEscritor['escritor_id'] ?>"><?php echo $rEscritor['escritor_nome'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="input-group-append"><span class="input-group-text">Escritor</span></div>
                                </div><!--Escritor-->
                                <div class="input-group mb-3 collapse-subinfo" style="display: none;">
                                    <select class="custom-select" id="altEditora" name="altEditora">
                                        <?php
                                            $dEditora = enviarComand("select editora_id,editora_nome from tbleditora where editora_user_id='{$_SESSION['user_id']}';",'bd_lworld');
                                            while($rEditora = $dEditora->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo $rEditora['editora_id'] ?>"><?php echo $rEditora['editora_nome'] ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="input-group-append"><span class="input-group-text">Editora</span></div>
                                </div><!--Editora-->
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="..." aria-label="Quantidade de Páginas" id="altPag" name="altPag">
                                    <div class="input-group-append"><span class="input-group-text">Páginas</span></div>
                                </div><!--Páginas-->
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="..." aria-label="Página Atual" id="altPagAtual" name="altPagAtual">
                                    <div class="input-group-append"><span class="input-group-text">Pág. Atual</span></div>
                                </div><!--Páginas Atual-->
                                <!--Status-->
                                <button type="button" class="btn btn-primary btn-sm" id="btnStatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button><input type="hidden" id="altStatus" name="altStatus">
                                <div class="dropdown-menu" aria-labelledby="btnStatus">
                                    <a class="dropdown-item" onclick="$('#btnStatus').html($(this).html()); $('#altStatus').val($(this).html());">Em Espera</a>
                                    <a class="dropdown-item" onclick="$('#btnStatus').html($(this).html()); $('#altStatus').val($(this).html());">Lendo</a>
                                    <a class="dropdown-item" onclick="$('#btnStatus').html($(this).html()); $('#altStatus').val($(this).html());">Lido</a>
                                </div>
                                <!--Comprado-->
                                <button type="button" class="btn btn-secondary btn-sm" id="btnComprado" onclick="if($(this).html()=='Comprado'){$(this).html('À Comprar');$('#altComprado').val(0);}else{$(this).html('Comprado');$('#altComprado').val(1);}"></button><input type="hidden" id="altComprado" name="altComprado">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
          </section>
          <!-- Modal Img -->
          <div class="modal fade" id="modalImg" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content text-dark">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Selecione a Imagem</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 mb-1 btn btn-dark">
                            <div class="row text-center p-1 m-auto">
                                <h1 class="btn btn-sm btn-light btn-block"><b>Selecione um Livro</b></h1>
                                <?php
                                    $path = "img/".($_SESSION['user_id']+1000)."/";
                                    if(!is_dir($path)) mkdir($path);
                                    $diretorio = dir($path);
                                    while($arquivo = $diretorio -> read()){ if($arquivo!='.'&&$arquivo!='..'){   
                                ?>
                                <img class='img-fluid tamanho2' onclick="$('#imgImg').attr('src','<?php echo $path.$arquivo; ?>'); $('#altImg').val('<?php echo $arquivo; ?>');" src='<?php echo $path.$arquivo; ?>' data-dismiss="modal" title="<?php echo $arquivo; ?>">  
                                <?php } } ?>
                            </div>
                        </div>
                        <form method="POST" action="back/cadastro.php?uploadImg" enctype="multipart/form-data">
                            <div class="form-group mt-2 mb-1">
                                <div>
                                    <label for="addImgLivro">Adicionar Imagem:</label>
                                    <input type="file" accept="image/*" class="form-control-file" id="addImgLivro" name="addImgLivro" onchange="maxSize(); if($(this).val().length>0){ $('#addFinaliza').removeClass('d-none'); }else{ $('#addFinaliza').addClass('d-none'); }">
                                </div>
                                <div class="preview pt-0" style="margin-top:-18px;">
                                    <p class="text-center text-muted">Nenhuma Imagem Selecionada</p> 
                                </div>
                                <button type="submit" class="btn btn-primary btn-block d-none" id="addFinaliza">Finalizar</button>
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
        </div>
      </div>
    </div>
    <?php
        include('../function/ctrlm.php');
        include('../function/mnav.php');
        include('../function/arty.php');
        include('../function/wmatth.php');
    ?>
</body>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
</html>