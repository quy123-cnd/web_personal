const nav_link = document.querySelectorAll('.nav-link');

nav_link.forEach(the =>{
    the.addEventListener('click', ()=>{
        nav_link.forEach(item => item.classList.remove('active'));

        the.classList.add('active')
    })
})