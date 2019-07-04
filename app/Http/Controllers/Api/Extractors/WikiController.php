<?php

    namespace App\Http\Controllers\Api\Extractors;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\Api\Extractors\Wiki\InfoboxRequest;
    use App\Services\Extractors\WikiExtractor;
    use Arr;
    use Str;

    class WikiController extends Controller
    {
        public function infobox(WikiExtractor $wikiExtractor, InfoboxRequest $request)
        {
            if ($request->type === 'template') {

                $request->title = Str::start($request->title, 'Template:');

                if ($request->wanted_keys) {
                    $request->wanted_keys = explode('|', $request->wanted_keys);
                }

                $response = $wikiExtractor->getInfoboxArrayFromWikiResponse(
                    $request->title,
                    $request->wanted_keys ?: [],
                    $request->star_split ?: false
                );

                if (!$request->star_split) {
                    return print_r($response, true);
                }

                return implode(PHP_EOL, Arr::flatten($response));

            }

            if ($request->type === 'category') {

                return implode(PHP_EOL, $wikiExtractor->getCategoryList($request->title));

            }
        }
    }
