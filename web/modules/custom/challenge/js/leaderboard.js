"use strict";
setInterval(function () {
    location.reload();
}, 61000); // 61000 milliseconds = 1 minute and 1 second
function initializeCountdownTimerLeaderboard() {
    const timerSpan = document.querySelector(".timer span");
    if (timerSpan) {
        startCountdown(parseTime("1"), timerSpan);
    }
}
document.addEventListener("DOMContentLoaded", () => {
    initializeCountdownTimerLeaderboard();
});
//# sourceMappingURL=leaderboard.js.map