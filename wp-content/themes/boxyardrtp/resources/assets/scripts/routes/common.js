export default {
  init() {
    // Show topbar nav
    function showTopbarNav() {
      $('body').addClass('topbarnav-active');
      $('#topbar-menu-trigger + label i').attr('aria-label', 'Hide navigation menu');

      // Enable focus of nav items using tabindex
      $('.topbar-menu').each(function() {
        var el = $(this);
        $('a', el).attr('tabindex', '0');
      });
    }

    // Hide topbar nav
    function hideTopbarNav() {
      $('body').removeClass('topbarnav-active');
      $('#topbar-menu-trigger + label i').attr('aria-label', 'Show navigation menu');

      // Disable focus of nav items using tabindex
      $('.topbar-menu').each(function() {
        var el = $(this);
        $('a', el).attr('tabindex', '-1');
      });
    }

    // Toggle topbar nav
    $('#topbar-menu-trigger').on('change focusout', function() {
      if ($(this).prop('checked')) {
        showTopbarNav();
      } else {
        hideTopbarNav();
      }
    });

    // Only show topbar nav if an element inside is receiving focus
    $('.topbar-menu').each(function () {
      var el = $(this);

      $('a', el).on('focus', function() {
        $(this).parents('li').addClass('hover');
      }).on('focusout', function() {
        $(this).parents('li').removeClass('hover');

        var smDown = window.matchMedia( '(max-width: 768px)' );
        if (smDown.matches) {
          setTimeout(function () {
            if ($(':focus').closest('#menu-top-nav').length == 0) {
              $('#topbar-menu-trigger').prop('checked', false);
              hideTopbarNav();
            }
          }, 200);
        }
      });
    });
  },
  finalize() {
    // JavaScript to be fired on all pages, after the init JS
  },
};
