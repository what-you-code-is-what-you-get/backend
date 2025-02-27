declare var JSConfetti: any;
/**
 * Initializes the countdown timer functionality.
 * Adds an event listener to the button to start the countdown.
 */
declare function initializeCountdownTimer(): void;
/**
 * Initializes the show names functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'name'.
 */
declare function initializeShowNames(): void;
/**
 * Initializes the show Contact functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'Contact'.
 */
declare function initializeShowContact(): void;
/**
 * Initializes the show placement functionality.
 * Adds an event listener to the button to toggle the visibility of elements with the class 'name'.
 */
declare function initializeShowPlacement(): void;
/**
 * Parses a time string in the format "MM:SS" and converts it to seconds.
 *
 * @param timeString - The time string to parse.
 * @returns The total time in seconds.
 */
declare function parseTime(timeString: string): number;
/**
 * Starts a countdown timer and updates the display element with the remaining time.
 * When the countdown reaches zero, it displays "00:00" and adds the "show" class
 * to the element with the class "display-time-up".
 *
 * @param duration - The duration of the countdown in seconds.
 * @param display - The HTML element to update with the remaining time.
 */
declare function startCountdown(duration: number, display: HTMLSpanElement): void;
/**
 * Toggles the 'show' class on all elements with the class 'name' and updates the button text.
 *
 * This function selects all elements with the class 'name' and toggles
 * the 'show' class on each of them. It also toggles the text of the button
 * with the class 'show-names' between "Show Names" and "Hide Names".
 */
declare function showNames(): void;
/**
 * Toggles the 'show' class on all elements with the class 'Contact' and updates the button text.
 *
 * This function selects all elements with the class 'Contact' and toggles
 * the 'show' class on each of them. It also toggles the text of the button
 * with the class 'show-Contacts' between "Show Contacts" and "Hide Contacts".
 */
declare function showContact(): void;
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
declare function showPlacement(): void;
/**
 * Initialize delete buttons for submissions and votes.
 */
declare function initDeleteButtons(): void;
/**
 * Handles deletion of submissions or votes.
 */
declare function deleteEntities(event: Event): void;
