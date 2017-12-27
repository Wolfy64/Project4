
var $index = 1;

$removeTag = '<a href = "#" class="remove_tag_link"><i class="fa fa-user-times fa-2x" aria-hidden="true"></i> </a>';

jQuery(document).ready(function () {

    $('#add_tag_link').on('click', function (e) {
        e.preventDefault();

        var $ticket = $('.ticket').html();
        var $newTicket = $ticket.replace(/reservation_tickets_0/g, 'reservation_tickets_' + $index)
            .replace(/reservation\[tickets]\[0/g, 'reservation[tickets][' + $index)
            .replace(/Ticket/g, 'Ticket ' + ($index + 1));
        $('.tickets').last().append('<div class=ticket-' + $index + '></div>');
        $('.ticket-' + $index).append($newTicket).addClass("del-tkt");

        $index++;
    });

    $('#remove_tag_link').on('click', function (e) {
        e.preventDefault();

        $('.del-tkt:last-child').remove();

        $index--;
    });
});