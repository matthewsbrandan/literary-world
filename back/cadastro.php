<?php session_start();

if(!isset($_SESSION['user_id'])||$_SESSION['user_id']<=0) header('Location: ../sign-in/');
else{
    include('../../conn/function.php');
    
    if(isset($_GET['revela'])) $revela = 0;
    else $revela = inweb;
    if($revela==0) include('_format/masc_inicio.php');
    
    include('_format/function.php');

    if($revela==0) if(isset($_POST)) include('_format/masc_post.php');    

    if(isset($_GET['deleteLivro'])){
        $sql = "delete from tbllivro where livro_id='{$_POST['altLivroId']}' and livro_user_id='{$_SESSION['user_id']}';";
        
        if(enviarComand($sql,'bd_lworld')) header('Location: ../index.php?delete=0'); 
        else header('Location: ../index.php?delete=1');
    }else
    if(isset($_GET['updateLivro'])){
        $image = nameImage($_POST['altImg']);
        
        $sql = "update tbllivro set livro_nome='{$_POST['altNome']}', livro_qtdPag='{$_POST['altPag']}', livro_pagAtual='{$_POST['altPagAtual']}', livro_status='{$_POST['altStatus']}', livro_comprado='{$_POST['altComprado']}', livro_img='$image', livro_saga_id='{$_POST['altSaga']}', livro_escritor_id='{$_POST['altEscritor']}', livro_editora_id='{$_POST['altEditora']}' where livro_id='{$_POST['altLivroId']}' and livro_user_id='{$_SESSION['user_id']}';";
        
        if(enviarComand($sql,'bd_lworld')) header('Location: ../index.php?config='.$_POST['altLivroId']); 
        else header('Location: ../index.php?update=1');
    }else
    if(isset($_GET['uploadImg'])){
        if($revela==0) print_pre($_FILES['addImgLivro']);

        if($_FILES['addImgLivro']['error']==0){   
            $img = verificaNome($_FILES['addImgLivro']['name']);
            $dirUp = "../img/";
            move_uploaded_file($_FILES['addImgLivro']['tmp_name'],$dirUp.$img);
            
            if($revela==0) echo "<h5 class='text-center text-success'>UPLOAD CONCLUIDO COM SUCESSO:<br/> $img</h5>";
            
            header('Location: ../index.php?img=0');
        }
        else{ echo "<h5 class='text-center text-danger'>HOUVE UM ERRO</h5>"; }
    }else
    if(isset($_POST['idLivroLendo'])){
        function livroLido($id){
            return "update tbllivro set livro_pagAtual=livro_qtdPag, livro_status='Lido' where livro_id='$id' and livro_user_id='{$_SESSION['user_id']}';";
        }

        if(isset($_GET['pagina'])){
            $sql="select livro_qtdPag from tbllivro where livro_id='{$_POST['idLivroLendo']}' and livro_user_id='{$_SESSION['user_id']}';";
            if(enviarComand($sql,'bd_lworld')->fetch_assoc()['livro_qtdPag']>$_POST['inputQtdPag']){
                $sql = "update tbllivro set livro_pagAtual={$_POST['inputQtdPag']} where livro_id='{$_POST['idLivroLendo']}' and livro_user_id='{$_SESSION['user_id']}';";
            }
            else $sql = livroLido($_POST['idLivroLendo']);
        }else
        if(isset($_GET['lido'])) $sql = livroLido($_POST['idLivroLendo']);
        else die('Nenhuma das Opções pré-determinadas foi satisfeita, preenchimento incorreto.');
        
        if(enviarComand($sql,'bd_lworld')) header('Location: ../secular/index.php?llendo='.$_POST['idLivroLendo']);
        else header('Location: ../secular/index.php?errlendo='.$_POST['idLivroLendo']);
    }
    if(isset($_POST['addLivroProx'])){
        if($_POST['addLivroProx']=='1'){
            $_SESSION['cadNomeLivro']=null;
            $_SESSION['cadIdEditora']=null;
            $_SESSION['cadIdEscritor']=null;
            $_SESSION['cadIdSaga']=null;
            $testando=false;
            
            //Editora
            if($_POST['addNomeEditoraInput']){
                $sql = "call newEditora('{$_POST['addNomeEditoraInput']}','{$_SESSION['user_id']}');";    
                enviarComand($sql,'bd_lworld');
                $_SESSION['cadNomeEditora'] = $_POST['addNomeEditoraInput'];
            }
            else{ $_SESSION['cadNomeEditora'] = $_POST['addNomeEditora']; }
            $sql = "select * from tbleditora where editora_nome='{$_SESSION['cadNomeEditora']}' ";
            $sql.= "and editora_user_id='{$_SESSION['user_id']}';";
            $dataEdit = enviarComand($sql,'bd_lworld');
            $resEdit = $dataEdit->fetch_array();
            $_SESSION['cadIdEditora']=$resEdit['editora_id'];
            
            //Escritor
            if($_POST['addNomeEscritorInput']){
                $sql = "call newEscritor('{$_POST['addNomeEscritorInput']}','{$_SESSION['cadIdEditora']}','{$_SESSION['user_id']}');";
                enviarComand($sql,'bd_lworld');
                $_SESSION['cadNomeEscritor'] = $_POST['addNomeEscritorInput'];
            }
            else{ $_SESSION['cadNomeEscritor'] = $_POST['addNomeEscritor']; }
            $sql = "select * from tblescritor where escritor_nome='{$_SESSION['cadNomeEscritor']}' ";
            $sql.= "and escritor_user_id='{$_SESSION['user_id']}';";
            $dataEscrit = enviarComand($sql,'bd_lworld');
            $resEscrit = $dataEscrit->fetch_array();
            $_SESSION['cadIdEscritor']=$resEscrit['escritor_id'];

            //Saga
            if($_POST['addNomeSagaInput']){
                $sql = "call newSaga('{$_POST['addNomeSagaInput']}','{$_SESSION['cadIdEscritor']}','{$_SESSION['user_id']}');";
                enviarComand($sql,'bd_lworld');
                $_SESSION['cadNomeSaga'] = $_POST['addNomeSagaInput'];
            }else{ $_SESSION['cadNomeSaga'] = $_POST['addNomeSaga']; }
            $sql = "select * from tblsaga where saga_nome='{$_SESSION['cadNomeSaga']}' ";
            $sql.= "and saga_escritor_id='{$_SESSION['cadIdEscritor']}' and saga_user_id='{$_SESSION['user_id']}'";
            $dataSaga = enviarComand($sql,'bd_lworld');
            $resSaga = $dataSaga->fetch_array();
            $_SESSION['cadIdSaga']=$resSaga['saga_id'];

            //Livro
            $_SESSION['cadNomeLivro']=$_POST['addNomeLivro'];
            
            if($revela==0) echo "Nome Livro: {$_SESSION['cadNomeLivro']}<br/>";
            if($revela==0) echo "Id Editora: {$_SESSION['cadIdEditora']}<br/>";
            if($revela==0) echo "Id Escritor: {$_SESSION['cadIdEscritor']}<br/>";
            if($revela==0) echo "Id Saga: {$_SESSION['cadIdSaga']}<br/>";
            header('Location: ../secular/index.php?nLivro=2');
        }else
        if($_POST['addLivroProx']=='2'){
            //Nome do Livro, Quantidade de páginas, Página atual, Status('Lendo','Em Espera','Lido'), Se foi comprado ou não, Id da Saga, Id do Escritor, Id da Editora, Id do Usuário
            if(isset($_POST['addCompraLivro'])) $comprado = $_POST['addCompraLivro']=="on"?1:0;
            else $comprado=0;
            $pagAt = $_POST['addStatus']=="Lido"?$_POST['addQtdPag']:0;

            $sql = "call newLivro('{$_SESSION['cadNomeLivro']}','{$_POST['addQtdPag']}',$pagAt,'{$_POST['addStatus']}', $comprado,'{$_SESSION['cadIdSaga']}','{$_SESSION['cadIdEscritor']}','{$_SESSION['cadIdEditora']}','{$_SESSION['user_id']}');";
            enviarComand($sql,'bd_lworld');
            
            $sql = "select * from detalheLivros where Livro='{$_SESSION['cadNomeLivro']}' and Usuario='{$_SESSION['user_id']}';";
            $data = enviarComand($sql,'bd_lworld');

            if($revela==0) print_pre($sql);
            if($revela==0) print_pre($data);
            
            $res = $data->fetch_array();
            $_SESSION['cadIdLivro'] = $res['livro_id'];

            if($revela==0) echo "Id do Livro: ".$_SESSION['cadIdLivro'];
            
            header('Location: ../secular/index.php?nLivro=3');
        }else
        if($_POST['addLivroProx']=='3'){
            if($_SESSION['cadNomeLivro']==$_POST['addNomeLivro']){
                $img = "padrao.jpg";
                if($_POST['inlineRadioOptions']==1) $img = "padrao.jpg";
                if($_POST['inlineRadioOptions']==2) $img = "capa.jpg";
                if($_POST['inlineRadioOptions']==3) $img = nameImage($_POST['addImgGaleria']);
                if($_POST['inlineRadioOptions']==4){
                    if($revela==0) print_pre($_FILES['addImgLivro']);

                    if($_FILES['addImgLivro']['error']==0){   
                        $img = verificaNome($_FILES['addImgLivro']['name']);
                        $dirUp = "../img/";
                        move_uploaded_file($_FILES['addImgLivro']['tmp_name'],$dirUp.$img);
                        
                        if($revela==0) echo "<h5 class='text-center text-success'>UPLOAD CONCLUIDO COM SUCESSO:<br/> $img</h5>";
                    }else{
                        $img = "error=";
                        $img .= $_FILES['addImgLivro']['error']==1?$_FILES['addImgLivro']['error']:2;
                    }
                }
                if(substr($img,0,5)=="error"){
                    header('Location: ../secular/index.php?nLivro=3&'.$img);
                }else{
                    $sql="call updateImg('{$_SESSION['cadIdLivro']}','$img');";
                    if($revela==0) print_pre($sql);
                    enviarComand($sql,'bd_lworld');
                    header('Location: ../secular/index.php?nLivro=4');
                }
            }else{
                echo "<script>alert('Erro ao Alterar a Capa do Livro');</script>";
            }
        }
    }

    if($revela==0) include('_format/masc_fim.php');
}
?>