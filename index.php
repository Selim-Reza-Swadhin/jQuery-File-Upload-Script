<?php
    if (isset($_FILES['attachments'])) {
        $msg = "";
        $targetFile = "uploads/" . basename($_FILES['attachments']['name'][0]);
        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "File already exists!");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            $msg = array("status" => 1, "msg" => "File Has Been Uploaded", "path" => $targetFile);

        exit(json_encode($msg));
    }
?>
<html>
	<head>
		<title>jQuery File Upload Script</title>
		<style type="text/css">
			#dropZone {
				border: 3px dashed #0088cc;
				padding: 50px;
				width: 500px;
				margin-top: 20px;
			}

			#files {
				border: 1px dotted #0088cc;
				padding: 20px;
				width: 200px;
				display: none;
			}

            #error {
                color: red;
            }
		</style>
	</head>
	<body>
		<center>
			<img src="images/logo.png"><br><br>
			<div id="dropZone">
				<h1>Drag & Drop Files...</h1>
				<input type="file" id="fileupload" name="attachments[]" multiple>
			</div>
			<h1 id="error"></h1><br><br>
			<h1 id="progress"></h1><br><br>
			<div id="files"></div>
		</center>

		<script src="js/jquery-3.5.1.min.js"></script>
		<script src="js/vendor/jquery.ui.widget.js" type="text/javascript"></script>
		<script src="js/jquery.iframe-transport.js" type="text/javascript"></script>
		<script src="js/jquery.fileupload.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(function () {
               let files = $("#files");

               $("#fileupload").fileupload({
                   url: 'index.php',
                   dropZone: '#dropZone',
                   dataType: 'json',
                   autoUpload: false
               }).on('fileuploadadd', function (e, data) {
                   let fileTypeAllowed = /.\.(gif|jpg|png|jpeg)$/i;
                   let fileName = data.originalFiles[0]['name'];
                   let fileSize = data.originalFiles[0]['size'];

                   if (!fileTypeAllowed.test(fileName))
                        $("#error").html('Only images are allowed!');
                   else if (fileSize > 500000)
                       $("#error").html('Your file is too big! Max allowed size is: 500KB');
                   else {
                       $("#error").html("");
                       data.submit();
                   }
               }).on('fileuploaddone', function(e, data) {
                    let status = data.jqXHR.responseJSON.status;
                    let msg = data.jqXHR.responseJSON.msg;

                    if (status == 1) {
                        let path = data.jqXHR.responseJSON.path;
                        $("#files").fadeIn().append('<p><img style="width: 100px; height: 100px;" src="'+path+'" /></p>');
                    } else
                        $("#error").html(msg);
               }).on('fileuploadprogressall', function(e,data) {
                    let progress = parseInt(data.loaded / data.total * 100, 10);
                    $("#progress").html("Completed: " + progress + "%");
               });
            });
        </script>
	</body>
</html>