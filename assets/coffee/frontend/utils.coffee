range = (size, startAt = 0) ->
    return Array.from new Array(size), ( x, i ) => i + startAt;

randomNumber = (min, max) ->
  min = Math.ceil min
  max = Math.floor max
  return Math.floor(Math.random() * (max - min + 1)) + min;

export { range, randomNumber }
