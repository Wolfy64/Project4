var $collectionHolder;

// setup an "add a tag" link
var $addTagLink = $('<a href="#" class="add_tag_link">Add a ticket</a>');
var $newDiv = $('<div></div>').append($addTagLink);

jQuery(document).ready(function () {
    // Get the div that holds the collection of tags
    $collectionHolder = $('.tickets');

    // add the "add a tag" anchor and li to the tags div
    $collectionHolder.append($newDiv);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newDiv);

        // ============ TEST CLONE ============
        // $('.ticket').clone().insertAfter('.tickets');
        // $('.ticket .input').each(function (i) {
        //     $('.ticket').clone().insertAfter('.tickets');
        //     $(this).attr('reservation_tickets_'+i+'_guest_firstName', i + 1);
        // });
        // $('.ticket').replace(/__name__/g, 'BB');
        // $("#ticket").clone().insertAfter("#ticket-fields-list")
        // ============ TEST CLONE ============
    });

    function addTagForm($collectionHolder, $newDiv) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        var newForm = prototype;
        // You need this only if you didn't set 'label' => false in your tags field in TaskType
        // Replace '__name__label__' in the prototype's HTML to
        // instead be a number based on how many items we have
        // newForm = newForm.replace(/__name__label__/g, index);

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on how many items we have
        newForm = newForm.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an div, before the "Add a tag" link div
        var $newFormDiv = $('<div class="toto"></div>').append(newForm);
        $newDiv.before($newFormDiv);
    }
});