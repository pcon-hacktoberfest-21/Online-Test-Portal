<!DOCTYPE html>
<html lang="en">

<head>
    <? include 'Include/head.php'?>
</head>

<body>
    <? include 'Include/nav.php'?>

    <div class="col-lg-4 mx-auto">
        <form class="mt-4">
            <center>
                <label class="sr-only" for="email">Email</label>
                <input type="text" class="mb-2 mr-sm-2" style=" 
                padding: .375rem .75rem;
                font-size: 0.9rem;
                width:60%;
                line-height: 1.3;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: .25rem;" name="search" placeholder="Enter Email/Mobile/Name/City" required>
                <button type="submit" class="btn btn-primary mb-2 btn-sm">Submit</button>
                <a class="btn btn-primary mb-2 btn-sm" href="?search=all" onclick="return confirm('This Process may take some time. Please Don\'t use it in test time\n Do you really want to continue?')">View All Users</a>
            </center>
        </form>
    </div>
    <div id="tests" class="col-lg-9 mx-auto px-5 rounded">
        <h3>Users</h3>
        <table class="table table-responsive-md">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Mobile</th>
                    <th class="text-center">City</th>
                    <th class="text-center">Verification</th>
                    <th class="text-center">#</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sl = 1;
                if (isset($user_data)) {
                    foreach ($user_data as $user) :
                ?>
                        <tr id="user_div_<?= $user->id ?>">
                            <td class="text-center"><?= $sl++ ?></td>
                            <td>
                                <?= $user->name; ?><br>
                                <a class='btn-info rounded mt-1 px-1 px-1' target="_blank" href="<?= getenv('app.baseURL') ?>/Admin/ViewUser/<?= $user->id; ?>">View</button>
                            </td>
                            <td class="text-center">
                                <?= $user->email; ?><br>
                                <a class='btn-info rounded mt-1 px-1 px-1' target="_blank" href="<?= getenv('app.baseURL') ?>/Admin/SuperLogin/<?= $user->id; ?>">Super Login</button>
                            </td>
                            <td class="text-center"><?= $user->phoneNo; ?></td>
                            <td class="text-center"><?= $user->city; ?></td>
                            <td class="text-center">
                                <span class="badge badge-default" id="verification_<?= $user->id ?>">
                                    <? if($user->verified){?>
                                    <i class="fas fa-check-circle text-success"></i><br>
                                    <button class='btn-info rounded mt-1' onclick="UnVerifyUser(`<?= $user->id ?>`)">Un Verify</button>
                                    <?}else{?>
                                    <i class="fas fa-times-circle text-danger"></i><br>
                                    <button class='btn-info rounded mt-1' onclick="VerifyUser(`<?= $user->id ?>`)">Verify</button>
                                    <?}?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button class="btn badge badge-sm btn-primary" onclick="DeleteUser(`<?= $user->id ?>`)">Delete User</button>
                                <span class="badge badge-sm badge-info" style="cursor:pointer" onclick="$.alert(`<?= $user->password ?>`)">Password</span>
                            </td>
                        </tr>
                <?php endforeach;
                } ?>
            </tbody>
        </table>
    </div>
</body>

</html>


<script>
    $(document).ready(function() {
        $('table').dataTable();
    });

    function VerifyUser(id) {
        if (!confirm('Do you really want to Verify this user')) {
            return
        }
        // console.log(id)
        var form = new FormData();
        form.append("user_id", id);
        form.append("verification", 1);
        var settings = {
            "url": `<?= getenv('app.baseURL') ?>/Admin/VerifyUser`,
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
        };
        $.ajax(settings).done(function(response) {
            // console.log(response);
            if (response == 1) {
                $("#verification_" + id).html(`
            <i class="fas fa-check-circle text-success"></i></span><br><button class='btn-info rounded mt-1' onclick="UnVerifyUser('${id}')">Un-Verify</button>`)
            }
        });
    }

    function UnVerifyUser(id) {
        if (!confirm('Do you really want to un-verify this user')) {
            return
        }
        var form = new FormData();
        form.append("user_id", id);
        form.append("verification", 0);
        var settings = {
            "url": `<?= getenv('app.baseURL') ?>/Admin/VerifyUser`,
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
        };
        $.ajax(settings).done(function(response) {
            console.log(response);
            if (response == 1) {
                $("#verification_" + id).html(`
                                <i class="fas fa-times-circle text-danger"></i><br>
                                <button class='btn-info rounded mt-1'  onclick="VerifyUser('${id}')">Verify</button>`)
            }
        });
    }

    function DeleteUser(id) {
        if (!confirm('Do you really want to delete this user')) {
            return
        }
        var form = new FormData();
        form.append("user_id", id);
        var settings = {
            "url": `<?= getenv('app.baseURL') ?>/Admin/DeleteUser`,
            "method": "POST",
            "timeout": 0,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": form
        };
        $.ajax(settings).done(function(response) {
            console.log(response);
            if (response == 1) {
                $("#user_div_" + id).remove()
            }
        });
    }
</script>