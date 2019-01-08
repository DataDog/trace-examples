<?php


class HomeController extends BaseController
{
    public function showWelcome()
    {
        /** @var \Illuminate\Http\Request $request */
        return View::make('hello');
    }

    public function distributedTracing()
    {
        $ch = curl_init("http://127.0.0.1/distributed-tracing-backend");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseText = curl_exec($ch);

        curl_close($ch);

        return "This is frontend - $responseText";
    }

    public function distributedTracingBackend()
    {
        return "This is backend";
    }

    public function actionThatExits()
    {
        $this->someCustomRequestHandler();
        exit(0);
    }

    private function someCustomRequestHandler()
    {
        echo "echoed";
    }
}
