"use strict";

let valid = {};

function ValidationResponse (tipo, longitud) {
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
    let tipo = (data.value === second.value) ? true : false;
    let resp = new ValidationResponse(tipo, true);
    return resp;
};

/**
 * Validación si es un dato de tipo numérico.
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 * @returns {boolean}
 */
 valid.number = function (data,longitud) {
    const regNumeros = /^[0-9]+$/;
    let tipo = regNumeros.test(data.value);
    let long = (longitud !== undefined) ? valid.longitud(data, longitud) : true;
    if(!tipo){
        data.classList.add('error');
     } else{
        data.classList.remove('error');
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
     const regEmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
     let tipo = regEmail.test(data.value);
     let long = (longitud !== undefined) ? valid.longitud(data, longitud) : true;
     if(!tipo){
        data.classList.add('error');
     } else{
        data.classList.remove('error');
     }
     let resp = new ValidationResponse(tipo, long);
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
 valid.text = function (data, longitud) {
    const regText = /^[a-zA-Z]+$/;
    let tipo = regText.test(data.value);
    let long = (longitud !== undefined) ? valid.longitud(data, longitud) : true;
    if(!tipo){
       data.classList.add('error');
    } else{
       data.classList.remove('error');
    }
    let resp = new ValidationResponse(tipo, long);
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
 valid.password = function (data, longitud) {
    const regText = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z])(?=.*[#$^+=!*()@%&]).{8,}$/;
    let tipo = regText.test(data.value);
    let equal = (data.getAttribute("for") !== null) ? valid.equal(data) : true;
    var htmlItem = document.createElement("small");
    htmlItem.style.color = "red";
    htmlItem.id = 'errorPassword';
    if(!tipo) {
        htmlItem.innerHTML = 'Su contraseña no cumple con los requerimientos mínimos de longitud (8 caracteres) o no contiene caracteres especiales (#$^+=!*()@%&).';
    }
    if(!equal){
        htmlItem.innerHTML += ' Las contraseñas no coinciden';
    }

    if(!tipo){
       data.classList.add('error');
       data.parentNode.insertBefore(htmlItem, data.nextSibling);
    } else{
       data.classList.remove('error');
       htmlItem.remove();
    }
    let resp = new ValidationResponse(tipo, equal);
    return resp;
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
    let long = (longitud !== undefined) ? valid.longitud(data, longitud) : true;
    let resp = new ValidationResponse(tipo, long);
    return resp;
};

valid.form = function(form) {
    var ok = false;
    var controls = form.elements;
    for (var i=0, iLen=controls.length; i<iLen; i++) {
        if(controls[i].nodeName === 'INPUT'){
            switch (controls[i].getAttribute('data-id')) {
                case 'email':
                    if(valid.email(controls[i]).tipo) { 
                        controls[i].classList.remove('error');
                        ok = true;
                    } else{
                        controls[i].classList.add('error');
                        ok = false;
                    }
                    break;
                case 'text':
                    if(valid.text(controls[i]).tipo) { 
                        controls[i].classList.remove('error');
                        ok = true;
                    } else{
                        controls[i].classList.add('error');
                        ok = false;
                    }
                    break;
                case 'password':
                    var htmlItem = document.createElement("small");
                    htmlItem.style.color = "red";
                    htmlItem.id = 'errorPassword';
                    if(valid.password(controls[i]).tipo) { 
                        controls[i].classList.remove('error');
                        if(htmlItem !== null){
                            htmlItem.remove();
                        }
                        ok = true;
                    } else{
                        htmlItem.innerHTML = 'Su contraseña no cumple con los requerimientos mínimos de longitud (8 caracteres) o no contiene caracteres especiales (#$^+=!*()@%&).';
                        (!valid.password(controls[i]).longitud) ? htmlItem.innerHTML += ' Las contraseñas no coinciden' : "";
                        controls[i].classList.add('error');
                        controls[i].parentNode.insertBefore(htmlItem, controls[i].nextSibling);
                        ok = false;
                    }
                    break;
                case 'decimal':
                    if(valid.decimal(controls[i]).tipo) { 
                        controls[i].classList.remove('error');
                        ok = true;
                    } else{
                        controls[i].classList.add('error');
                        ok = false;
                    }
                    break;
                case 'number':
                    if(valid.number(controls[i]).tipo) { 
                        controls[i].classList.remove('error');
                        ok = true;
                    } else{
                        controls[i].classList.add('error');
                        ok = false;
                    }
                    break;
                default:
                    controls[i].classList.remove('error');
                    ok = true;
                    break;
            }
        }
    }
    return ok;
}


/**
 * Establece validaciones en un form.
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 */
valid.setupForm = function(data){
    let controls = data.elements;
    for (let i=0, iLen=controls.length; i<iLen; i++) {
        if(controls[i].nodeName === 'INPUT'){
            controls[i].addEventListener("change", function(){
                switch (this.getAttribute('data-id')) {
                    case 'email':
                        valid.email(this);
                        break;
                    case 'date':
                        //valid.email(this);
                        break;
                    case 'decimal':
                        valid.decimal(this);
                        break;
                    case 'number':
                        valid.number(this);
                        break;
                    default:
                        this.classList.remove('error');
                        break;
                }
            });
        }
    }
}

/**
 * Validación de longitud.
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 * @returns {boolean}
 */
 valid.longitud = function (data, longitud) {
     return data.length >= longitud;
};
