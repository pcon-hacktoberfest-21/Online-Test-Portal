<style type="text/css">
    .context-menu {
        position: absolute;
        text-align: center;
        background: lightgray;
        border: 1px solid black;
        z-index: 2;
    }

    .context-menu ul {
        padding: 0px;
        margin: 0px;
        min-width: 150px;
        list-style: none;
    }

    .context-menu ul li {
        padding-bottom: 7px;
        padding-top: 7px;
        border: 1px solid black;
    }

    .context-menu ul li a {
        text-decoration: none;
        color: black;
    }

    .context-menu ul li:hover {
        background: darkgray;
    }
</style>

<div id="contextMenu" class="context-menu" style="display:none">
    <ul>
        <li><a onClick="window.location.reload();"><i class="fas fa-sync"></i> Refresh Page</a></li>
        <li><a href="<?= getenv('app.baseURL') ?>"><i class="fas fa-home"></i> Return to Home</a></li>
    </ul>
</div>

<script>
    document.onclick = hideMenu;
    document.oncontextmenu = rightClick;

    function hideMenu() {
        document.getElementById(
            "contextMenu").style.display = "none"
    }

    function rightClick(e) {
        e.preventDefault();

        if (document.getElementById(
                "contextMenu").style.display == "block")
            hideMenu();
        else {
            var menu = document
                .getElementById("contextMenu")

            menu.style.display = 'block';
            menu.style.left = e.pageX + "px";
            menu.style.top = e.pageY + "px";
        }
    }
</script>