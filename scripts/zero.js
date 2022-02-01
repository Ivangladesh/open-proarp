"use strict";

let zero = {};
const forms = document.getElementsByTagName("form");
const nav = document.querySelectorAll(".nav-link");
let sesion = "";

/**
 * Oculta la ventana modal.
 * @returns {void}
 **/
zero.hideModal = function () {
    const btnCloseList = document.getElementsByClassName("btn-close-modal");
    for (let i = 0, iLen = btnCloseList.length; i < iLen; i++) {
        btnCloseList[i].addEventListener("click", function (e) {
            let mdl = this.getAttribute("data-id");
            document.getElementById(mdl).style.display = "none";
            e.preventDefault();
        });
    }
    // document.getElementById("btnCerrarModal").addEventListener("click", function (e) {
    //     let dataId = e.target.getAttribute("data-id");
    //     document.getElementById(dataId).style.display = "none;";
    //     e.preventDefault();
    // });
};

/**
 * Intercambia elementos de la barra de navegación.
 * @param {event} e Evento, no requerido.
 * @returns {void}
 */
zero.responsividadNavBar = function (e) {
    const items = document.getElementsByClassName("item");
    let count = 0;
    for (let key in items) {
        if (count < items.length) {
            if (items[key].classList.contains("active")) {
                items[key].classList.remove("active");
                document.getElementById("toggleBtn").innerHTML =
                    "<i class='fa fa-bars toggle'></i>";
            } else {
                items[key].classList.add("active");
                document.getElementById("toggleBtn").innerHTML =
                    "<i class='fa fa-times toggle'></i>";
            }
            count++;
        }
    }
};

/**
 * Handler para eventos de la barra de navegación.
 * @summary Intercambia ventanas 
 * @param {evento} evt Evento que detona la acción.
 * @returns {boolean}
 **/
zero.navEventHandler = function (e) {
    let dataId = e.target.getAttribute("data-id");
    let padreId = document.getElementById("content");
    let subs = padreId.getElementsByClassName("sub-container");
    for (var i = 0; i < subs.length; i++) {
        var a = subs[i];
        a.style.display = "none";
    }
    document.getElementById(dataId).style.display = "block";
    for (let i = 0; i < forms.length; i++) {
        let controls = forms[i].elements;
        for (let i = 0, iLen = controls.length; i < iLen; i++) {
            if (controls[i].nodeName === "INPUT") {
                controls[i].classList.remove("error");
                valid.removeErrorMessageInput(null, "error-password");
            }
        }
        let tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        document.getElementById(dataId).style.display = 'block';
        for (let i = 0; i < forms.length; i++) {
            let controls = forms[i].elements;
            for (let i = 0, iLen = controls.length; i < iLen; i++) {
                if (controls[i].nodeName === 'INPUT') {
                    controls[i].classList.remove('error');
                    valid.removeErrorMessageInput(null, 'error-password');
                }
            }
            forms[i].reset();
            forms[i].reset();
        }
        if (dataId !== "div-inicio-sesion" && dataId !== "div-registro-usuario") {
            if (sessionStorage["Session"] !== undefined) {
                sesion = atob(sessionStorage["Session"]);
            } else {
                call.post(
                    "../php/session.php",
                    JSON.stringify({ Action: "ObtenerSesion" }),
                    zero.sesionHandler,
                    true
                );
            }
            let usrName = sesion.split("|")[1];
            document.getElementById("txtUsuarioContacto").value = usrName;
        }
        e.preventDefault();
    }
};

/**
 * Handler para eventos de la barra de tabs.
 * Muestra y oculta elementos provenientes de un tab.
 * @param {evento} evt Evento que detona la acción.
 * @returns {void}
 **/
zero.tabEventHandler = function (e) {
    let i, tabcontent, tablinks;
    let dataId = e.target.getAttribute("data-id");

    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tab-nav");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(dataId).style.display = "block";
    e.currentTarget.className += " active";
    e.preventDefault();
};

/**
 * Handler que intercambia elementos para mostrar en el contenedor principal.
 * @param {string} Identificador del elemento a mostrar.
 * @returns {void}
 **/
zero.navHandler = function (dataId) {
    let padreId = document.getElementById("content");
    let subs = padreId.getElementsByClassName("sub-container");
    for (var i = 0; i < subs.length; i++) {
        var a = subs[i];
        a.style.display = "none";
    }
    document.getElementById(dataId).style.display = "block";
    for (let i = 0; i < forms.length; i++) {
        let controls = forms[i].elements;
        for (let i = 0, iLen = controls.length; i < iLen; i++) {
            if (controls[i].nodeName === "INPUT") {
                controls[i].classList.remove("error");
            }
        }
    }
};

/**
 * Handler para restaurar la sesión en el navegador.
 * @returns {void}
 **/
zero.sesionHandler = function (e) {
    if (e.ok) {
        sesion = e.data;
        sessionStorage.setItem("Session", e.data);
    } else {
        call.post(
            "../php/session.php",
            JSON.stringify({ Action: "CerrarSesion" }),
            handler,
            true
        );
    }
};

/**
 * Handler que recarga la ventana del navegador.
 * @param {evento} evt Evento que detona la acción.
 * @returns {void}
 **/
function handler(e) {
    if (e.ok) {
        alerta.notif("info", e.data, 2000);
        setTimeout(function () {
            location.reload();
        }, 2100);
    } else {
        console.log(e);
    }
}

document.addEventListener("DOMContentLoaded", function () {

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("tab-nav")) {
            zero.tabEventHandler(e);
        }
    });

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("toggle")) {
            zero.responsividadNavBar(e);
        }
    });

    nav.forEach((el) =>
        el.addEventListener("click", (event) => {
            zero.navEventHandler(event);
        })
    );
    zero.hideModal();
    //FIN DEL DOCUMENT READY
});
