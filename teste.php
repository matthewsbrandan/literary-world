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
        /* Sticky Footer Classes */
        html,body { height: 100%; }
        #page-content { flex: 1 0 auto; }
        #sticky-footer { flex-shrink: none;}
        
        /* Other Classes for Page Styling */
        body { background: #007bff; background: linear-gradient(to right, #aa62a6, #33AEdd); }
        img.tamanho{ height:30px; width: auto; }
        .n-button{
            border: none;
            background: transparent;
            font-size: inherit;
            color: inherit;
            font-weight: inherit;
        }
    </style>
    <script src="js/jquery/jquery-3.4.1.min.js"></script>
    <script>
        var tblLivro = [];
    <?php
        include('../conn/function.php');
        $sql = "select livro_id,Livro,Saga,Escritor,Img from detalheLivros order by livro_id;";
        $data = enviarComand($sql,'bd_lworld');
        $entrou = 0;
        while($resultado = $data->fetch_array()){
    ?>
        tblLivro[<?php echo $entrou; ?>] = {
            id: "<?php echo $resultado['livro_id']; ?>",
            livro: "<?php echo $resultado['Livro']; ?>",
            saga: "<?php echo $resultado['Saga']; ?>",
            escritor: "<?php echo $resultado['Escritor']; ?>",
            img: "<?php echo $resultado['Img']; ?>"
        };
    <?php $entrou++; } ?>
        function carregaTbl(){
            conteudo = "";
            for(i=0;i<(tblLivro.length);i++){
                imagem = "<img class='card-img-top tamanho' src='img/"+tblLivro[i]['img']+"'> ";
                conteudo += "<tr><th scope='row'>"+(i+1)+"</th><td>"+imagem+tblLivro[i]['livro']+"</td><td>"+tblLivro[i]['saga']+"</td><td>"+tblLivro[i]['escritor']+"</td></tr>";
            }
            $('table tbody').append(conteudo);
        }
        window.onload = function(){
            carregaTbl();
        }
    </script>
</head>    
<body class="d-flex flex-column">
  <div id="page-content">
    <div class="container text-center">
      <div class="row justify-content-center">
        <div class="col-md-7">
          <h1 class="font-weight-light mt-4 text-white">Requisição de dados do MySql</h1>
          <p class="lead text-white-50">Aqui serão testadas inicialmente as requisições de dados no banco sendo inseridas atravéz do JavaScript</p>
        </div>
      </div>
    </div>
    <div class="container text-light mt-3">
        <table class="table">
          <thead class="bg-light text-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Livro</th>
              <th scope="col">
                  <div class="dropdown">
                      <button class="n-button dropdown-toggle" type="button" id="ddmBtnSaga" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Saga</button>
                      <div class="dropdown-menu" aria-labelledby="ddmBtnSaga"><a class="dropdown-item" href="#">Alguma ação</a></div>
                    </div>
                  </th>
              <th scope="col">Escritor</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
    </div>
  </div>
  <footer id="sticky-footer" class="py-4 bg-dark text-white-50">
    <div class="container text-center">
      <small>Copyright &copy; Mateus Brandão </small>
    </div>
  </footer>
</body>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/jquery.slim.js"></script>
</html>