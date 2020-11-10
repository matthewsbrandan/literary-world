<?php
    session_start();
    $lworld_id = 2;
    include('../../conn/function.php');
    if(isset($_GET['revela'])) $revela = 0;
    else $revela = inweb;
    if($revela==0) include('_format/masc_inicio.php');
    include('_format/function.php');
    if($revela==0) echo "<h1 class='text-center'>Log / Create</h1><hr/>";
    if(isset($_POST)){
        if($revela==0) include('_format/masc_post.php');
    }
    if(isset($_POST['btnEntrar'])){
        $senha = md5($_POST['inputPassword']);
        $sql="select * from tbluser where user_email='{$_POST['inputEmail']}' and user_senha='$senha';";
        $r = enviarComand($sql,'bd_lworld');
        $retorno = $r->fetch_assoc();
        if($retorno){
            $_SESSION['user_id']= $retorno['user_id'];
            $_SESSION['user_nome']= $retorno['user_nome'];
            $_SESSION['user_email']= $retorno['user_email'];
            if(isset($_GET['mtworld'])&&isset($_SESSION['user_mtworld'])){
                $sql = "select id from user_sites where usuario_id='{$_SESSION['user_mtworld']}' and sites_id='$lworld_id';";
                $r = enviarComand($sql,'bd_mtworld');
                $data = $r->fetch_assoc();
                if(isset($data['id'])){
                    $sql = "update user_sites set status='ativo', login='{$_POST['inputEmail']}', senha='$senha' where id='{$data['id']}';";
                    if(enviarComand($sql,'bd_mtworld')) header('Location: ../index.php?vinculado=1');
                    else header('Location: ../index.php?vinculado=0');
                }
            }else header('Location: ../');
        }else{ header('Location: ../sign-in/index.php?error=1'); }
        if($revela==0) print_pre($sql);
        if($revela==0) print_pre($retorno);
    }else
    if(isset($_POST['btnCadastrar'])){
        $senha = md5($_POST['cadPassword']);
        $sql = "insert tbluser(user_nome,user_email,user_senha) values ";
        $sql.= "('{$_POST['cadNome']}','{$_POST['cadEmail']}','$senha');";
        if(enviarComand($sql,'bd_lworld')){
            $more = isset($_GET['mtworld'])?'&mtworld':'';
            header('Location: ../sign-in/index.php?success'.$more);
        }else{ header('Location: ../sign-in/index.php?error=2'); }
        if($revela==0) print_pre($sql);
        if($revela==0) print_pre($retorno);

    }
    if($revela==0) include('_format/masc_fim.php');
?>
