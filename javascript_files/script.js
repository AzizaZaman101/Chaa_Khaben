let menu= document.querySelector('#menu-bars');
let navbar=document.querySelector('.navbar');

menu.onclick = () =>{
    menu.classList.toggle('fa-times');//cancel sign
    navbar.classList.toggle('active');
}



