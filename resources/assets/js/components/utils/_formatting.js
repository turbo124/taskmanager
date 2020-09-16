export default function formatDuration (duration, showSeconds = false) {
    // const time = duration.toString().split('.')[0]

    if (showSeconds) {
        return time
    } else {
        console.log('time', duration)
        const parts = duration.toString().split('.')
        return `${zeroPad(parts[0], 2)}:${zeroPad(parts[1], 2)}`
    }
}

export function zeroPad (num, places) {
    var zero = places - num.toString().length + 1
    return Array(+(zero > 0 && zero)).join('0') + num
}
