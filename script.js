let search = document.querySelector('.search-box');

document.querySelector('#search-icon').onclick =()=>{
    search.classList.toggle('active');
    navbar.classList.remove('active');
}

let navbar = document.querySelector('.navbar');

cocument.querySelector('menu-icon').onclick =()=>{
    navbar.classList.toggle('active');
    search.classList.remove('active');  
}
window.conscroll=()=>{
    navbar.classList.remove('active');
    search.classList.remove('active');
}