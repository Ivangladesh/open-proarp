document.addEventListener("DOMContentLoaded", function () {
    
    document.getElementById("aceptarValidarCuenta").addEventListener('click', function(e){
        setTimeout(function(){location.href = 'index.php';},1000);
        e.preventDefault();
    });

});