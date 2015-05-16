(function () {
    PlaylistController = function () {
        this.element = {
            'listenKey':       ":input[name='listenKey']",
            'type':            ":input[name='type']",
            'checked_type':    ":input[name='type']:checked",
            'premium':         ":input[name='premium']",
            'checked_premium': ":input[name='premium']:checked",
            'permalink':       "#permaLink"
        };
        this.listenKey = '3x4mpl3';
        this.type = 'pls';
        this.premium = true;
        this.updatePermaLink();
        this.registerHandlers();
    };

    PlaylistController.prototype.registerHandlers = function () {
        var self = this;
        $(self.element.listenKey).bind('change keyup', function () {
            self.listenKey = self.filterKey();
            self.updatePermaLink();
        });
        $(self.element.type).bind('change', function () {
            self.type = $(self.element.checked_type).val();
            self.updatePermaLink();
        });
        $(self.element.premium).bind('change', function () {
            self.premium = parseInt($(self.element.checked_premium).val()) === 1;
            self.updatePermaLink();
        });
        $("#generateBtn").click(function () {
            window.location = self.getUri();
        })
    };

    PlaylistController.prototype.getUri = function () {
        var premium = this.premium ? 'premium' : 'public';
        return '/' + premium + '/' + this.listenKey + '.' + this.type;
    };

    PlaylistController.prototype.filterKey = function () {
        var str = $(this.element.listenKey)
            .val();
        str = str.replace(/[^a-z0-9]/g, '');
        return str;
    };

    PlaylistController.prototype.updatePermaLink = function () {
        $(this.element.permalink)
            .attr('href', this.getUri())
            .html(
            window.location.href + this.getUri().substr(1));
    };
})();
