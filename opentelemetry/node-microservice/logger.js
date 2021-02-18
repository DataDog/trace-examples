'use strict'
// eslint-disable-next-line
const otel = require('@opentelemetry/api');
const winston = require('winston')
const UINT_MAX = 4294967296

// Convert a buffer to a numerical string.
function toNumberString (buffer, radix) {
  let high = readInt32(buffer, 0)
  let low = readInt32(buffer, 4)
  let str = ''

  radix = radix || 10

  while (1) {
    const mod = (high % radix) * UINT_MAX + low

    high = Math.floor(high / radix)
    low = Math.floor(mod / radix)
    str = (mod % radix).toString(radix) + str

    if (!high && !low) break
  }

  return str
}

// Convert a numerical string to a buffer using the specified radix.
function fromString (str, raddix) {
  const buffer = new Uint8Array(8)
  const len = str.length

  let pos = 0
  let high = 0
  let low = 0

  if (str[0] === '-') pos++

  const sign = pos

  while (pos < len) {
    const chr = parseInt(str[pos++], raddix)

    if (!(chr >= 0)) break // NaN

    low = low * raddix + chr
    high = high * raddix + Math.floor(low / UINT_MAX)
    low %= UINT_MAX
  }

  if (sign) {
    high = ~high

    if (low) {
      low = UINT_MAX - low
    } else {
      high++
    }
  }

  writeUInt32BE(buffer, high, 0)
  writeUInt32BE(buffer, low, 4)

  return buffer
}

// Write unsigned integer bytes to a buffer.
function writeUInt32BE (buffer, value, offset) {
  buffer[3 + offset] = value & 255
  value = value >> 8
  buffer[2 + offset] = value & 255
  value = value >> 8
  buffer[1 + offset] = value & 255
  value = value >> 8
  buffer[0 + offset] = value & 255
}

// Read a buffer to unsigned integer bytes.
function readInt32 (buffer, offset) {
  return (buffer[offset + 0] * 16777216) +
    (buffer[offset + 1] << 16) +
    (buffer[offset + 2] << 8) +
    buffer[offset + 3]
}

const tracingFormat = function () {
  return winston.format(info => {
    const span = otel.getSpan(otel.context.active());
    if (span) {
      // convert to dd with:
      // https://github.com/DataDog/dd-trace-js/blob/master/packages/dd-trace/src/id.js
      const context = span.context();
      const traceIdEnd = context.traceId.slice(context.traceId.length / 2)
      info['dd.trace_id'] = toNumberString(fromString(traceIdEnd,16))
      info['dd.span_id'] = toNumberString(fromString(context.spanId,16))
    }
    return info;
  })();
}

module.exports = winston.createLogger({
  transports: [new winston.transports.Console],
  format: winston.format.combine(tracingFormat(), winston.format.json())
});
