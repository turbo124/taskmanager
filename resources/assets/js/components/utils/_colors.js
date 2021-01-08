export function convertHexStringToColor (value) {
    if (value == null) {
        return null
    }
    value = value.replaceAll('#', '')
    if (value.length !== 6) {
        return null
    }
    try {
        return parseInt(value, 16) + 0xFF000000
    } catch (e) {
        return null
    }
}
