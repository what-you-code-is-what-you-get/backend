declare class Countdown extends EventTarget {
    time: string;
    seconds: number;
    minutes: number;
    interval: ReturnType<typeof setInterval> | null;
    constructor();
    setInitialTime(seconds: number): void;
    start(): void;
    pause(): void;
    updateTime(): void;
    state(): "running" | "paused";
}
export default Countdown;
