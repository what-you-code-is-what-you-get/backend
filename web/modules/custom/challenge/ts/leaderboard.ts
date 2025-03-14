import Countdown from "./Countdown.js";

function initializeCountdownTimerLeaderboard(): void {
  const timerSpan = document.querySelector("*[data-timer]") as HTMLSpanElement;

  if(!timerSpan) {
    return;
  }

  const countdown = new Countdown({ initialTime: 60 });
  countdown.start();

  countdown.addEventListener('update-timer', (e: Event) => {
    const customEvent = e as CustomEvent;
    timerSpan.innerText = customEvent.detail.time; // Update the foo element with the new time

    if(customEvent.detail.time === '0:00') {
      location.reload();
    }
  });

}

document.addEventListener("DOMContentLoaded", () => {
  initializeCountdownTimerLeaderboard();
});
