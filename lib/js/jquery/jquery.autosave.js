/**
 * jQuery Plugin Autosave
 *
 * @author Raymond Julin (raymond[dot]julin[at]gmail[dot]com)
 * @author Mads Erik Forberg (mads[at]hardware[dot]no)
 * @author Simen Graaten (simen[at]hardware[dot]no)
 *
 * Licensed under the MIT License
 *
 * Usage:
 * $("input.autosave").autosave({
 *     url: url, // Defaults to parent form url or window.location.href
 *     method: "post",  // Defaults to parent form url or get
 *     grouped: true, // Defaults to false. States whether all selected fields should be sent in the request or only the one it was triggered upon
 *     success: function(data) {
 *         console.log(data);
 *     },
 *     send: function(eventTriggeredByNode) {
 *         // Do stuff while we wait for the ajax response, defaults to doing nothing
 *         console.log("Saving");
 *     },
 *     error: function(xmlReq, text, errorThrown) {
 *         // Handler if the ajax request fails, defaults to console.log-ing the ajax request scope
 *         console.log(text);
 *     },
 *     dataType: "json" // Defaults to JSON, but can be XML, HTML and so on
 * });
 *
 * $("form#myForm").autosave(); // Submits entire form each time one of the
 *                              // elements are changed, except buttons and submits
 *
 *
 * Todo:
 * - Support timed autosave for textareas
 */

(function($) {

    $.fn.autosave = function(options) {

        /**
         * Define some needed variables
         * elems is a shortcut for the selected nodes
         * nodes is another shortcut for elems later (wtf)
         * eventName will be used to set what event to connect to
         */
        var elems = $(this), nodes = $(this), eventName;

        options = $.extend({
            grouped: false,
            send: false, // Callback
            error: false, // Callback
            success: false, // Callback
            dataType: "json" // From ajax return point
        }, options);

        /**
         * If the root form is used as selector
         * bind to its submit and find all its
         * input fields and bind to them
         */
        if ($(this).is('form')) {
            /* Group all inputelements in this form */
            options.grouped = true;
            elems = nodes = $(this).find(":input,button");
            // Bind to forms submit
            $(this).bind('submit', function(e) {
                e.preventDefault();
                $.fn.autosave._makeRequest(e, nodes, options, $(this));
            });
        }
        /**
         * For each element selected (typically a list of form elements
         * that may, or may not, reside in the same form
         * Build a list of these nodes and bind them to some
         * onchange/onblur events for submitting
         */
        elems.each(function(i) {
            eventName = $(this).is('button,:submit') ? 'click' : 'change';
            $(this).bind(eventName, function (e) {
                eventName == 'click' ? e.preventDefault() : false;
                $.fn.autosave._makeRequest(e, nodes, options, this);
            });
        });
        return $(this);
    }

    /**
     * Actually make the http request
     * using previously supplied data
     */
    $.fn.autosave._makeRequest = function(e, nodes, options, actsOn) {
        // Keep variables from global scope
        var vals = {}, form;
        /**
         * Further set default options that require
         * to actually inspect what node autosave was triggered upon
         * Defaults:
         *  -method: post
         *  -url: Will default to parent form if one is found,
         *        if not it will use the current location
         */
        form = $(actsOn).is('form') ? $(actsOn) : $(actsOn.form);
        options = $.extend({
            url: (form.attr('action'))? form.attr('action') : window.location.href,
            method: (form.attr('method')) ? form.attr('method') : "post"
        }, options);

        /**
         * If options.grouped is true we collect every
         * value from every node
         * But if its false we should only push
         * the one element we are acting on
         */
        if (options.grouped) {
            nodes.each(function (i) {
                /**
                 * Do not include button and input:submit as nodes to
                 * send, EXCEPT if the button/submit was the explicit
                 * target, aka it was clicked
                 */
                if (!$(this).is('button,:submit') || e.currentTarget == this) {
                    if ($(this).is(':radio') && $(this).attr('checked')==false)
                        return;
                    vals[this.name] = $(this).is(':checkbox') ?
                        $(this).attr('checked') :
                        $(this).val();
                }
            });
        }
        else {
            vals[actsOn.name] = $(actsOn).is(':checkbox') ?
                $(actsOn).attr('checked') :
                $(actsOn).val();
        }

        /* Create it's own function - autosave_visual_on */
        //if (window.console) { console.log('visualizer.autosave.ajax'); }
       	//$("#autosave_icon").css("width", "12px");
       	//$("#autosave_icon").css("margin-top", "0");
		//$('input[type="image"]').attr("src", "/images/autosave.gif");
		//$("#autosave_icon").css("background-image", "url(images/autosave.gif)");
		$("#autosave_icon").css("display", "block");
        /**
         * Perform http request and trigger callbacks respectively
         */
        // Callback triggered when ajax sending starts
        options.send ? options.send($(actsOn)) : false;
        $.ajax({
            type: options.method,
            data: vals,
            url: options.url,
            dataType: options.dataType,
            success: function(resp) {
                options.success ? options.success(resp) : false;
            },
            error: function(resp) {
                options.error ? options.error(resp) : false;
            }
        });

    /* Create it's own function - autosave_visual_off */
    //if (window.console) { console.log('visualizer.autosave.timer'); }
    window.setInterval(function() {
   	//$("#autosave_icon").css("width", "24px");
	//$('input[type="image"]').attr("src", "/images/icon_save.png");
	//$("#autosave_icon").css("margin-top", "0");
	$("#autosave_icon").css("display", "none");
	//$("#autosave_icon").css("background-image", "none");
	}, 1000);
	$("#entry_form_last_modified").html('Edited ' + getTime()); // make this part of the auto_save_visual function
	$("#entry_form_last_modified").css('color', '#B3B3B3');

    }

})(jQuery);

