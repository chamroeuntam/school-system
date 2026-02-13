<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;

class GoogleSheetsClient
{
    public function make(): Sheets
    {
        $client = new Client();
        $client->setApplicationName('School System');
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig(config('services.google.service_account_json'));

        return new Sheets($client);
    }

    public function readTab(string $sheetId, string $tabName): array
    {
        $service = $this->make();
        $resp = $service->spreadsheets_values->get($sheetId, $tabName);
        return $resp->getValues() ?? [];
    }

    public function writeTab(string $sheetId, string $tabName, array $values): void
    {
        $service = $this->make();
        $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);
        $service->spreadsheets_values->update($sheetId, $tabName, $body, ['valueInputOption' => 'RAW']);
    }
}
