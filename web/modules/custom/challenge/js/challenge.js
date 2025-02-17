"use strict";
/**
 * Initializes the countdown timer functionality.
 * Adds an event listener to the button to start the countdown.
 */
function initializeCountdownTimer() {
    const timerButton = document.querySelector(".timer button");
    const timerSpan = document.querySelector(".timer span");
    if (timerButton && timerSpan) {
        // Get the initial value from the timer span
        const initialTime = parseTime(timerSpan.textContent || "00:00");
        timerButton.addEventListener("click", () => {
            startCountdown(initialTime, timerSpan);
        });
    }
}
/**
 * Initializes the show names functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'name'.
 */
function initializeShowNames() {
    const showNameButton = document.querySelector("button.show-names");
    if (showNameButton) {
        showNameButton.addEventListener("click", showNames);
    }
}
/**
 * Initializes the show Contact functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'Contact'.
 */
function initializeShowContact() {
    const showContactButton = document.querySelector("button.show-contact");
    if (showContactButton) {
        showContactButton.addEventListener("click", showContact);
    }
}
/**
 * Initializes the show placement functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'name'.
 */
function initializeShowPlacement() {
    const showPlacementButton = document.querySelector("button.show-placement");
    if (showPlacementButton) {
        showPlacementButton.addEventListener("click", showPlacement);
    }
}
document.addEventListener("DOMContentLoaded", () => {
    initializeCountdownTimer();
    initializeShowNames();
    initializeShowContact();
    initializeShowPlacement();
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
/**
 * Toggles the 'show' class on all elements with the class 'name' and updates the button text.
 *
 * This function selects all elements with the class 'name' and toggles
 * the 'show' class on each of them. It also toggles the text of the button
 * with the class 'show-names' between "Show Names" and "Hide Names".
 */
function showNames() {
    const names = document.querySelectorAll(".name");
    const showNameButton = document.querySelector("button.show-names");
    names.forEach((name) => {
        name.classList.toggle("show");
    });
    // Toggle the button text
    if (showNameButton) {
        if (showNameButton.textContent === "Show Names") {
            showNameButton.textContent = "Hide Names";
        }
        else {
            showNameButton.textContent = "Show Names";
        }
    }
}
/**
 * Toggles the 'show' class on all elements with the class 'Contact' and updates the button text.
 *
 * This function selects all elements with the class 'Contact' and toggles
 * the 'show' class on each of them. It also toggles the text of the button
 * with the class 'show-Contacts' between "Show Contacts" and "Hide Contacts".
 */
function showContact() {
    const contact = document.querySelectorAll(".contact");
    const showContactButton = document.querySelector("button.show-contact");
    contact.forEach((contact) => {
        contact.classList.toggle("show");
    });
    // Toggle the button text
    if (showContactButton) {
        if (showContactButton.textContent === "Show contact information") {
            showContactButton.textContent = "Hide contact information";
        }
        else {
            showContactButton.textContent = "Show contact information";
        }
    }
}
/**
 * Displays the placement and triggers confetti animations.
 *
 * This function shows the placement of the top 3 positions by updating the DOM elements
 * and triggering confetti animations. It uses different confetti colors for each placement
 * (bronze, silver, and gold) and triggers additional confetti bursts every 2 seconds for
 * 14 seconds when the first place is shown.
 *
 * The function expects the button with the class "show-placement" to have text content
 * indicating which placement to show next ("Show 3. place", "Show 2. place", "Show 1. place").
 * When the first place is shown, the button is hidden.
 */
function showPlacement() {
    var _a;
    const jsConfetti = new JSConfetti();
    const confettiColors = [
        "#eeeeee",
        "#79fe9d",
        "#1d43c6",
        "#f9a86f",
        "#f9ead4",
    ];
    const goldConfettiColors = [
        "#FAFAD2",
        "#EEE8AA",
        "#F0E68C",
        "#DAA520",
        "#FFD700",
        "#FFA500",
        "#FFFFFF",
    ];
    const silverConfettiColors = [
        "#D3D3D3",
        "#C0C0C0",
        "#A9A9A9",
        "#808080",
        "#FFFFFF",
    ];
    const bronzeConfettiColors = [
        "#ce8946",
        "#fca956",
        "#82572c",
        "#593c1e",
        "#DDAC7D",
    ];
    const intervalTime = 2000; // 2 seconds
    const totalTime = 14000; // 14 seconds
    const placements = [
        {
            element: document.querySelector(".place-3"),
            buttonText: "Show 3. place",
            confettiColors: bronzeConfettiColors,
            confettiNumber: 500,
        },
        {
            element: document.querySelector(".place-2"),
            buttonText: "Show 2. place",
            confettiColors: silverConfettiColors,
            confettiNumber: 1000,
        },
        {
            element: document.querySelector(".place-1"),
            buttonText: "Show 1. place",
            confettiColors: goldConfettiColors,
            confettiNumber: 1500,
        },
    ];
    const showPlacementButton = document.querySelector("button.show-placement");
    for (const placement of placements) {
        if (showPlacementButton.textContent === placement.buttonText) {
            placement.element.classList.add("show");
            showPlacementButton.textContent =
                ((_a = placements[placements.indexOf(placement) + 1]) === null || _a === void 0 ? void 0 : _a.buttonText) || "";
            jsConfetti.addConfetti({
                confettiColors: placement.confettiColors,
                confettiNumber: placement.confettiNumber,
            });
            if (placement.buttonText === "Show 1. place") {
                showPlacementButton.classList.add("hide");
                const intervalId = setInterval(() => {
                    jsConfetti.addConfetti({
                        confettiColors: confettiColors,
                        confettiNumber: 500,
                    });
                }, intervalTime);
                setTimeout(() => {
                    clearInterval(intervalId);
                }, totalTime);
            }
            break;
        }
    }
}
//# sourceMappingURL=challenge.js.map