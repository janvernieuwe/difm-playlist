(function () {
    Playlist = function () {
        this.key = $("#key");
        this.station = $("#station");
        this.format = $(":input[name='optionsRadios']:checked");
        this.download = $("#download");
        this.registerHandlers();
    };

    Playlist.prototype.registerHandlers = function () {
        var self = this;
        $(this.download).click(function (e) {
            e.preventDefault();
            var url = '/';
            url += $(self.station).val() + '/';
            url += $(self.key).val() + '.';
            url += $(self.format).val();
            window.location = url;
            return false;
        });
    };
})();
var playlist = null;
$(document).ready(function() {
    playlist = new Playlist();
});