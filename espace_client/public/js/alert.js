function timeOut(elementToSelect, time) {
    var flashMsg = document.getElementById(elementToSelect);
    if (flashMsg != null) {
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