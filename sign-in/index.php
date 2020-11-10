<!doctype html>
<?php session_start(); ?>
<html lang="en">
  <head>
    <title>Bem-Vindo ao Literary World</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="icon" href="../img/icones/blur_on.png" type="image/png"/>
    <style>
        body{ background: url('../fundos/c781c01bf263c86e8a8dcc8ccd34ac40.jpg'); }  
    </style>
    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <script src="../js/jquery/jquery-3.4.1.min.js"></script>
    <script>
      function msg(p){ $('#msgTexto').html(p); $('#btnChamaMsg').click(); }
      window.onload = function(){
        $('#modalCadastro').on('show.bs.modal',function(e){
          $('#form-login').hide('slow');
        });
        $('#modalCadastro').on('hide.bs.modal',function(e){ 
          $('#form-login').show('slow');
        });
        <?php 
        if(isset($_GET['error'])){
          switch($_GET['error']){
            case '1': echo " msg('Senha ou Email, inválido!'); "; break;
            case '2': echo " msg('Erro ao tentar cadastrar novo usuário!'); "; break;
          }
        }
        if(isset($_GET['success'])){
          echo " msg('Usuário cadastrado com sucesso!'); ";
        }
        ?>
      }
    </script>
  </head>
  <body class="text-center">
    <div class="mt-2 mr-3" style="position: absolute; color: #fff; top:0; right: 0; opacity: .8; font-family: serif;">
      <h3>Literary World</h3>
    </div>
    <form id="form-login"
      class="form-signin" method="POST" 
      action="../back/log.php<?php echo isset($_GET['mtworld'])?'?mtworld':''; ?>"
    >
      <?php if(isset($_GET['mtworld'])){ ?>
        <div class="bg-light border border-secondary px-1 pb-1 pt-0 mb-2 rounded d-flex justify-content-center text-dark flex-column" style="opacity:.6">
          <small class="border-bottom border-secondary mb-1" style="font-size: 7pt; opacity: .85;">Vincular com MatthewsWorld</small>
          <span class="material-icons">ac_unit</span>
        </div>
      <?php } ?>
      <h1 class="h3 mb-3 font-weight-normal text-light">Login</h1>
      <label for="inputEmail" class="sr-only">Email address</label>     
      <input type="email" id="inputEmail" name="inputEmail" class="form-control" placeholder="E-mail" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name="inputPassword" class="form-control" placeholder="Senha" required>
      <div class="checkbox mb-3 text-light">
        <a class="text-light" style="opacity: .6;" href="#" onclick="$('#modalCadastro').modal('show');">
          Não é Cadastrado?
        </a>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit" id="btnEntrar" name="btnEntrar">Entrar</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2020</p>
    </form>

    <!-- Modal Msg -->
    <button type="button" class="d-none" data-toggle="modal" data-target="#modalCadastro" id="btnChamaCadastro"></button>
    <div class="modal fade" data-backdrop id="modalCadastro" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg" style="background: transparent">
          <div class="modal-body pt-2 border border-secondary rounded" style="background: transparent">
            <button type="button" 
              class="close float-right mt-2" 
              data-dismiss="modal" aria-label="Close"
              >
              <span aria-hidden="true">&times;</span>
            </button>
            <form 
              class="form-signin" method="POST" 
              action="../back/log.php<?php echo isset($_GET['mtworld'])?'?mtworld':''; ?>"
            >
              <?php if(isset($_GET['mtworld'])){ ?>
              <div class="bg-light border border-secondary px-1 pb-1 pt-0 mb-2 rounded d-flex justify-content-center text-dark flex-column"
                style="opacity:.6">
                <small class="border-bottom border-secondary mb-1" style="font-size: 7pt; opacity: .85;">Vincular com MatthewsWorld</small>
                <span class="material-icons">ac_unit</span>
              </div>
              <?php } ?>
              <h1 class="h3 mb-3 font-weight-normal text-light">Cadastrar</h1>
              <input type="text" 
                id="cadNome" name="cadNome" placeholder="Nome" 
                class="form-control"
                style="margin-bottom: -1px; border-bottom-right-radius: 0; border-bottom-left-radius: 0;" 
                required
              >
              <input type="email" 
                id="cadEmail" name="cadEmail" placeholder="E-mail" 
                class="form-control rounded-0" 
                required
              >
              <input type="password" 
                id="cadPassword" name="cadPassword" placeholder="Senha"
                class="form-control rounded-0"  style="margin-bottom: -1px;"
                required
              >
              <input type="password" 
                id="confPassword" placeholder="Confirmar a Senha"
                class="form-control"
                required
              >
              <button type="submit" 
                id="btnCadastrar" name="btnCadastrar"
                class="btn btn-lg btn-primary btn-block"
              >Finalizar</button>
              <p class="mt-4 mb-1 text-muted">&copy; 2020</p>
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
              <h2 class="pt-0 mt-0 pb-1 text-light">Literary World <span class="material-icons">textsms</span></h2>
              <div class="text-light mt-2 border rounded px-2 pb-3 pt-0 text-center">
                  <span class="text-muted m-0 p-0 align-top">...</span>
                  <div id="msgTexto">
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>

  </body>
</html>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>