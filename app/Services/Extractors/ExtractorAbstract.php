<?php

    namespace App\Services\Extractors;

    use Exception;
    use GuzzleHttp\Client;
    use GuzzleHttp\Exception\GuzzleException;

    abstract class ExtractorAbstract
    {
        protected $client;

        public function __construct(Client $client)
        {
            $this->client = $client;
        }

        /**
         * Try to get an XML response.
         *
         * @param string $uri_key
         * @param string $id
         *
         * @return array
         * @throws GuzzleException
         */
        public function tryGetXmlResponse(string $uri_key, string $id = ''): array
        {
            try {
                $request = $this->client->request(
                    'GET',
                    str_replace('{id}', urlencode($id), $this->uris[$uri_key]),
                    ['http_errors' => false]
                );

                if ($request->getStatusCode() === 200) {
                    return $this->xmlToArray($request->getBody());
                }

                return [];

            } catch (Exception $exception) {
                return [];
            }
        }

        /**
         * Convert an XML string to an array.
         *
         * @param string $string String of text
         * @return array
         */
        public function xmlToArray(string $string): array
        {
            $xml = simplexml_load_string($string);
            $json = json_encode($xml);

            return json_decode($json, true);
        }

        /**
         * Try to get a JSON response.
         *
         * @param string $uri_key
         * @param string $id
         *
         * @return array
         * @throws GuzzleException
         */
        public function tryGetJsonResponse(string $uri_key, string $id = ''): array
        {
            try {
                $request = $this->client->request(
                    'GET',
                    str_replace('{id}', urlencode($id), $this->uris[$uri_key]),
                    ['http_errors' => false]
                );

                if ($request->getStatusCode() === 200) {
                    return json_decode($request->getBody(), true);
                }

                return [];

            } catch (Exception $exception) {
                return [];
            }
        }


        /**
         * Try to get a response.
         *
         * @param string $uri_key
         * @param string $id
         *
         * @return mixed
         * @throws GuzzleException
         */
        public function tryGetResponse(string $uri_key, string $id = '')
        {
            try {
                $request = $this->client->request(
                    'GET',
                    str_replace('{id}', urlencode($id), $this->uris[$uri_key]),
                    ['http_errors' => false]
                );

                if ($request->getStatusCode() === 200) {
                    return $request->getBody();
                }

                return null;

            } catch (Exception $exception) {
                return null;
            }
        }

    }