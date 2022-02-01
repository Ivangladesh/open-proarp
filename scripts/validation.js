"use strict";

let valid = {};

function ValidationResponse(tipo, longitud) {
    this.tipo = tipo;
    this.longitud = longitud;
}

/**
 * Validación de igualdad.
 * @param {string} data Dato a validar.
 * @returns {boolean}
 */
valid.equal = function (data) {
    let secondId = data.getAttribute("for");
    let second = document.getElementById(secondId);
    let resp = data.value === second.value ? true : false;
    return resp;
};

/**
 * Validación de igualdad para contraseñas.
 * @param {string} data Dato a validar.
 * @returns {boolean}
 */
valid.equalPassword = function (first) {
    let resp = false;
    if (first.getAttribute("for") !== null) {
        let secondId = first.getAttribute("for");
        let second = document.getElementById(secondId);
        const msg = "Las contraseñas no coinciden";
        const errorClass = "error-password";
        resp = first.value === second.value ? true : false;
        valid.removeErrorMessageInput(null, errorClass);
        if (!resp) {
            valid.addErrorMessageInput(first, msg);
            valid.addErrorMessageInput(second, msg);
            first.classList.add("error");
            second.classList.add("error");
        } else {
            first.classList.remove("error");
            second.classList.remove("error");
        }
    }
    return resp;
};

/**
 * Validación si es un dato de tipo numérico.
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 * @returns {boolean}
 */
valid.number = function (data, longitud) {
    const regNumeros = /^[0-9]+$/;
    let tipo = regNumeros.test(data.value);
    let long = longitud !== undefined ? valid.longitud(data, longitud) : true;
    if (!tipo) {
        data.classList.add("error");
    } else {
        data.classList.remove("error");
    }
    let resp = new ValidationResponse(tipo, long);
    return resp;
};
/**
 * Validación si es un dato corresponde a un email.
 * Expresión regular tomada de:
 * https://www.regular-expressions.info/email.html
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 * @returns {boolean}
 **/
valid.email = function (data, longitud) {
    const regEmail =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    let tipo = regEmail.test(data.value);
    let long = longitud !== undefined ? valid.longitud(data, longitud) : true;
    if (!tipo) {
        data.classList.add("error");
    } else {
        data.classList.remove("error");
    }
    let resp = new ValidationResponse(tipo, long);
    return resp;
};

/**
 * Validación si es un dato corresponde al formato aceptado para las fechas.
 * Expresión regular tomada de:
 * https://www.regular-expressions.info/email.html
 * @param {string} data Dato a validar.
 * @returns {boolean}
 **/
valid.date = function (data) {
    const regFecha = /^\d{4}-\d{2}-\d{2}$/;
    let tipo = regFecha.test(data.value);
    let dateYear = new Date(data.value).getFullYear();
    let todaysYear = new Date().getFullYear();
    let msg = "";
    if (todaysYear - dateYear < 18) {
        msg = `Fecha inválida, no puedes ser menor de 18 años.`;
        valid.addErrorMessageInput(data, msg);
        tipo = false;
    }
    if (!tipo) {
        data.classList.add("error");
    } else {
        data.classList.remove("error");
    }
    let resp = new ValidationResponse(tipo, tipo);
    return resp;
};

/**
 * Validación si es un dato corresponde a un texto.
 * Expresión regular tomada de:
 * https://www.regular-expressions.info/email.html
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 * @returns {boolean}
 **/
