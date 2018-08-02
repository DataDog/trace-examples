# Sqlite Tracing Example
This application demonstrates DataDog APM tracing with a Sqlite database.

This example application creates a Sqlite database and a simple table in that database.  Inserts 100 records into that table and then queries the records from the table and prints the data to stdout.  The trace spans the full execution of the example application.

## To Run
1. Make sure that you have the Trace Agent setup and configured with your key.
2. From this directory run `go build`
3. From this directory run `./go-sqlite3`
4. Go do the DataDog website and under the APM menu click on `Traces`. It will take a few seconds but you should see a trace for `sqlite-example` show up at the top of the Traces.