class Countdown extends EventTarget {
    constructor() {
        super(); // Call the parent constructor (EventTarget)
        this.time = '00:00';
        this.seconds = 0;
        this.minutes = 0;
        this.interval = null;
    }
    setInitialTime(seconds) {
        this.minutes = Math.floor(seconds / 60);
        this.seconds = seconds % 60;
        this.updateTime();
    }
    start() {
        if (this.interval)
            return; // Prevent multiple intervals from starting
        this.interval = setInterval(() => {
            if (this.seconds > 0) {
                this.seconds--;
            }
            else if (this.minutes > 0) {
                this.minutes--;
                this.seconds = 59;
            }
            else {
                this.pause();
            }
            this.updateTime();
        }, 1000);
    }
    pause() {
        if (this.interval !== null) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }
    updateTime() {
        this.time = `${this.minutes}:${this.seconds < 10 ? '0' : ''}${this.seconds}`;
        console.log(this.time);
        // Dispatch a custom event to notify the update
        const event = new CustomEvent('update-timer', {
            detail: { time: this.time }, // Pass the updated time as event detail
        });
        this.dispatchEvent(event); // Dispatch the event
    }
    state() {
        return this.interval ? 'running' : 'paused';
    }
}
export default Countdown;
//# sourceMappingURL=Countdown.js.map