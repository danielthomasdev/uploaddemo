<?php
require __DIR__ . '/vendor/autoload.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php
    //mime type video/webm

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = filesize($_FILES['fileToUpload']["tmp_name"]);
        if (!$check)
        {
            $ffmpeg = FFMpeg\FFMpeg::create(array(
                'ffmpeg.binaries' => 'C:/ffmpeg/ffmpeg.exe',
                'ffprobe.binaries' => 'C:/ffmpeg/ffprobe.exe',
                'timeout' => 3600, // The timeout for the underlying process
                'ffmpeg.threads' => 12, // The number of threads that FFMpeg should use
                ));
            
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
            {
                echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                $video = $ffmpeg->open($target_file);
                $video
                ->save(new FFMpeg\Format\Video\WebM(), 'uploads/export-webm.webm');
            } 
            else 
            {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    ?>
    <form method="post" enctype="multipart/form-data">
        <div class="row pt-5">
            <div class="col">
                <div class="mb-3">
                    <input class="form-control" type="file" id="formFile" name="fileToUpload">
                </div>
                <div class="col-auto">
                    <button type="submit" name="submit" class="btn btn-primary mb-3">Convert and Upload</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

</body>
</html>