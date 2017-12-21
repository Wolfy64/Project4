// keep track of how many ticket fields have been rendered
var ticketCount = '{{ form.tickets|length }}';

jQuery(document).ready(function () {
    jQuery('#add-another-ticket').click(function (e) {
        e.preventDefault();

        var ticketList = jQuery('#ticket-fields-list');

        // grab the prototype template
        var newWidget = ticketList.attr('data-prototype');
        
        // replace the "__name__" used in the id and name of the prototype
        // with a number that's unique to your emails
        // end name attribute looks like name="contact[emails][2]"
        newWidget = newWidget.replace(/__name__/g, ticketCount);
        ticketCount++;

        // create a new list element and add it to the list
        var newLi = jQuery('<li></li>').html(newWidget);
        newLi.appendTo(ticketList);
    });
})