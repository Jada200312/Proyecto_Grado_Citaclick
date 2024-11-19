document.addEventListener('DOMContentLoaded', function () { 
    const navbarLinks = document.querySelectorAll('.navbar_link');
   
    navbarLinks.forEach(link => {
        link.addEventListener('click', () => {
            navbarLinks.forEach(navLink => navLink.classList.remove('active'));
            link.classList.add('active');
        });
    });
});