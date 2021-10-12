<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand text-warning" href="<?=getenv('app.baseURL')?>/Admin">Admin Portal</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?=getenv('app.baseURL')?>/Admin/Test"><i class="fas fa-book-reader"></i>Tests</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?=getenv('app.baseURL')?>/Admin/AddTest"><i class="fas fa-layer-plus"></i> Create Test</a>
            </li>
        </ul>
        <span>
            <a href="<?=getenv('app.baseURL')?>/Admin/logout" class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-power-off"></i> Logout </a>
        </span>
    </div>
</nav>