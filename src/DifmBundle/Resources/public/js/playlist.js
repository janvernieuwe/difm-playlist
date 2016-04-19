(function () {
    Playlist = function () {
        this.key = $("#key");
        this.station = $("#station");
        this.format = $(":input[name='optionsRadios']:checked");
        this.download = $("#download");
        this.quality = $("#quality");
        this.registerHandlers();
    };

    Playlist.prototype.registerHandlers = function () {
        var self = this;
        $(this.download).click(function (e) {
            e.preventDefault();
            var url = '/';
            url += $(self.station).val() + '/';
            url += $(self.quality).val() + '/';
            url += $(self.key).val() + '.';
            url += $(self.format).val();
            window.location = url;
            return false;
        });

        $(this.station).change(function () {
            var station = $(self.station).val();
            var hq = $("#quality > option[value='320']");
            if(station === 'difm') {
                $(hq).removeAttr('disabled');
            }else {
                $(hq).attr('disabled', 'disabled');
                $(self.quality).val('128');
            }
        });
    };
})();
var playlist = null;
$(document).ready(function () {
    playlist = new Playlist();
});