interface CountdownInterface {
    timer: HTMLElement | null;
    seconds: number;
    minutes: number;
    interval: number | null;
}
declare const Countdown: CountdownInterface & {
    init(): void;
};
export default Countdown;
