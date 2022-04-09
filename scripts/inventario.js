document.addEventListener("DOMContentLoaded", function () {
    
    let estadoInventario, estadoInventarioId, proveedorId;

    const frmNuevoProducto = document.getElementById('frmNuevoProducto');
    const frmNuevoProveedor = document.getElementById('frmNuevoProveedor');
    const frmDetalleProducto = document.getElementById('frmDetalleProducto');
    const frmDetalleProveedor = document.getElementById('frmDetalleProveedor');

    if(frmNuevoProducto !== null){
        valid.setupForm(frmNuevoProducto);
        document.getElementById("btnAceptarNuevoProducto").addEventListener('click', function(e){
            if(valid.form(frmNuevoProducto)){
                InsertarProducto();
            }
            e.preventDefault();
        });
    }

    if(frmNuevoProveedor !== null){
        valid.setupForm(frmNuevoProveedor);
        document.getElementById("btnAceptarNuevoProveedor").addEventListener('click', function(e){
            if(valid.form(frmNuevoProveedor)){
                InsertarProveedor();
            }
            e.preventDefault();
        });
    }

    if(frmDetalleProducto !== null){
        valid.setupForm(frmDetalleProducto);
        document.getElementById("btnAceptarProducto").addEventListener('click', function(e){
            if(valid.form(frmDetalleProducto)){
                let dataId = e.target.getAttribute("data-id");
                ActualizarProducto(dataId);
            }
            e.preventDefault();
        });
    }

    if(frmDetalleProveedor !== null){
        valid.setupForm(frmDetalleProveedor);
        document.getElementById("btnAceptarProveedor").addEventListener('click', function(e){
            if(valid.form(frmDetalleProveedor)){
                let dataId = e.target.getAttribute("data-id");
                ActualizarProveedor(dataId);
            }
            e.preventDefault();
        });
    }

    document.getElementById("inventario").addEventListener('click', function(e){
        ObtenerProductos();
        ObtenerProveedores();
        e.preventDefault();
    });
    document.getElementById("agregarProducto").addEventListener('click', function (e) {
        mostrarModalNuevoProducto();
        valid.resetValidations(frmNuevoProducto);
        frmNuevoProducto.reset();
        e.preventDefault();
    });
    document.getElementById("agregarProveedor").addEventListener('click', function (e) {
        mostrarModalNuevoProveedor();
        valid.resetValidations(frmNuevoProveedor);
        frmNuevoProveedor.reset();
        e.preventDefault();
    });

    const mostrarModalNuevoProducto = () => {
        document.getElementById('mdlNuevoProducto').style.display = "block"
    }
    const mostrarModalNuevoProveedor = () => {
        document.getElementById('mdlNuevoProveedor').style.display = "block"
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
        let datos = {Action: "ObtenerDetalleProveedor", ProveedorId: id};
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function ObtenerImagenesPorInventarioId(inventarioId) {
        let datos = { Action: "ObtenerImagenesPorInventarioId", InventarioId : inventarioId };
        call.post("../php/imagen.php", JSON.stringify(datos), handler, true);
    }

    function ActualizarProducto(id) {
        let nombre = document.getElementById('txtNombreInv').value;
        let descripcion = document.getElementById('txtDescripcionInv').value;
        let fechaCompra = document.getElementById('txtFechaInv').value;
        let precioVenta = document.getElementById('txtPrecioVentaInv').value;
        let existencias = document.getElementById('txtExistenciasInv').value;
        let datos = {
            Action: "ActualizarProducto",
            InventarioId : id,
            Nombre :nombre,
            Descripcion : descripcion,
            Estado: estadoInventarioId,
            PrecioVenta:precioVenta,
            FechaCompra:fechaCompra,
            Existencias :existencias
        };
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function ActualizarProveedor(id) {
        let razonSocial = document.getElementById('txtRazonDetalleProv').value;
        let marcaComercial = document.getElementById('txtMarcaDetalleProv').value;
        let RFC = document.getElementById('txtRFCDetalleProv').value;
        let direccion = document.getElementById('txtDireccionDetalleProv').value;
        let telefono = document.getElementById('txtTelefonoDetalleProv').value;
        let telefonoAlternativo = document.getElementById('txtTelefonoAltDetalleProv').value;
        let nombreContacto = document.getElementById('txtContactoDetalleProv').value;
        let descripcion = document.getElementById('txtDescripcionDetalleProv').value;
        let notas = document.getElementById('txtNotasDetalleProv').value;
        let datos = {
            Action: "ActualizarProveedor",
            ProveedorId : id,
            RazonSocial : razonSocial,
            MarcaComercial : marcaComercial,
            RFC : RFC,
            Direccion : direccion,
            Telefono : telefono,
            TelefonoAlternativo : telefonoAlternativo,
            NombreContacto : nombreContacto,
            Descripcion : descripcion,
            Notas : notas
        };
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function InsertarProducto() {
        let nombre = document.getElementById('txtNombreNuevoProd').value;
        let descripcion = document.getElementById('txtDescripcionNuevoProd').value;
        let fechaCompra = document.getElementById('txtFechaNuevoProd').value;
        let precioVenta = document.getElementById('txtPrecioVentaNuevoProd').value;
        let precioCompra = document.getElementById('txtPrecioCompraNuevoProd').value;
        let existencias = document.getElementById('txtExistenciasNuevoProd').value;
        let proveedorId = document.getElementById('cmbProveedorNuevoProd').value;
        let datos = {
            Action: "InsertarProducto",
            Nombre :nombre,
            Descripcion : descripcion,
            PrecioVenta : precioVenta,
            FechaCompra : fechaCompra,
            Existencias : existencias,
            ProveedorId : proveedorId,
            Compra : precioCompra
        };
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function InsertarProveedor() {
        let razonSocial = document.getElementById('txtRazonNuevoProv').value;
        let marcaComercial = document.getElementById('txtMarcaNuevoProv').value;
        let rfc = document.getElementById('txtRFCNuevoProv').value;
        let direccion = document.getElementById('txtDireccionNuevoProv').value;
        let telefono = document.getElementById('txtTelefonoNuevoProv').value;
        let telefonoAlternativo = document.getElementById('txtTelefonoAltNuevoProv').value;
        let nombreContacto = document.getElementById('txtContactoNuevoProv').value;
        let descripcion = document.getElementById('txtDescripcionNuevoProv').value;
        let notas = document.getElementById('txtNotasNuevoProv').value;

        let datos = {
            Action: "InsertarProveedor",
            RazonSocial :razonSocial,
            MarcaComercial : marcaComercial,
            RFC : rfc,
            Direccion : direccion,
            Telefono : telefono,
            TelefonoAlternativo : telefonoAlternativo,
            NombreContacto : nombreContacto,
            Descripcion : descripcion,
            Notas : notas
        };
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

    
    let poblarComboProveedores = (datos) =>{
		let select = document.getElementById("cmbProveedorNuevoProd");
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
			option.text = datos[i].RazonSocial;
			option.value = datos[i].ProveedorId;
			select.appendChild(option);
        };
    }

    let poblarComboProveedoresDetalle = (datos) =>{
		let select = document.getElementById("cmbProveedorDetalleProd");
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
			option.text = datos[i].RazonSocial;
			option.value = datos[i].ProveedorId;
			select.appendChild(option);
        };
    }

    let poblarImagenes = (datos, ok) => {
        let divGaleria = document.getElementById('galeriaProducto');
        divGaleria.innerHTML = "";
        let longitud = datos.length - 1;
        if(!ok){
            divGaleria.innerHTML = datos;
        } else{
            for (let i = 0; i <= longitud; i++) {
                let img = document.createElement("img");
                let a = document.createElement("a");
                img.setAttribute("src", datos[i].RutaThumb);
                img.setAttribute("id", datos[i].ImagenId);
                img.setAttribute("class", "img-producto");
                img.setAttribute("alt", i + "imagen no encontrada");
                a.setAttribute("href", datos[i].RutaFisica);
                a.setAttribute("target", "_blank");
                a.append(img);
                divGaleria.append(a);
            };
        }

    }

    document.getElementById("tblInventario").addEventListener('click', function (e) {
        if (e.target.cellIndex < 5) {
            let id = parseInt(e.target.parentNode.cells[0].id);
            ObtenerDetalleProducto(id);
            ObtenerImagenesPorInventarioId(id);
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

    document.getElementById("btnEliminarProducto").addEventListener('click', function (e) {
        let dataId = e.target.getAttribute("data-id");
        let data = {callback : "EliminarProducto", id : dataId}
        alerta.confirm(data, handler);
        e.preventDefault();
    });

    document.getElementById("btnEliminarProveedor").addEventListener('click', function (e) {
        let dataId = e.target.getAttribute("data-id");
        let data = {callback : "EliminarProducto", id : dataId}
        alerta.confirm(data, handler);
        e.preventDefault();
    });

    function eliminarProducto(id) {
        let datos = {
            Action: "EliminarProducto",
            ProductoId: id
        }
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    function eliminarProveedor(id) {
        let datos = {
            Action: "EliminarProveedor",
            ProveedorId: id
        }
        call.post("../php/inventario.php", JSON.stringify(datos), handler, true);
    }

    const mostrarModalInventario = (datos) => {
        ObtenerEstadoInventario();
        var fechaFormateada = new Date(datos.FechaCompra).toISOString().slice(0, 10);
        document.getElementById('txtNombreInv').value = datos.Nombre;
        document.getElementById('txtDescripcionInv').value = datos.Descripcion;
        estadoInventario = datos.EstadoInventario;
        estadoInventarioId = datos.Estado;
        proveedorId = datos.Proveedor;
        document.getElementById('txtFechaInv').value = fechaFormateada;
        document.getElementById('txtPrecioVentaInv').value = datos.PrecioVenta;
        document.getElementById('txtExistenciasInv').value = datos.Existencias;
        document.getElementById('btnAceptarProducto').setAttribute("data-id", datos.InventarioId);
        document.getElementById('btnEliminarProducto').setAttribute("data-id", datos.InventarioId);
        document.getElementById('mdlDetalleProducto').style.display = "block"
    }

    const mostrarModalProveedores = (datos) => {
        var fechaFormateada = new Date(datos.FechaRecepcion).toLocaleDateString("es-MX");
        document.getElementById('txtRazonDetalleProv').value = datos.RazonSocial;
        document.getElementById('txtMarcaDetalleProv').value = datos.MarcaComercial;
        document.getElementById('txtRFCDetalleProv').value = datos.RFC;
        document.getElementById('txtDireccionDetalleProv').value = datos.Direccion;
        document.getElementById('txtTelefonoDetalleProv').value = datos.Telefono;
        document.getElementById('txtTelefonoAltDetalleProv').value = datos.TelefonoAlternativo;
        document.getElementById('txtContactoDetalleProv').value = datos.NombreContacto;
        document.getElementById('txtDescripcionDetalleProv').value = datos.Descripcion;
        document.getElementById('txtNotasDetalleProv').value = datos.Notas;
        document.getElementById('btnAceptarProveedor').setAttribute("data-id", datos.ProveedorId);
        document.getElementById('btnEliminarProveedor').setAttribute("data-id", datos.ProveedorId);
        document.getElementById('mdlDetalleProveedor').style.display = "block"
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
        // element.text = text;
    }

	function removeOptions(selectElement) {
		var i, L = selectElement.options.length - 1;
		for(i = L; i >= 0; i--) {
		   selectElement.remove(i);
		}
	 }

    function handler(e){
        let msg = "";
        let msgFail = 'Ha ocurrido un error consulte al administrador.';
        switch (e.callback) {
            case "ObtenerProductos":
                if(e.ok){
                    poblarInventario(e.data);
                }
                break;
            case "ObtenerProveedores":
                if(e.ok){
                    poblarProveedores(e.data);
                    poblarComboProveedores(e.data);
                    poblarComboProveedoresDetalle(e.data);
                }
                break;
            case "ObtenerDetalleProducto":
                if (e.ok) {
                    mostrarModalInventario(e.data);
                } else {
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "ObtenerDetalleProveedor":
                if (e.ok) {
                    mostrarModalProveedores(e.data)
                } else {
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "ObtenerEstadoInventario":
                if (e.ok) {
                    poblarComboEstadoInventario(e.data);
                    selectElement('cmbEstadoInv', estadoInventarioId,);
                    selectElement('cmbProveedorDetalleProd', proveedorId);
                } else {
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "ObtenerImagenesPorInventarioId":
                if (e.ok) {
                    poblarImagenes(e.data, e.ok);
                } else{
                    poblarImagenes(e.data, e.ok);
                }
                break;
            case "ActualizarProveedor":
                if(e.ok){
                    alerta.notif('ok', 'Registro actualizado correctamente.', 3000);
                    ObtenerProveedores();
                    document.getElementById("mdlDetalleProveedor").style.display = "none";
                } else{
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "ActualizarProducto":
                if(e.ok){
                    alerta.notif('ok', 'Registro actualizado correctamente.', 3000);
                    ObtenerProductos();
                    document.getElementById("mdlDetalleProducto").style.display = "none";
                } else{
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "EliminarProveedor":
                if(e.ok){
                    alerta.notif('ok', 'Registro eliminado correctamente.', 3000);
                    ObtenerProveedores();
                    document.getElementById("mdlDetalleProveedor").style.display = "none";
                } else{
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "EliminarProducto":
                if(e.ok){
                    alerta.notif('ok', 'Registro eliminado correctamente.', 3000);
                    ObtenerProductos();
                    document.getElementById("mdlDetalleProducto").style.display = "none";
                } else{
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "InsertarProducto":
                if(e.ok){
                    alerta.notif('ok', 'Registro realizado correctamente.', 3000);
                    ObtenerProductos();
                    document.getElementById("mdlNuevoProducto").style.display = "none";
                } else{
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "InsertarProveedor":
                if(e.ok){
                    alerta.notif('ok', 'Registro realizado correctamente.', 3000);
                    ObtenerProveedores();
                    document.getElementById("mdlNuevoProveedor").style.display = "none";
                } else{
                    alerta.notif('fail', msgFail, 3000);
                }
                break;
            case "Confirmar":
                if (e.ok) {
                    if(e.data.callback === "EliminarProducto"){
                        document.getElementById("mdlDetalleProducto").style.display = "none";
                        eliminarProducto(e.data.id);
                    } else if(e.data.callback === "EliminarProveedor"){
                        document.getElementById("mdlDetalleProveedor").style.display = "none";
                        eliminarProveedor(e.data.id);
                    }
                   
                } else {
                    msg = 'Ha ocurrido un error, consulte a su administrador.';
                    alerta.notif('ok', e.data, 3000);
                }
                break;
            default:
                break;
        }
    }
    

});