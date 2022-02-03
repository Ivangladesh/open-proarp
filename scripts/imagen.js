document.addEventListener("DOMContentLoaded", function () {
    let msg = "";
    document.getElementById('btnGuardarImagen').addEventListener('click', function(e) {
		if(document.getElementById('imgUpload').files.length == 0) {
            msg = 'No se ha seleccionado ningún archivo.';
            alerta.notif('fail', msg, 3000);
            e.preventDefault();
			return;
		}

		let file = document.getElementById('imgUpload').files[0];
		let allowed_mime_types = [ 'image/jpeg', 'image/png', 'image/jpg' ];
		let allowed_size_mb = 2;
	
		if(allowed_mime_types.indexOf(file.type) == -1) {
            msg = 'Tipo de archivo no soportado.';
            alerta.notif('fail', msg, 3000);
			return;
		}

		if(file.size > allowed_size_mb*1024*1024) {
            msg = 'El tamaño del archivo excede al permitido.';
            alerta.notif('fail', msg, 3000);
			return;
		}

		let data = new FormData();
		data.append('file', document.getElementById('imgUpload').files[0]);

		let request = new XMLHttpRequest();
		request.open('POST', '../php/upload.php'); 
		request.addEventListener('load', function(e) {
			if(JSON.parse(request.response).ok){
                msg = 'Imagen guardada exitosamente.';
                alerta.notif('ok', msg, 3000);
            } else{
                msg = 'Ha ocurrido un error.';
                alerta.notif('fail', msg, 3000);
            }

		});
		request.send(data);
        e.preventDefault();
	});

});