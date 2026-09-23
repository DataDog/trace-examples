'use strict'
// eslint-disable-next-line
const otel = require('@opentelemetry/api');
const winston = require('winston')

const tracingFormat = function () {
  return winston.format(info => {
    const span = otel.getSpan(otel.context.active());
    if (span) {
      const { spanId, traceId } = span.spanContext();
      const traceIdEnd = traceId.slice(traceId.length / 2);
      info['dd.trace_id'] = BigInt(`0x${traceIdEnd}`).toString();
      info['dd.span_id'] = BigInt(`0x${spanId}`).toString();
    }
    return info;
  })();
}

module.exports = winston.createLogger({
  transports: [new winston.transports.Console],
  format: winston.format.combine(tracingFormat(), winston.format.json())
});
