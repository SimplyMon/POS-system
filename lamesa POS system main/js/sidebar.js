var sideBarIsOpen = true;
hamburger_button.addEventListener('click', (event) => {
    event.preventDefault();


    if (sideBarIsOpen) {

        dashboard_sidebar.style.width = "8%";
        dashboard_mainbar.style.width = '92%';
        logo_dash.style.height = '45px';
        logo_dashs.style.padding = '15px 0 15px 25px'
        hamburger.style.padding = '0 0 40px 0'
        dashboard_sidebar_list.style.padding = '0 10px 0 10px'



        MenuIcons = document.getElementsByClassName('Menutext');
        for (var i = 0; i < MenuIcons.length; i++) {
            MenuIcons[i].style.display = 'none';
        }
        console.log(MenuIcons);


        title_dash = document.getElementsByClassName('title_dash');
        for (var i = 0; i < title_dash.length; i++) {
            title_dash[i].style.display = 'none';
        }
        console.log(title_dash);
        sideBarIsOpen = false;
    } else {

        dashboard_sidebar.style.width = "15%";
        dashboard_mainbar.style.width = '85%';
        dashboard_sidebar.style.transition = 'all 0.5s ease';
        dashboard_mainbar.style.transition = 'all 0.5s ease';
        logo_dash.style.height = '45px';
        logo_dash.style.transition = 'all 0.5s ease';
        logo_dashs.style.padding = '15px 0 15px 0';
        hamburger.style.justifyContent = 'center';
        dashboard_sidebar_list.style.padding = '0 0 0 0'


        MenuIcons = document.getElementsByClassName('Menutext');
        for (var i = 0; i < MenuIcons.length; i++) {
            MenuIcons[i].style.display = 'inline-flex'
        }
        console.log(MenuIcons);


        title_dash = document.getElementsByClassName('title_dash');
        for (var i = 0; i < title_dash.length; i++) {
            title_dash[i].style.display = '';
        }
        console.log(title_dash);
        sideBarIsOpen = true;
    }
});

// validation to delete
document.addEventListener("DOMContentLoaded", function() {
    var deleteLinks = document.querySelectorAll('.delete-link');
    deleteLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            var id = this.getAttribute('data-id');
            if (confirm("Are you sure you want to delete this data?")) {
                window.location.href = "delete.php?id=" + id;
            }
        });
    });
});


