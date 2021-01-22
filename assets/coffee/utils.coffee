range = (size, startAt = 0) =>
    return Array.from new Array(size), ( x, i ) => i + startAt;

export { range }
