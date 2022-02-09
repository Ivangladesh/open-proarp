document.addEventListener("DOMContentLoaded", function () {
    let msg = "";

    const frmUploadImagen = document.getElementById('frmUploadImagen');

    if(frmUploadImagen !== null){
        valid.setupForm(frmUploadImagen);
        // document.getElementById("btnRegistrarMensajeContacto").addEventListener('click', function(e){
        //     if(valid.form(frmContacto)){
        //         RegistrarMensaje();
        //     }
        //     e.preventDefault();
        // });
    }

    document.getElementById("agregarImagen").addEventListener('click', function(e){
        ObtenerProductos();
        e.preventDefault();
    });

    document.getElementById("tabImagenes").addEventListener('click', function (e) {
        ObtenerImagenes();
        e.preventDefault();
    });

    document.getElementById('btnGuardarImagen').addEventListener('click', function(e) {
		if(document.getElementById('imgUpload').files.length == 0) {
            msg = 'No se ha seleccionado ningún archivo.';
            alerta.notif('fail', msg, 3000);
            e.preventDefault();
			return;
		}

		let file = document.getElementById('imgUpload').files[0];
		let allowed_mime_types = [ 'image/jpeg', 'image/png', 'image/jpg' ];
		let allowed_size_mb = 1;
	
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
				let archivo = JSON.parse(request.response).data;
				let datos = {
					Action: "InsertarImagen",
					InventarioId : document.getElementById("cmbProductos").value,
					Descripcion : document.getElementById("txtDescripcion").value,
					Archivo : archivo
				};
        		call.post("../php/imagen.php", JSON.stringify(datos), handler, true);
            } else{
                msg = 'Ha ocurrido un error.';
                alerta.notif('fail', msg, 3000);
            }

		});
		request.send(data);
        e.preventDefault();
	});

    function ObtenerProductos() {
        let datos = {Action: "ObtenerProductos"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

	function ObtenerImagenes() {
        let datos = { Action: "ObtenerImagenes" };
        call.post("../php/imagen.php", JSON.stringify(datos), handler, true);
    }

    let poblarComboProductos = (datos) =>{
		let select = document.getElementById("cmbProductos");
		removeOptions(select);
		let placeHolder = document.createElement("option");
		placeHolder.text = "Seleccione...";
		placeHolder.value = "";
		placeHolder.disabled = true;
		placeHolder.selected = true;
		select.appendChild(placeHolder);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
			var option = document.createElement("option");
			option.text = datos[i].Nombre;
			option.value = datos[i].InventarioId;
			select.appendChild(option);
        };
    }

	function removeOptions(selectElement) {
		var i, L = selectElement.options.length - 1;
		for(i = L; i >= 0; i--) {
		   selectElement.remove(i);
		}
	 }

	 let poblarImagenes = (datos) => {
        let table = document.getElementById('tblImagenes');
        var oldTbody = table.children[1];
        var newTbody = document.createElement('tbody');
        table.removeChild(oldTbody);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
            let newRow =
                '<tr>' +
                '<td style="display: none;" id=' + datos[i].ImagenId + '></td>' +
                '<td><img src="' + datos[i].RutaThumb +'"></td>' +
                '<td>' + datos[i].Descripcion + '</td>' +
                '<td>' + datos[i].Nombre + '</td>' +
                '</tr>';
            let emptyRow = newTbody.insertRow(newTbody.rows.length);
            emptyRow.innerHTML = newRow;
        };
        table.appendChild(newTbody);
    }

	function handler(e){
        let msg = "";
        switch (e.callback) {
            case "ObtenerProductos":
                if(e.ok){
                    poblarComboProductos(e.data);
                }
                break;
            case "InsertarImagen":
                if(e.ok){
                    msg = 'Ok';
					frmUploadImagen.reset();
                    alerta.notif('ok', 'Imagen almacenada exitosamente.', 3000);
					document.getElementById('mdlUploadImagen').style.display = "none";
					ObtenerImagenes();
                } else{
                    msg = 'Ha ocurrido un error al actualizar el estado del mensaje.';
                    alerta.notif('ok', e.data, 3000);
                }
                break;
			case "ObtenerImagenes":
				if (e.ok) {
					poblarImagenes(e.data);
				}
				break;
            case "Confirmar":
                if(e.ok){
                    eliminarMensaje(e.data);
                } else{
                    msg = 'Ha ocurrido un error al actualizar el estado del mensaje.';
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            case "EliminarMensaje":
                if(e.ok){
                    alerta.notif('ok', 'Mensaje eliminado correctamente.', 3000);
                    ObtenerProductos();
                } else{
                    alerta.notif('fail', 'Ha ocurrido un error al eliminar el mensaje.', 3000);
                }
                break;
            default:
                break;
        }
    }


});