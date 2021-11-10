"use strict";

let call = {};

/**
 * @typedef datos
 * @property {string} Action Método que será ejecutado
 */

/**
 * Ejecuta la llamada ajax.
 * @param {string} url dirección url al servicio.
 * @param {method} callback Método que se usará para manejar la respuesta.
 * @param {string} method Tipo de llamada.
 * @param {datos} data Objeto con los parámetros de la consulta.
 * @param {boolean} async Indica si la llamada se hará de forma asíncrona o no.
 */
call.send = function (url, callback, method, data, async) {
  let request = new XMLHttpRequest();
  request.withCredentials = true;
  try {
    request.open(method, url, async);
    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status === 200) {
          if(request.responseText !== ""){
            callback(JSON.parse(request.responseText));
          } else{
            console.table(JSON.parse(request));
            callback({ok:false,data:null,callback:data.Action});
          }        
      }
    };
    request.setRequestHeader("content-type", "application/json");
    request.setRequestHeader("cache-control", "no-cache");
    request.send(data);
  } catch (err) {
    call.handleError(err);
  }
};
/**
 * Ejecuta una llamada tipo GET.
 * @param {string} url dirección url al servicio.
 * @param {URLSearchParams} data Objeto con los parámetros de la consulta.
 * @param {method} callback Método que se usará para manejar la respuesta.
 * @param {boolean} async Indica si la llamada se hará de forma asíncrona o no.
 **/
call.get = function (url, data, callback, async) {
  var query = [];
  for (var key in data) {
    query.push(encodeURIComponent(key) + "=" + encodeURIComponent(data[key]));
  }
  let queryString = url + data.toString();
  console.log(queryString);
  call.send(queryString, callback, "GET", null, async);
};
/**
 * Ejecuta una llamada tipo POST.
 * @param {string} url dirección url al servicio.
 * @param {datos} data Objeto con los parámetros de la consulta.
 * @param {method} callback Método que se usará para manejar la respuesta.
 * @param {boolean} async Indica si la llamada se hará de forma asíncrona o no.
 **/
call.post = function (url, data, callback, async) {
  call.send(url, callback, "POST", data, async);
};

call.handleError = function (ex) {
  //TODO: Implementar un mensaje de alerta
  console.error(ex.status);
};
