<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="card-body">
        <form enctype="multipart/form-data" action="<?= getenv('app.baseURL') ?>/Mail/Extract" method="POST">
            <div class="form-group">
                <label for="file">File <small>(only xlsx)</small></label>
                <input type="file" class="form-control-file" name="excel_file" id="file" accept=".xlsx">
                <small style="color:red;font-family: 'Playfair Display', serif;">You Need to manually upload image</small>
            </div>
            <button type="submit" class="btn btn-primary tex-light" style="font-family: 'Libre Baskerville', serif;"><i class="far fa-upload"></i> Upload</button>
        </form>
    </div>
</body>

</html>