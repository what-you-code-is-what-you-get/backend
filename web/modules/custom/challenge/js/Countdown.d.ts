declare class Countdown extends EventTarget {
    time: string;
    seconds: number;
    minutes: number;
    initialTime: number;
    interval: ReturnType<typeof setInterval> | null;
    constructor({ initialTime }: {
        initialTime: number;
    });
    setInitialTime(seconds: number): void;
    start(): void;
    pause(): void;
    reset(): void;
    updateTime(): void;
    state(): "running" | "paused";
}
export default Countdown;
