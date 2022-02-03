document.addEventListener("DOMContentLoaded", function () {
    
    document.getElementById('btnGuardarImagen').addEventListener('click', function(e) {
		if(document.getElementById('imgUpload').files.length == 0) {
			alert('Error : No file selected');
            e.preventDefault();
			return;
		}

		let file = document.getElementById('imgUpload').files[0];
		let allowed_mime_types = [ 'image/jpeg', 'image/png', 'image/jpg' ];
		let allowed_size_mb = 2;
	
		if(allowed_mime_types.indexOf(file.type) == -1) {
			alert('Error : Incorrect file type');
			return;
		}

		if(file.size > allowed_size_mb*1024*1024) {
			alert('Error : Exceeded size');
			return;
		}

		let data = new FormData();
		data.append('file', document.getElementById('imgUpload').files[0]);

		let request = new XMLHttpRequest();
		request.open('POST', '../php/upload.php'); 
		request.addEventListener('load', function(e) {
			console.log(request.response);
		});
		request.send(data);
        e.preventDefault();
	});

});