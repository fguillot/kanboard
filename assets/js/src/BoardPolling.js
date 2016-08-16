Kanboard.BoardPolling = function(app) {
    this.app = app;
};

Kanboard.BoardPolling.prototype.execute = function() {
    if (this.app.hasId("board-container")) {
        var interval = parseInt($("table.board-project").attr("data-check-interval"));
        if (interval > 0) {
            window.setInterval(this.check.bind(this), interval * 1000);
        }
    }
};

Kanboard.BoardPolling.prototype.check = function() {
    if (this.app.isVisible() && !this.app.get("BoardDragAndDrop").savingInProgress) {
        var self = this;
        this.app.showLoadingIcon();
        // Poll every board
        $("table.board-project").each(function() {
          var boardid = $(this).attr("data-project-id");
          var url = $(this).attr("data-check-url");
            $.ajax({
                cache: false,
                url: url,
                statusCode: {
                    200: function(data) {
                        self.app.get("BoardDragAndDrop").refresh(boardid,data);
                    },
                    304: function () {
                        // TODO: Hide loading Icon after all boards have been checked.
                        self.app.hideLoadingIcon();
                    }
                }
            });
        });
    }
};
