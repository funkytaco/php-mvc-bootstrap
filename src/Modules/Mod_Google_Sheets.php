<?php
namespace Main\Modules;

    Class Mod_Google_Sheets {

        public function __construct($spreadsheetId) {
            $this->client = new \Google_Client();
            $this->client->setApplicationName('Google Sheets and PHP');
            $this->client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
            $this->client->setAccessType('offline');
            $this->client->setAuthConfig(__DIR__ . '/credentials.json');

            $this->service = new Google_Service_Sheets($client);

            $this->spreadsheetId = $spreadsheetId;
        }
        
        public function get_sheet_rows($get_range =  "A2: B10") {
            $response = $this->service->spreadsheets_values->get($this->spreadsheetId, $get_range);
            return $arrValues; 
        }
        public function update_sheet_rows($update_range =  "A2: B10", $values = [[$arrOne, $arrTwo]]) {

            $body = new Google_Service_Sheets_ValueRange([

                'values' => $values
          
              ]);
          
              $params = [
          
                'valueInputOption' => 'RAW'
          
              ];
            $update_sheet = $this->service->spreadsheets_values->update($this->spreadsheetId, $update_range, $body, $params);
        }

        public function data() {
            return 'data....';
        }
    }