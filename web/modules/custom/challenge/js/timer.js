"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const Countdown = {
    timer: null,
    seconds: 0,
    minutes: 0,
    interval: null,
    init() {
        this.timer = document.querySelector('.timer');
        this.timer.innerHTML = '00:00';
        this.seconds = 0;
        this.minutes = 0;
        this.interval = null;
    },
    start() {
        this.interval = setInterval(() => {
            this.seconds++;
            if (this.seconds >= 60) {
                this.seconds = 0;
                this.minutes++;
            }
            const display = `${this.minutes < 10 ? '0' : ''}${this.minutes}:${this.seconds < 10 ? '0' : ''}${this.seconds}`;
            this.timer.innerHTML = display;
        }, 1000);
    }
};
exports.default = Countdown;
//# sourceMappingURL=timer.js.map