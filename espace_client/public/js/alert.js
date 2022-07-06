function timeOut(elementToSelect, time) {
    var flashMsg = document.getElementsByClassName(elementToSelect);
    if (flashMsg) {
        setTimeout(function () {
                flashMsg.style.transition = "opacity " + 3 + "s";
                flashMsg.style.opacity = 0;
                flashMsg.addEventListener("transitionend", function () {
                    console.log("transition has ended, set display: none;");
                    flashMsg.style.display = "none";
                });
            }, time
        );
    }
}