valid.text = function (data) {
    const regText = /^(?!\s*$)(?!(.)\1\1){3}.+/;
    let tipo = regText.test(data.value);
    const errorId = data.id + "errorValid";
    let maxlength = parseInt(data.getAttribute("maxlength"));
    let minlength = parseInt(data.getAttribute("minlength"));
    let long = true;
    let msg = "";
    let caracteres = data.value.length;
    if (!isNaN(maxlength) && !isNaN(minlength)) {
        if (minlength < caracteres && maxlength > caracteres) {
            long = true;
        } else {
            long = false;
            if (minlength < caracteres) {
                msg = `Tiene demasiados caracteres. Caracteres ${caracteres}`;
            } else if (maxlength > caracteres) {
                msg = `Tiene muy pocos caracteres. Caracteres ${caracteres}`;
            }
            valid.addErrorMessageInput(data, msg);
        }
    } else if (!isNaN(maxlength) && isNaN(minlength)) {
        if (maxlength > caracteres) {
            long = true;
        } else {
            long = false;
            msg = `Tiene muy pocos caracteres. Caracteres ${caracteres}`;
            valid.addErrorMessageInput(data, msg);
        }
    } else if (!isNaN(minlength) && isNaN(maxlength)) {
        if (minlength < caracteres) {
            long = true;
        } else {
            long = false;
            msg = `Tiene demasiados caracteres. Caracteres ${caracteres}`;
            valid.addErrorMessageInput(data, msg);
        }
    }
    if (!long) {
        data.classList.add("error");
    } else {
        data.classList.remove("error");
        valid.removeErrorMessageInput(errorId, null);
    }

    if (!tipo) {
        data.classList.add("error");
        msg = `El texto contiene demasiados caracteres iguales seguidos.`;
        valid.addErrorMessageInput(data, msg);
    } else {
        data.classList.remove("error");
        valid.removeErrorMessageInput(errorId, null);
    }

    let resp = new ValidationResponse(tipo, long);
    return resp;
};

/**
 * Validación si es un dato corresponde a un texto.
 * Expresión regular tomada de:
 * https://www.regular-expressions.info/email.html
 * @param {string} data Dato a validar.
 * @returns {boolean}
 **/
