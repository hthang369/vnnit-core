/**
 * Open file manager and return selected files.
 * Promise is never resolved if window is closed.
 *
 * @returns Promise<array> Array of selected files with properties:
 *      icon        string
 *      is_file     bool
 *      is_image    bool
 *      name        string
 *      thumb_url   string|null
 *      time        int
 *      url         string
 */
 window.filemanager = function filemanager() {
    var url = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '/filemanager';
    var target = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'FileManager';
    var features = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 'width=900,height=600';
    return new Promise(function (resolve) {
        window.open(url, target, features);
        window.SetUrl = resolve;
    });
};

$.fn.fab = function (options) {
    var menu = this;
    menu.addClass('fab-wrapper');

    var toggler = $('<a>')
      .addClass('fab-button fab-toggle')
      .append($('<i>').addClass('fa fa-plus'))
      .click(function () {
        menu.toggleClass('fab-expand');
      });

    menu.append(toggler);

    options.buttons.forEach(function (button) {
      toggler.before(
        $('<a>').addClass('fab-button fab-action')
          .attr('data-label', button.label)
          .attr('id', button.attrs.id)
          .append($('<i>').addClass(button.icon))
          .click(function () {
            menu.removeClass('fab-expand');
          })
      );
    });
};

$(document).ready(function () {
    $('#fab').fab({
      buttons: [
        {
          icon: 'fa fa-upload',
          label: lang['nav-upload'],
          attrs: {id: 'upload'}
        },
        {
          icon: 'fa fa-folder',
          label: lang['nav-new'],
          attrs: {id: 'add-folder'}
        }
      ]
    });
});
