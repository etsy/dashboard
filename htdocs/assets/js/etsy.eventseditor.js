var Etsy = Etsy || {};

Etsy.EventEditor = function() {
    this.edit_buttons = $('.event .edit-event-button');
    this.edit_event_boxes = $('.edit-event');
    this.event_info_boxes = $('.event-info');
    this.cancel_edit_buttons = $('.edit_event_form a');
    this.init();
};

Etsy.EventEditor.prototype = {
    init : function() {
        this.bindEditHover();
        this.bindEditButtons();
        this.bindCancelEditButtons();
        this.edit_window_open = false;
    },

    bindEditHover : function() {
        this.event_info_boxes.hover(function() {
            $(this).children('.edit-event-button').show();
        }, function() {
            $(this).children('.edit-event-button').hide();
        });
    },

    bindEditButtons : function() {
        var self = this;
        this.edit_buttons.click(function() {
            self.edit_event_boxes.hide();
            self.event_info_boxes.show();
            $(this).parent().siblings().show();
            self.edit_window_open = true;
        });
    },

    bindCancelEditButtons : function() {
        var self = this;
        this.cancel_edit_buttons.click(function(evt) {
            evt.preventDefault();
            self.edit_event_boxes.hide();
        });
    }
};

$(function() {
    var eventEditor = new Etsy.EventEditor();
});
