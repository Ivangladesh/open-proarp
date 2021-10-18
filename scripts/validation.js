"use strict";

let valid = {};

function ValidationResponse (tipo, longitud) {
    this.tipo = tipo;
    this.longitud = longitud;
}
/**
 * Validación si es un dato de tipo numérico.
 * @param {string} data Dato a validar.
 * @param {number} longitud Dato a validar.
 * @returns {boolean}
 */
 valid.number = function (data,longitud) {
    const regNumeros = /^[0-9]+$/;
    let tipo = regNumeros.test(data.value);
    let long = longitud !== undefined ? long = valid.longitud(data, longitud) : true;
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
     let long = longitud !== undefined ? long = valid.longitud(data, longitud) : true;
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
    let long = longitud !== undefined ? long = valid.longitud(data, longitud) : true;
    if(!tipo){
       data.classList.add('error');
    } else{
       data.classList.remove('error');
    }
    let resp = new ValidationResponse(tipo, long);
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
    let long = longitud !== undefined ? long = valid.longitud(data, longitud) : true;
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
                    if(valid.text(controls[i]).tipo) { 
                        controls[i].classList.remove('error');
                        ok = true;
                    } else{
                        controls[i].classList.add('error');
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
