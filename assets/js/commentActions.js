function postComment(button, postedBy, videoId, responseTo, containerClass) {
    var textarea = $(button).siblings("textarea");
    var commentText = textarea.val();
    textarea.val("");

    if(commentText) {
        $.post("ajax/postComment.php", { commentText: commentText,
             postedBy: postedBy, videoId: videoId, responseTo: responseTo })
        .done(function(comment) {

            if(!replyTo) {
                $("."+containerClass).prepend(comment);
            }
            else {
                // if this is a reply to a comment we add it to the child container
                $(button).parent().siblings("." + containerClass).appned(comment);
            }
            
        });
    }
    else {
        alert("You can't post an empty comment!");
    }
}
function toggleReply(button) {
    var parent = $(button).closest(".itemContainer"); // go up in the DOM and find the first item with itemContainer class
    var commentForm = parent.find(".commentForm").first(); // go down the DOM
    commentForm.toggleClass("hidden");
}
function likeComment(commentId, button, videoId) {
    $.post("ajax/likeComment.php", {commentId: commentId, videoId, videoId})
    .done(function (numToChange) {
        //JQUERY
        var likeB = $(button);
        var dislikeB = $(button).siblings(".dislikeButton");
            
        likeB.addClass("active");
        dislikeB.removeClass("active");

        var likesCount = $(button).siblings(".likesCount");
        updateLikes(likesCount, numToChange);

        if(numToChange < 0) {
            likeB.removeClass("active");
            likeB.find("img:first").attr("src", "assets/images/icons/thumb-up.png");
        }
        else {
            likeB.find("img:first").attr("src", "assets/images/icons/thumb-up-active.png")
        }
        dislikeB.find("img:first").attr("src", "assets/images/icons/thumb-down.png")
    });
}
function dislikeComment(commentId, button, videoId) {
    $.post("ajax/dislikeComment.php", {commentId: commentId, videoId, videoId})
    .done(function (numToChange) {
        //JQUERY
        var dislikeB = $(button);
        var likeB = $(button).siblings(".likeButton");
            
        dislikeB.addClass("active");
        likeB.removeClass("active");

        var likesCount = $(button).siblings(".likesCount");
        updateLikes(likesCount, numToChange);

        if(numToChange > 0) {
            dislikeB.removeClass("active");
            dislikeB.find("img:first").attr("src", "assets/images/icons/thumb-down.png");
        }
        else {
            dislikeB.find("img:first").attr("src", "assets/images/icons/thumb-down-active.png")
        }
        likeB.find("img:first").attr("src", "assets/images/icons/thumb-up.png")
    });
}
function updateLikes(element, num) {
    var likesCount = element.text() || 0;
    element.text(parseInt(likesCount) + parseInt(num))

}
function getReplies(commentId, button, videoId) {
    $.post("ajax/getCommentReplies.php", { commentId: commentId, videoId: videoId })
    .done(function(comments) {
        var replies = $("<div>").addClass("repliesSection");
        replies.append(comments);
        $(button).replaceWith(replies);
    })
}