valid.password = function (data) {
    const regText =
        /^(?=.*\d)(?!.*\s)(?=.*[a-zA-Z])(?=.*[#$^+=!*()@%&]).{8,15}(?!(.)\1\1){3}$/;
    let tipo = regText.test(data.value);
    const errorId = data.id + "errorValid";
    valid.removeErrorMessageInput(errorId, null);
    let msg = `La contraseña debe tener una longitud mínima de 8 caracteres y máxima de 15 o no contiene caracteres especiales (#$^+=!*()@%&).`;
    if (!tipo) {
        data.classList.add("error");
        valid.addErrorMessageInput(data, msg);
    } else {
        data.classList.remove("error");
    }

    let resp = new ValidationResponse(tipo, tipo);
    return resp;
};

/**
 * Método que añade un mensaje de error a un campo.
 * @param {object} data Objeto javascript al que se le añadirá el mensaje de error.
 * @param {string} msg Objeto javascript al que se le añadirá el mensaje de error.
 * @param {boolean} status Indicará si se añade o elimina el mensaje.
 **/
valid.addErrorMessageInput = function (data, msg) {
    var htmlItem = document.createElement("small");
    htmlItem.style.color = "red";
    htmlItem.id = data.id + "errorValid";
    htmlItem.classList.add("error-password");
    htmlItem.innerHTML = msg;
    data.parentNode.insertBefore(htmlItem, data.nextSibling);
};

/**
 * Método que elimina el mensaje de error a un campo.
 * @param {string} id Identificador del elemento html al que se le eliminará el mensaje de error.
 * @param {string} class Clase del elemento html al que se le eliminará el mensaje de error.
 **/
valid.removeErrorMessageInput = function (id, errorClass) {
    if (id !== undefined) {
        let elem = document.getElementById(id);
        if (elem !== null) {
            elem.remove();
        }
    }
    if (errorClass !== undefined) {
        let elem = document.getElementsByClassName(errorClass);

        while (elem.length > 0) {
            elem[0].classList.remove("error");
            elem[0].parentNode.removeChild(elem[0]);
        }
    }
};

/**
 * Validación si es un dato de tipo numérico.
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 * @returns {boolean}
 */
valid.decimal = function (data, longitud) {
    const regDecimal = /^\d+(\.\d{1,2})?$/;
    let tipo = regDecimal.test(data.value);
    let long = longitud !== undefined ? valid.longitud(data, longitud) : true;
    let resp = new ValidationResponse(tipo, long);
    return resp;
};

/**
 * Método que establece de manera dinámica validaciones para cada elemento input y textarea.
 * @param {form} form Elemento form a validar.
 * @returns {boolean}
 */
valid.form = function (form) {
    let countOk = 0;
    var controls = form.elements;
    valid.removeErrorMessageInput(null, "error-password");
    for (var i = 0, iLen = controls.length; i < iLen; i++) {
        if (controls[i].nodeName === "INPUT") {
            switch (controls[i].getAttribute("data-id")) {
                case "email":
                    if (valid.email(controls[i]).tipo) {
                        controls[i].classList.remove("error");
                    } else {
                        countOk += 1;
                        controls[i].classList.add("error");
                    }
                    break;
                case "text":
                    if (valid.text(controls[i]).tipo) {
                        controls[i].classList.remove("error");
                    } else {
                        countOk += 1;
                        controls[i].classList.add("error");
                    }
                    break;
                case "password":
                    let resp = valid.password(controls[i]);
                    if (resp.tipo) {
                        controls[i].classList.remove("error");
                    } else {
                        countOk += 1;
                        controls[i].classList.add("error");
                    }
                    break;
                case "decimal":
                    if (valid.decimal(controls[i]).tipo) {
                        controls[i].classList.remove("error");
                    } else {
                        countOk += 1;
                        controls[i].classList.add("error");
                    }
                    break;
                case "number":
                    if (valid.number(controls[i]).tipo) {
                        controls[i].classList.remove("error");
                    } else {
                        countOk += 1;
                        controls[i].classList.add("error");
                    }
                    break;
                case "date":
                    if (valid.date(controls[i]).tipo) {
                        controls[i].classList.remove("error");
                    } else {
                        countOk += 1;
                        controls[i].classList.add("error");
                    }
                    break;
                default:
                    controls[i].classList.remove("error");
                    break;
            }
        } else if (controls[i].nodeName === "TEXTAREA") {
            if (valid.text(controls[i]).tipo) {
                controls[i].classList.remove("error");
            } else {
                countOk += 1;
                controls[i].classList.add("error");
            }
        }
    }
    if (countOk <= 0) {
        return true;
    } else {
        alerta.notif("fail", `Corrija los ${countOk} campos marcados`, 3000);
        return false;
    }
};

/**
 * Método que establece event listeners de manera dinámica para cada elemento input y textarea.
 * Cada evento detona en una validación para el tipo de input.
 * @param {form} form Elemento form a validar.
 * @returns {boolean}
 */
valid.setupForm = function (data) {
    let controls = data.elements;
    for (let i = 0, iLen = controls.length; i < iLen; i++) {
        if (controls[i].nodeName === "INPUT") {
            controls[i].addEventListener("change", function () {
                switch (this.getAttribute("data-id")) {
                    case "email":
                        valid.email(this);
                        break;
                    case "date":
                        valid.date(this);
                        break;
                    case "decimal":
                        valid.decimal(this);
                        break;
                    case "number":
                        valid.number(this);
                        break;
                    case "text":
                        valid.text(this);
                        break;
                    case "password":
                        valid.equalPassword(this);
                        break;
                    default:
                        this.classList.remove("error");
                        break;
                }
            });
        } else if (controls[i].nodeName === "TEXTAREA") {
            controls[i].addEventListener("change", function () {
                valid.text(this);
            });
        }
    }
};

/**
 * Validación de longitud.
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 * @returns {boolean}
 */
valid.longitud = function (data, longitud) {
    return data.length >= longitud;
};
