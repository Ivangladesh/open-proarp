"use strict";

let alerta = {};

/**
 * Crea una notificación.
 * @param {string} type Tipo de notificación [ok, fail, info].
 * @param {URLSearchParams} data Objeto con los parámetros para el mensaje.
 * @param {int} time Método que se usará para manejar la respuesta.
 **/
 alerta.notif = function (type, data, time) {
    let divNot = document.createElement("div");
    divNot.id = 'containerNotification';
    divNot.classList.add('container-notification')
    if(type === 'ok'){
        divNot.classList.add('notification-success') 
    } else if(type === 'fail'){
        divNot.classList.add('notification-fail') 
    } else if(type === 'info'){
        divNot.classList.add('notification-info') 
    }
    divNot.innerHTML = `<p style="display:inline">${data} &nbsp </p><span id='closeAlert' style="cursor:pointer;float: right;" onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;'>x</span>`;
    document.getElementById('content').appendChild(divNot);
    setTimeout(function(){
        divNot.remove()
    },time);
};

/**
 * Crea una notificación.
 * @param {string} type Tipo de notificación [ok, fail, info].
 * @param {URLSearchParams} data Objeto con los parámetros para el mensaje.
 * @param {int} time Método que se usará para manejar la respuesta.
 **/
 alerta.confirm = function (data, callback) {
    let msg = `<p style="display:inline"> Esta acción <strong>no</strong> se puede deshacer, ¿desea continuar? </p>`;
    document.getElementById('mdlConfirmMensaje').innerHTML = msg;
    document.getElementById('btnAceptarConfirmacion').addEventListener('click', function(){
        callback({callback:'Confirmar', ok:true, data:data});
        document.getElementById('mdlConfirm').style.display = "none";
    });
    document.getElementById("btnCancelarConfirmacion").addEventListener('click', function(e){
        document.getElementById('mdlConfirm').style.display = "none";
        e.preventDefault();
    });
    document.getElementById("mdlConfirm").style.display = "block";
};