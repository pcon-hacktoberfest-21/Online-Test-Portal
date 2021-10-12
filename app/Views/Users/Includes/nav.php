<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand text-warning" href="<?=getEnv('app.baseURL')?>/dashboard"><b><?=getEnv('app.siteName')?></b></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="<?=getEnv('app.baseURL')?>/Dashboard"><i class="fas fa-home-lg-alt"></i> Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="<?=getEnv('app.baseURL')?>/Dashboard/Profile"><i class="fad fa-user-md"></i> Profile</a>
            </li>
        </ul>
        <div class="form-inline my-2 my-lg-0">
            <a href="<?=getEnv('app.baseURL')?>/logout"><button class="btn btn-outline-success my-2 my-sm-0" type="submit"><span class="glyphicon glyphicon-log-in"></span><i class="fas fa-sign-out"></i> Log Out</button></a>
        </div>
    </div>
</nav>
<?
// include 'cutomContext.php';
?>