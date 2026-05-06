function rot13(str) {
    return (str + '').replace(/[a-z]/gi, function(s) {
        return String.fromCharCode(s.charCodeAt(0) + (s.toLowerCase() < 'n' ? 13 : -13));
    });
}

function linkAction(e) {
    console.log(rot13(e.getAttribute("data-href")));
    window.location = rot13(e.getAttribute("data-href"));
}

function closestDataHref( e ) {
    if( e.getAttribute('data-href') ) {
        return e;
    }
    if(e.parentNode.nodeName != 'BODY') //Last possibility! There's no parent behind!
        return e.parentNode && closestDataHref( e.parentNode );
    return null;
}

document.addEventListener('click', function (event) {
    let pseudoLink = closestDataHref(event.target);
    if (pseudoLink) {
        linkAction(pseudoLink);
        return false;
    }
});