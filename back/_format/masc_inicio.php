<!DOCTYPE HTML>
<html>
<head>
    <title>LW - BackEnd</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style> 
        body{ background-image: linear-gradient(to left,#fffeee,rgba(30,20,20,.19)); }
        .box-shadow{ box-shadow: 1px 1px 5px black; }
        .card{ background: rgb(240,240,240,.2); }
        pre{ color: #eee;}
    </style>
    <script src="../js/jquery/jquery-3.4.1.min.js"></script>
    <script>
        function mostraPost(){
            if($('#bodingOne').hasClass("d-none"))$('#bodingOne').removeClass("d-none")
            else $('#bodingOne').addClass("d-none")
        }
        function copiar(elem){
            elem.removeClass('d-none').select();
            document.execCommand('copy');
            elem.addClass('d-none');
            alert('copiado');
        }        
    </script>
</head>
<body>
<div class="container my-2 py-2 bg-dark text-light box-shadow rounded">