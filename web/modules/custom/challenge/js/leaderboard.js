import Countdown from "./Countdown.js";
function initializeCountdownTimerLeaderboard() {
    const timerSpan = document.querySelector("*[data-timer]");
    if (!timerSpan) {
        return;
    }
    const countdown = new Countdown({ initialTime: 60 });
    countdown.start();
    countdown.addEventListener('update-timer', (e) => {
        const customEvent = e;
        timerSpan.innerText = customEvent.detail.time; // Update the foo element with the new time
        if (customEvent.detail.time === '0:00') {
            location.reload();
        }
    });
}
document.addEventListener("DOMContentLoaded", () => {
    initializeCountdownTimerLeaderboard();
});
//# sourceMappingURL=leaderboard.js.map