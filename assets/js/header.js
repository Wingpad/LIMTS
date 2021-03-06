var pagesLocation = "assets/json/pages.json";

function onNavLinkHover() {
    var link = $(this),
        name = link.attr("name");
    link.attr("href", "?page=" + name + location.hash);
}

function onHeaderPagesLoaded(pages) {
    var mainNav = $("#main-nav");
    mainNav.empty();

    $.each(pages, function(i, page) {
        if (page.dropdown) {
            var dropdownElements = [];

            $.each(page.pages, function(i, subPage) {
                dropdownElements.push(
                    $("<li>").append(
                        $("<a>").text(
                            subPage.title
                        ).attr("name", page.name + "-" + subPage.name)
                        .css("cursor", "pointer")
                        .hover(onNavLinkHover)
                    )
                );
            });

            var subElements = [
                $('<a href="#" class="dropdown-toggle" data-toggle="dropdown">' + page.title + ' <b class="caret"></b></a>'),
                $('<ul class="dropdown-menu navmenu-nav" role="menu">').append(dropdownElements)
            ];

            mainNav.append($("<li class=\"dropdown\">").append(subElements));
        } else {
            mainNav.append(
                $("<li>").append(
                    $("<a>").text(
                        page.title
                    ).attr("name", page.name)
                    .css("cursor", "pointer")
                    .hover(onNavLinkHover)
                )
            );
        }
    });

    $(function () {
        var link = $("a[name='"+getParameterByName("page")+"']");
        link.parent().addClass("active");
        link.parents(".dropdown").addClass("open");
    });
}

function setupHeader() {
    return $.ajax({
        dataType: "json",
        url: pagesLocation,
        success: onHeaderPagesLoaded
    });
}
