export function plural(once) {
    return once[once.length - 1] == 'y' ? (once.slice(0, -1) + 'ies') : (once + 's');
}