var state = null;

$(function () {
    $('.ajax').on('click', 'a', function () {
        if ($(this).attr('href')) {
            if ($(this).attr('href').charAt(0) == '#') {
                return true;
            }

            if ($(this).hasClass('noajax')) {
                return true;
            }

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                success: function (result) {
                    $('.container-fluid').html(
                        $(result).find('.container-fluid').children()
                    );

                    maruti.init();
                }
            });

            window.history.pushState(url, null, url);
            window.addEventListener('popstate', function (event) {
                state = event.state;
                console.log('State: ' + state);
                if (state != null && state != 'null') {
                    loadPage(state);
                }
            });

            return false;
        }

        return true;
    })
});