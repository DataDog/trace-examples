# Usage

Assuming you have `ddtrace-run` installed, run example for `json_log_formatter`:

``` sh
pip install json_log_formatter
DD_LOGS_INJECTION=true ddtrace-run python example_jlf.py
```

For `structlog`:

``` sh
pip install structlog
DD_LOGS_INJECTION=true ddtrace-run python example_structlog.py
```
