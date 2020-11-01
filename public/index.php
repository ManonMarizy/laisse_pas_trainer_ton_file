<?php
const UPLOAD_DIR_PATH = __DIR__ . '/uploads/';
$it = new FilesystemIterator(UPLOAD_DIR_PATH);

if (!empty($_FILES['files']['name'][0])) {
    $files = $_FILES['files'];

    $uploaded = [];
    $failed = [];
    $allowed = ['png', 'jpg', 'gif'];

    foreach ($_FILES['files']['name'] as $position => $file_name) {
        $file_tmp = $files['tmp_name'][$position];
        $file_size = $files['size'][$position];
        $file_error = $files['error'][$position];
        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));

        if(in_array($file_ext, $allowed)) {
            if($file_error === 0) {
                if($file_size <= 1000000) {
                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $file_destination = UPLOAD_DIR_PATH . $file_name_new;

                    if(move_uploaded_file($file_tmp, $file_destination)) {
                        $uploaded[$position] = $file_destination;
                        header('Location: index.php');
                    } else {
                        $failed[$position] = "[{$file_name}] failed to upload.";
                    }
                } else {
                    $failed[$position] = "[{$file_name}] is too large.";
                }
            } else {
                $failed[$position] = "[{$file_name}] errored with code {$file_error}";
            }
       } else {
            $failed[$position] = "[{$file_name}] file exention '{$file_ext}' is not allowed.";
        }
    }
    if(!empty($uploaded)) {
        print_r($uploaded);
    }
    if(!empty($failed)) {
        print_r($failed);
    }
}
?>

<form action="index.php" method="post" enctype="multipart/form-data">
    <label for="imageUpload">Upload an profile image</label>
    <input type="file" name="files[]" multiple="multiple" id="imageUpload" />
    <button>Send</button>
</form>


 <?php foreach ($it as $fileInfo): ?>
<figure>
    <img src="uploads/<?= $fileInfo->getFilename()?>" alt="<?= $fileInfo->getFilename()?>" style="width: 300px">
    <figcaption><?= $fileInfo->getFilename() . "\n" ?></figcaption>
</figure>
<?php endforeach; ?>

