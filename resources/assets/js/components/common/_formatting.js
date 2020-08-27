export default function formatDuration (duration, showSeconds = true) {
    const time = duration.toString().split('.')[0]

    if (showSeconds) {
        return time
    } else {
        const parts = time.split(':')
        return `${parts[0]}:${parts[1]}`
    }
}
