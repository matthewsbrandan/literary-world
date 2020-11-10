<div id="accordion">
    <div class="card my-2 box-shadow">
        <div class="card-header bg-light" id="headingOne">
            <button class="btn btn-danger btn-block font-weight-bold" onclick="mostraPost()"> $_POST </button>
        </div>
        <div class="card-body text-left d-none" id="bodingOne">
            <pre><?php print_r($_POST); ?></pre>    
        </div>
    </div>
</div>