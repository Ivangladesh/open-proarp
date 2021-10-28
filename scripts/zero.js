"use strict";

let zero = {}
const forms = document.getElementsByTagName('form');
const nav = document.querySelectorAll('.nav-link');

/**
 * Intercambia elementos de la barra de navegación.
 * @param {string} e Evento, no requerido.
 * @returns {void}
 */
zero.responsividadNavBar = function (e) {
    const items = document.getElementsByClassName("item");
    let count = 0;
    for (let key in items) {
        if (count < items.length) {
            if (items[key].classList.contains('active')) {
                items[key].classList.remove('active');
                document.getElementById('toggleBtn').innerHTML = "<i class='fa fa-bars toggle'></i>";
            } else {
                items[key].classList.add('active');
                document.getElementById('toggleBtn').innerHTML = "<i class='fa fa-times toggle'></i>";
            }
            count++;
        }
    }
}

zero.navEventHandler = function (e) {
    let dataId = e.target.getAttribute("data-id");
    let padreId = document.getElementById('content');
    let subs = padreId.getElementsByClassName('sub-container');
    for (var i = 0; i < subs.length; i++) {
        var a = subs[i];
        a.style.display = 'none';
    }
    document.getElementById(dataId).style.display = 'block';
    for (let i = 0; i < forms.length; i++) {
        let controls = forms[i].elements;
        for (let i = 0, iLen = controls.length; i < iLen; i++) {
            if (controls[i].nodeName === 'INPUT') {
                controls[i].classList.remove('error');
                valid.removeErrorMessageInput(null,'error-password');
            }
        }
        forms[i].reset();
    }
    e.preventDefault();
}

zero.navHandler = function (dataId) {
    let padreId = document.getElementById('content');
    let subs = padreId.getElementsByClassName('sub-container');
    for (var i = 0; i < subs.length; i++) {
        var a = subs[i];
        a.style.display = 'none';
    }
    document.getElementById(dataId).style.display = 'block';
    for (let i = 0; i < forms.length; i++) {
        let controls = forms[i].elements;
        for (let i = 0, iLen = controls.length; i < iLen; i++) {
            if (controls[i].nodeName === 'INPUT') {
                controls[i].classList.remove('error');
            }
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('toggle')) {
            zero.responsividadNavBar(e);
        }
    });
    nav.forEach(el => el.addEventListener('click', event => {
        zero.navEventHandler(event);
    }));
    //FIN DEL DOCUMENT READY
});