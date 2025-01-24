"use strict";
/**
 * Initializes the countdown timer functionality.
 * Adds an event listener to the button to start the countdown.
 */
document.addEventListener("DOMContentLoaded", () => {
    const timerButton = document.querySelector(".timer button");
    const timerSpan = document.querySelector(".timer span");
    if (timerButton && timerSpan) {
        // Get the initial value from the timer span
        const initialTime = parseTime(timerSpan.textContent || "00:00");
        timerButton.addEventListener("click", () => {
            startCountdown(initialTime, timerSpan);
        });
    }
});
/**
 * Parses a time string in the format "MM:SS" and converts it to seconds.
 *
 * @param timeString - The time string to parse.
 * @returns The total time in seconds.
 */
function parseTime(timeString) {
    const [minutes, seconds] = timeString
        .split(":")
        .map((str) => Number(str !== null && str !== void 0 ? str : 0));
    const validMinutes = minutes !== null && minutes !== void 0 ? minutes : 0;
    const validSeconds = seconds !== null && seconds !== void 0 ? seconds : 0;
    return validMinutes * 60 + validSeconds;
}
/**
 * Starts a countdown timer and updates the display element with the remaining time.
 * When the countdown reaches zero, it displays "00:00" and adds the "show" class
 * to the element with the class "display-time-up".
 *
 * @param duration - The duration of the countdown in seconds.
 * @param display - The HTML element to update with the remaining time.
 */
function startCountdown(duration, display) {
    let timer = duration;
    const interval = setInterval(() => {
        const minutes = Math.floor(timer / 60)
            .toString()
            .padStart(2, "0");
        const seconds = (timer % 60).toString().padStart(2, "0");
        display.textContent = `${minutes}:${seconds}`;
        if (--timer < 0) {
            clearInterval(interval);
            display.textContent = "00:00";
            // Find the element with the class .display-time-up and add the class .show to it
            const timeUpElement = document.querySelector(".display-time-up");
            if (timeUpElement) {
                timeUpElement.classList.add("show");
            }
        }
    }, 1000);
}
//# sourceMappingURL=challenge.js.map