/**
 * A default (example) of a visualizer you can use that will
 * put a neat loading image in the nearest <legend>
 * for the element/form you were autosaving.
 * Notice: No default "remover" of this spinner exists
 */
defaultAutosaveSendVisualizer = function(node) {
    var refNode;
    if (node.is('form'))
        refNode = $(node).find('legend');
    else
        refNode = $(node).parent('fieldset').find('legend');
    // Create spinner
    var spinner = $('<img src="/images/spin.gif" />').css({
        'position':'relative',
        'margin-left':'10px',
        'height': refNode.height(),
        'width': refNode.height()
    });
    spinner.appendTo(refNode);
}

getTime = function() {
			var currentTime = new Date()
			var monthNumber = currentTime.getMonth() + 1
			var day = currentTime.getDate()
			var year = currentTime.getFullYear()
			var hours = currentTime.getHours()
			var minutes = currentTime.getMinutes()
			var seconds = currentTime.getSeconds()
			var ampm;
			var time;
			var date;
			var tz;

            var month = new Array(12);
            month[0] = "January";
            month[1] = "February";
            month[2] = "March";
            month[3] = "April";
            month[4] = "May";
            month[5] = "June";
            month[6] = "July";
            month[7] = "August";
            month[8] = "September";
            month[9] = "October";
            month[10] = "November";
            month[11] = "December";
            var monthName = month[currentTime.getMonth()];

            ampm = (hours >= 12)? 'pm' : 'am';
            hours = ((hours + 11) % 12 + 1);


            if (minutes < 10){
			minutes = "0" + minutes
			}
			if (seconds < 10){
			seconds = "0" + seconds
			}

            /*
			if(hours > 11){
			ampm = "PM";
			hours = hours - 12;
			} else {
			ampm = "AM";
			}

			if(hours == 0){
			hours = 12;
			}
            */

			//date = month + "/" + day + "/" + year + " ";
            //time = date + "@ " + hours + ":" + minutes + ":" + seconds + " " + ampm + " " + tz;
			/* time = hours + ":" + minutes + ":" + seconds + " " + ampm + " " + tz; */
            date = monthName + " " + day + " " + hours + ":" + minutes + " " + ampm

			return(date);
}