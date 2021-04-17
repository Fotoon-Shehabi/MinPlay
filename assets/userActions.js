function subscribe(userTo, userFrom, button) {
    if(userTo == userFrom) {
        return;
    }
    $.post("ajax/subscribe.php", { userTo: userTo, userFrom: userFrom })
    .done(function(count) {
        // the subscribe.php script echo's the count of the subscribers to the userTo
        if(count != null) {
            $(button).toggleClass("subscribe unsubscribe"); // takes care of dynamically changing the sub button after pressing

            var buttonText = $(button).hasClass("subscribe") ? "SUBSCRIBE" : "SUBSCRIBED";
            $(button).text(buttonText + " " + count);
        }
        else {
            alert("Something went wrong");
        }
    })
}