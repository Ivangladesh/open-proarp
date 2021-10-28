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
    divNot.innerHTML = `<p>${data} &nbsp <span id='closeAlert' style="cursor:pointer" onclick='this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode); return false;'>x</span></p>`;
    document.getElementById('mainContainer').appendChild(divNot);
    setTimeout(function(){
        alerta.fadeOut();
    },time);
};

alerta.fadeOut = function() {
    var notificationDiv =  document.getElementById('containerNotification');
    notificationDiv.style.opacity = '0'
    const target = document.getElementById("target");
    notificationDiv.addEventListener('transitionend', () => notificationDiv.remove());
}
