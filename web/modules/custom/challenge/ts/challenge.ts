import Countdown from './Countdown.js';

// JSConfetti is an external globally available class imported through a CDN so make sure TypeScript knows about it.
declare var JSConfetti: any;

/**
 * Initializes the countdown timer functionality.
 * Adds an event listener to the button to start the countdown.
 */
function initializeCountdownTimer(): void {

  const timerButtons = document.querySelectorAll(
    "button[data-timer-button]"
  ) as NodeListOf<HTMLButtonElement>;

  const timerElement = document.querySelector("*[data-timer]") as HTMLSpanElement;

  if(!timerElement) {
    return;
  }

  // Create a new Countdown instance with the initial time
  const countdown = new Countdown({
    initialTime : parseTime(timerElement.textContent || "00:00")
  });

  // Listen for the custom 'update-timer' event and update the foo element's innerText
  countdown.addEventListener('update-timer', (e: Event) => {
    const customEvent = e as CustomEvent;
    timerElement.innerText = customEvent.detail.time; // Update the foo element with the new time
  });

  timerButtons.forEach((button) => {

    const buttonType = button.dataset['timerButton'];

    button.addEventListener("click", () => {

      if(buttonType === 'start') {
        countdown.start();
      }

      if(buttonType === 'pause') {
        countdown.pause();
      }

      if(buttonType === 'reset') {
        countdown.reset();
      }

      if (button.parentElement) {
        button.parentElement.dataset['state'] = countdown.state();
      }
    });

  });
}

/**
 * Initializes the show names functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'name'.
 */
function initializeShowNames(): void {
  const showNameButton = document.querySelector(
    "button.show-names"
  ) as HTMLButtonElement;

  if (showNameButton) {
    showNameButton.addEventListener("click", showNames);
  }
}

/**
 * Initializes the show Contact functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'Contact'.
 */
function initializeShowContact(): void {
  const showContactButton = document.querySelector(
    "button.show-contact"
  ) as HTMLButtonElement;

  if (showContactButton) {
    showContactButton.addEventListener("click", showContact);
  }
}

/**
 * Initializes the show placement functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'name'.
 */
function initializeShowPlacement(): void {
  const showPlacementButton = document.querySelector(
    "button.show-placement"
  ) as HTMLButtonElement;

  if (showPlacementButton) {
    showPlacementButton.addEventListener("click", showPlacement);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  initializeCountdownTimer();
  initializeShowNames();
  initializeShowContact();
  initializeShowPlacement();
  // Initialize all delete buttons
  initDeleteButtons();
});

/**
 * Parses a time string in the format "MM:SS" and converts it to seconds.
 *
 * @param timeString - The time string to parse.
 * @returns The total time in seconds.
 */
function parseTime(timeString: string): number {
  const [minutes, seconds] = timeString
    .split(":")
    .map((str) => Number(str ?? 0));
  const validMinutes = minutes ?? 0;
  const validSeconds = seconds ?? 0;
  return validMinutes * 60 + validSeconds;
}

/**
 * Toggles the 'show' class on all elements with the class 'name' and updates the button text.
 *
 * This function selects all elements with the class 'name' and toggles
 * the 'show' class on each of them. It also toggles the text of the button
 * with the class 'show-names' between "Show Names" and "Hide Names".
 */
function showNames(): void {
  const names: NodeListOf<HTMLElement> = document.querySelectorAll(".name");
  const showNameButton = document.querySelector(
    "button.show-names"
  ) as HTMLButtonElement;

  names.forEach((name) => {
    name.classList.toggle("show");
  });

  // Toggle the button text
  if (showNameButton) {
    if (showNameButton.textContent === "Show Names") {
      showNameButton.textContent = "Hide Names";
    } else {
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
function showContact(): void {
  const contact: NodeListOf<HTMLElement> =
    document.querySelectorAll(".contact");
  const showContactButton = document.querySelector(
    "button.show-contact"
  ) as HTMLButtonElement;

  contact.forEach((contact) => {
    contact.classList.toggle("show");
  });

  // Toggle the button text
  if (showContactButton) {
    if (showContactButton.textContent === "Show contact information") {
      showContactButton.textContent = "Hide contact information";
    } else {
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
function showPlacement(): void {
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
      element: document.querySelector(".place-3") as HTMLElement,
      buttonText: "Show 3. place",
      confettiColors: bronzeConfettiColors,
      confettiNumber: 500,
    },
    {
      element: document.querySelector(".place-2") as HTMLElement,
      buttonText: "Show 2. place",
      confettiColors: silverConfettiColors,
      confettiNumber: 1000,
    },
    {
      element: document.querySelector(".place-1") as HTMLElement,
      buttonText: "Show 1. place",
      confettiColors: goldConfettiColors,
      confettiNumber: 1500,
    },
  ];

  const showPlacementButton = document.querySelector(
    "button.show-placement"
  ) as HTMLButtonElement;

  for (const placement of placements) {
    if (showPlacementButton.textContent === placement.buttonText) {
      placement.element.classList.add("show");
      showPlacementButton.textContent =
        placements[placements.indexOf(placement) + 1]?.buttonText || "";
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

/**
 * Initialize delete buttons for submissions and votes.
 */
function initDeleteButtons(): void {
  document.querySelectorAll('[data-delete]').forEach(button => {
    button.addEventListener('click', deleteEntities);
  });
}

/**
 * Handles deletion of submissions or votes.
 */
function deleteEntities(event: Event): void {
  event.preventDefault();

  const target = event.target as HTMLButtonElement;
  const type: string | undefined = target.dataset['delete']; // 'submissions' or 'votes'
  const challengeId: string | undefined = target.dataset['challengeId'];

  if (!type || !challengeId) {
    console.error('Missing data attributes.');
    return;
  }

  if (!confirm(`Are you sure you want to delete all ${type}?`)) {
    return;
  }

  fetch(`/challenge/${type}/${challengeId}/delete`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({})
  })
  .then(response => response.json())
  .then(data => {
    alert(data.message);
    if (data.success) location.reload();
  })
  .catch(console.error);
}
