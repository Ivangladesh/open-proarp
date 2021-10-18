document.addEventListener("DOMContentLoaded", function () {
    const forms = document.getElementsByTagName('form');

    function responsividadNavBar(e) {
        const items = document.getElementsByClassName("item");
        let count = 0;
        for (let key in items) {
            if(count < items.length){
                if(items[key].classList.contains('active')){
                    items[key].classList.remove('active');
                    document.getElementById('toggleBtn').innerHTML = "<i class='fa fa-bars toggle'></i>";
                } else{
                    items[key].classList.add('active');
                    document.getElementById('toggleBtn').innerHTML = "<i class='fa fa-times toggle'></i>";
                }
                count++;
            }
          }
    }
    document.addEventListener('click',function(e){
        if(e.target.classList.contains('toggle')) {
            responsividadNavBar(e);
            }
        });

        const nav = document.querySelectorAll('.nav-link');

        nav.forEach(el => el.addEventListener('click', event => {
            let dataId = event.target.getAttribute("data-id");
            let padreId = document.getElementById('content');
            let subs = padreId.getElementsByClassName('sub-container');
            for(var i = 0; i < subs.length; i++){
                var a = subs[i];
                a.style.display = 'none';
             }
             document.getElementById(dataId).style.display = 'block';
             for (let i = 0; i < forms.length; i++) {
                let controls = forms[i].elements;
                for (let i=0, iLen=controls.length; i<iLen; i++) {
                    if(controls[i].nodeName === 'INPUT'){
                        controls[i].classList.remove('error');
                    }
                }
            }
             event.preventDefault();
        }));
        




});