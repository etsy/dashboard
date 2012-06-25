(function($) {
    Time = (function() {
        var TIMES = {
            'PST': { title: 'San Francisco (EST)', offsetMinutes: -480 },
            'EST': { title: 'Brooklyn (EST)', offsetMinutes: -300 },
            // 'GMT': { title: 'London', offsetMinutes: -0 },
            'UTC': { title: 'UTC', offsetMinutes: 0 },
        };

        var interval = null;

        /**
         * Get selected text
         */
        function _getSelection() {
            var t = '';
            if(window.getSelection){
                t = window.getSelection();
            } else if(document.getSelection){
                t = document.getSelection();
            } else if(document.selection){
                t = document.selection.createRange().text;
            }
            return t;
        }

        /**
         * @return boolean
         * Return true if the given dom node is selected
         */
        function _isSelected(selector) {
            var value = $(selector).text();
            if(value && value  == _getSelection()) {
                return true;
            }
            return false;
        }


        /**
         * Update all updateable times
         */
        function _updateTimes(event) {

            var localTime = new Date();

            var utcTime =
                new Date(localTime.getTime() +
                         (localTime.getTimezoneOffset() * 60 * 1000));

            _setTimes(utcTime);
        }

        function _setTimes(utcTime) {

            for(id in TIMES) {
                var time = TIMES[id];

                var date = new Date(utcTime.getTime() +
                                    ( time.offsetMinutes * 60 * 1000));

                // Set Human Readable Time
                if(!_isSelected('#' + id + ' .time.readable')) {
                    var text = moment(date).format('ddd MMM DD, YYYY h:mma');
                    $('#' + id).find('.time.readable')
                        .text(text);
                }

                // Set 24H Readable Time
                if(!_isSelected('#' + id + ' .time.24h')) {
                    var text = moment(date).format('H:mma');
                    $('#' + id).find('.time.24h')
                        .text(text);
                }


                // Set Epoch Seconds 
                if(!_isSelected('#' + id + ' .time.utc_epoch_seconds')) {
                    $('#' + id).find('.time.utc_epoch_seconds')
                        .text(Math.floor(date.getTime()/1000));
                }

                // Set Epoch Millis 
                if(!_isSelected('#' + id + ' .time.epoch_millis')) {
                    $('#' + id).find('.time.epoch_millis')
                        .text(date.getTime());
                }
            }
        };

        function _trackRightNow() {
            // Set the times
            _updateTimes();


            // Update times once a second
            clearInterval(interval)
            interval = setInterval(_updateTimes, 1000);
        }

        function _onChangeSettingsFrom(event) {
            $('.settings_input').hide();
            switch($('#settings_from').val()) {
                case 'now':
                    $.address.queryString('');
                    _trackRightNow();
                    break;
                case 'from_utc_epoch_seconds':
                    $('#settings_from_utc_epoch_seconds').show();
                    break;
                case 'from_utc_date':
                    $('#settings_from_utc_date').show();
                    break;
            }
        }

        function _onChangeInputEpochSeconds(event) {
            $.address.parameter('utc_epoch_seconds', $('#input_utc_epoch_seconds').val());
        }

        function _onChangeAddress(event) {
            if($.address.parameter('utc_epoch_seconds')) {
                clearInterval(interval);
                _setTimes(new Date($.address.parameter('utc_epoch_seconds') * 1000)); 
                $('#settings_from').val('from_utc_epoch_seconds');
                $('#input_utc_epoch_seconds').val($.address.parameter('utc_epoch_seconds'));
                $('#settings_from_utc_epoch_seconds').show();
            }
        }

        function _setupListeners() {
            $('#settings_from').change(_onChangeSettingsFrom);
            $('#input_utc_epoch_seconds').change(_onChangeInputEpochSeconds);
            $.address.change(_onChangeAddress);
        }
                     
        return {
            init: function() {
                _setupListeners();

                var now = new Date();

                $('#local_time').text(moment(now).format('ddd MMM DD, YYYY h:mma'));

                // Create all time boxes
                for(id in TIMES) {
                    var time = TIMES[id];

                    $('#time_section').tmpl({ id: id, title: time.title, offsetHours: time.offsetMinutes/60 })
                        .appendTo('#time_boxes');
                }

                _trackRightNow();
            }
        };

    })().init();

})(jQuery);
 
