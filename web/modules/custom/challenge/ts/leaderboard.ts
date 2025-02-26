setInterval(function () {
  location.reload();
}, 61000); // 61000 milliseconds = 1 minute and 1 second

function initializeCountdownTimerLeaderboard(): void {
  const timerSpan = document.querySelector(".timer span") as HTMLSpanElement;

  if (timerSpan) {
    startCountdown(parseTime("1"), timerSpan);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  initializeCountdownTimerLeaderboard();
});
