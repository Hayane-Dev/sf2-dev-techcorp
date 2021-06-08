    $(document).ready(function() {

        var selector = '.navbar-nav li.nav-item a';
        var routes = [
            'tech_corp_front_homepage',
            'tech_corp_front_about'
        ];

        $(selector).each(function(index) {
            // console.log(index + ": " + $(this).attr("id"));
            $(this).on('click', function() {
                window.location.href = Routing.generate(routes[index]);
            });
        });

    });