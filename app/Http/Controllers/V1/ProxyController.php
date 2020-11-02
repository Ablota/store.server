<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ProxyController extends Controller {
	public function source(Request $request) {
		$request->validate(
			[
				'url' => 'required|url',
			]
		);

		$path = $this->getFile($request->get('url'), 1);

		$jarsigner = shell_exec(escapeshellarg(config('app.paths.jarsigner')) . ' -verify ' . escapeshellarg($path));
		abort_if(strpos($jarsigner, 'jar verified.') === false, 500, "The verification of the jar file failed.");

		$keytool = shell_exec(escapeshellarg(config('app.paths.keytool')) . ' -printcert -jarfile ' . escapeshellarg($path));
		preg_match("/\b[A-Fa-f0-9:]{95}\b/", $keytool, $fingerprints);
		abort_if(count($fingerprints) !== 1, 500, "No or multiple code signer certificates found in jar file.");

		$jar = new ZipArchive();
		$jar->open($path);

		$index = $jar->getFromName('index-v1.json');
		abort_if($index === false, 500, "No index found in jar file.");

		$source = json_decode($index);
		abort_if(empty($source), 500, "Invalid JSON content in index file.");
		$source->fingerprint = strtolower(str_replace(':', '', $fingerprints[0]));

		$jar->close();

		return response()->json($source);
	}

	private function getFile($url, $expiration) {
		$path = 'proxy/' . hash('sha256', $url);

		if(!Storage::exists($path) || Storage::lastModified($path) < time() - $expiration * 60 * 60) {
			$client = new Client(
				[
					RequestOptions::READ_TIMEOUT => 90,
					RequestOptions::TIMEOUT => 90,
				]
			);

			try {
				$response = $client->get($url);

				Storage::put($path, $response->getBody());
			} catch(BadResponseException $e) {
				abort($e->getResponse()->getStatusCode(), $e->getResponse()->getReasonPhrase());
			} catch(ConnectException | RequestException $e) {
				abort(504, $e->getMessage());
			}
		}

		return storage_path('app/' . $path);
	}

	public function asset(Request $request) {
		$request->validate(
			[
				'url' => 'required|url',
			]
		);

		return response()->file($this->getFile($request->get('url'), 24));
	}
}
