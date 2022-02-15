document.addEventListener("DOMContentLoaded", function () {
    
    let estadoInventario, estadoInventarioId;

    document.getElementById("inventario").addEventListener('click', function(e){
        ObtenerProductos();
        ObtenerProveedores();
        e.preventDefault();
    });
    document.getElementById("agregarProducto").addEventListener('click', function (e) {
        mostrarModalNuevoProducto();
        e.preventDefault();
    });

    const mostrarModalNuevoProducto = () => {
        document.getElementById('mdlNuevoProducto').style.display = "block"
    }

    function ObtenerProductos() {
        let datos = {Action: "ObtenerProductos"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function ObtenerProveedores() {
        let datos = {Action: "ObtenerProveedores"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function ObtenerEstadoInventario() {
        let datos = {Action: "ObtenerEstadoInventario"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function ObtenerDetalleProducto(id) {
        let datos = {Action: "ObtenerDetalleProducto", ProductoId: id};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function ObtenerDetalleProveedor(id) {
        let datos = {Action: "ObtenerDetalleProveedor", Proveedor: id};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function ActualizarProducto() {
        let datos = {Action: "ActualizarProducto"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function ActualizarProveedor() {
        let datos = {Action: "ActualizarProveedor"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function EliminarMensaje() {
        let datos = {Action: "EliminarMensaje"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function EliminarProveedor() {
        let datos = {Action: "EliminarProveedor"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function InsertarProducto() {
        let datos = {Action: "InsertarProducto"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function InsertarProveedor() {
        let datos = {Action: "InsertarProveedor"};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    let poblarInventario = (datos) =>{
        let table = document.getElementById('tblInventario');
        var oldTbody = table.children[1];
        var newTbody = document.createElement('tbody');
        table.removeChild(oldTbody);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
            var fechaFormateada = new Date(datos[i].FechaRecepcion).toLocaleDateString("es-MX");
            let newRow =
                '<tr>' +
                '<td style="display: none;" id=' + datos[i].InventarioId + '></td>' +
                '<td>' + datos[i].Nombre + '</td>' +
                '<td>' + datos[i].Descripcion + '</td>' +
                '<td>' + datos[i].Existencias + '</td>' +
                '<td>' + datos[i].EstadoInventario + '</td>' +
                '</tr>';
                let emptyRow = newTbody.insertRow(newTbody.rows.length);
                emptyRow.innerHTML = newRow; 
                if(datos[i].EstadoMensaje === "Nuevo"){
                    emptyRow.classList.add('tr-new')
                }
        };
        table.appendChild(newTbody);
        eliminarEventListener();
    }

    let poblarProveedores = (datos) =>{
        let table = document.getElementById('tblProveedores');
        var oldTbody = table.children[1];
        var newTbody = document.createElement('tbody');
        table.removeChild(oldTbody);
        let longitud = datos.length - 1;
        for (let i = 0; i <= longitud; i++) {
            var fechaFormateada = new Date(datos[i].FechaRecepcion).toLocaleDateString("es-MX");
            let newRow =
                '<tr>' +
                '<td style="display: none;" id=' + datos[i].ProveedorId + '></td>' +
                '<td>' + datos[i].RazonSocial + '</td>' +
                '<td>' + datos[i].RFC + '</td>' +
                '<td>' + datos[i].Telefono + '</td>' +
                '</tr>';
                let emptyRow = newTbody.insertRow(newTbody.rows.length);
                emptyRow.innerHTML = newRow; 
                if(datos[i].EstadoMensaje === "Nuevo"){
                    emptyRow.classList.add('tr-new')
                }
        };
        table.appendChild(newTbody);
        eliminarEventListener();
    }

    let poblarComboEstadoInventario = (datos) =>{
		let select = document.getElementById("cmbEstadoInv");
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
			option.text = datos[i].EstadoInventario;
			option.value = datos[i].EstadoInventarioId;
			select.appendChild(option);
        };
    }

    document.getElementById("tblInventario").addEventListener('click', function (e) {
        if (e.target.cellIndex < 5) {
            let id = parseInt(e.target.parentNode.cells[0].id);
            ObtenerDetalleProducto(id);
        }
        e.preventDefault();
    });

    document.getElementById("tblProveedores").addEventListener('click', function (e) {
        if (e.target.cellIndex < 5) {
            let id = parseInt(e.target.parentNode.cells[0].id);
            ObtenerDetalleProveedor(id);
        }
        e.preventDefault();
    });

    const mostrarModalInventario = (datos) => {
        ObtenerEstadoInventario();
        var fechaFormateada = new Date(datos.FechaCompra).toISOString().slice(0, 10);
        document.getElementById('txtNombreInv').value = datos.Nombre;
        document.getElementById('txtDescripcionInv').value = datos.Descripcion;
        estadoInventario = datos.EstadoInventario;
        estadoInventarioId = datos.Estado;
        document.getElementById('txtFechaInv').value = fechaFormateada;
        document.getElementById('txtPrecioVentaInv').value = datos.PrecioVenta;
        document.getElementById('txtExistenciasInv').value = datos.Existencias;
        document.getElementById('mdlDetalleProducto').style.display = "block"
    }

    const mostrarModalProveedores = (datos) => {
        var fechaFormateada = new Date(datos.FechaRecepcion).toLocaleDateString("es-MX");
        document.getElementById('txtNombreD').value = datos.NombreCompleto;
        document.getElementById('txtEmailD').value = datos.UsuarioEmail;
        document.getElementById('txtAsuntoD').value = datos.Asunto;
        document.getElementById('txtFechaD').value = fechaFormateada;
        document.getElementById('txtMensajeD').value = datos.Mensaje;
        document.getElementById('mdlNuevoProveedor').style.display = "block"
    }

    function eliminarEventListener (){
        const eliminarList = document.getElementsByClassName('btn-eliminar');
        for (let i = 0, iLen = eliminarList.length; i < iLen; i++) {
            eliminarList[i].addEventListener("click", function () {
                let rowId = parseInt(this.getAttribute('data-id'));
                alerta.confirm(rowId, handler);
            });
        }
    }

    function selectElement(id, value, text) {    
        let element = document.getElementById(id);
        element.value = value;
        element.text = text;
    }

	function removeOptions(selectElement) {
		var i, L = selectElement.options.length - 1;
		for(i = L; i >= 0; i--) {
		   selectElement.remove(i);
		}
	 }

    function handler(e){
        let msg = "";
        switch (e.callback) {
            case "ObtenerProductos":
                if(e.ok){
                    poblarInventario(e.data);
                }
                break;
            case "ObtenerProveedores":
                if(e.ok){
                    poblarProveedores(e.data);
                }
                break;
            case "ObtenerDetalleProducto":
                if (e.ok) {
                    mostrarModalInventario(e.data);
                } else {
                    alerta.notif('fail', 'Ha ocurrido un error consulte al administrador.', 3000);
                }
                break;
            case "ObtenerDetalleProveedor":
                if (e.ok) {
                    mostrarModalProveedores(e.data)
                } else {
                    alerta.notif('fail', 'Ha ocurrido un error consulte al administrador.', 3000);
                }
                break;
            case "ObtenerEstadoInventario":
                if (e.ok) {
                    poblarComboEstadoInventario(e.data);
                    selectElement('cmbEstadoInv', estadoInventarioId, estadoInventario);
                } else {
                    alerta.notif('fail', 'Ha ocurrido un error consulte al administrador.', 3000);
                }
                break;
            default:
                break;
        }
    }
    

});