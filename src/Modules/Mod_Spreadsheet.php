<?php

//requires "phpoffice/phpspreadsheet": "^1.23" in Composer
namespace Main\Modules;
// third party
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    Class Mod_Spreadsheet {

        public function __construct($spreadsheetId = '') {
           
            $this->spreadsheetId = $spreadsheetId;
            $this->arrOne = ['one'];
            $this->arrTwo = ['two'];
            $this->arrValues = [];
        }


        private function centerVerticalHorizontal($spreadsheet, $logo, $rowNumber, $columnChar){
            $rowHeight = $spreadsheet->getActiveSheet()->getRowDimension($rowNumber)->getRowHeight();
            $columnWidth = $spreadsheet->getActiveSheet()->getColumnDimension($columnChar)->getWidth();
        
            $result = [
                'offsetY' => $rowHeight - $logo->getHeight(),
                'offsetX' => $columnWidth + $logo->getWidth() * 2
            ];
        
            return $result;
        }

        private function formatActiveSheetColumnsAndRows($spreadsheet) {
            //$sheet->getDefaultRowDimension()->setRowHeight(15);
            $spreadsheet->getActiveSheet()->getDefaultRowDimension()->setRowHeight(30);
            //foreach (range('A', 'I') as $col) {            
            //    $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
            //}
            $spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(25);


            //or set custom dimensions...
            /*** $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
            $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25); 
            ***/
        }


        public function save() {
            $header = array(
                'A1' => 'Data in A1',
                'B1' => 'B1 Data',
                'C1' => 'C1 Data?',
                'D1' => 'D1 Data!',
                'E1' => 'Е1...',
                'F1' => 'F1...',
                'G1' => 'G1.....',
                'H1' => 'H1....',
                'I1' => 'I1...'
            );

            for ($i=0;$i<3;$i++) {
                $currentSheet = $i+1;
                $arrHeader[$i] = array(
                    'A1' => 'Data in A1 (Sheet'. $currentSheet .')',
                    'B1' => 'B1 Data (Sheet'. $currentSheet .')',
                    'C1' => 'C1 Data? (Sheet'. $currentSheet .')',
                    'D1' => 'D1 Data! (Sheet'. $currentSheet .')',
                    'E1' => 'Е1...(Sheet'. $currentSheet .')',
                    'F1' => 'F1... (Sheet'. $currentSheet .')',
                    'G1' => 'G1..... (Sheet'. $currentSheet .')',
                    'H1' => 'H1....(Sheet'. $currentSheet .')',
                    'I1' => 'I1... (Sheet'. $currentSheet .')'
                );
            }
            $spreadsheet1 = new Spreadsheet();
            // Set document properties
            $spreadsheet1->getProperties()
                ->setCreator('Luis Gonzalez')
                ->setLastModifiedBy('Luis Gonzalez')
                ->setTitle('SDF Dashboard')
                ->setSubject('SDF Dashboard Subject')
                ->setDescription('Test document for SDF Dashboard')
                ->setKeywords('SDF Dashboard')
                ->setCategory('SDF');


            $spreadsheet2 = new Spreadsheet();
            $spreadsheet3 = new Spreadsheet();

   
    
            $spreadsheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet2, 'Sheet2');
            $spreadsheet2->fromArray(
                $arrHeader[1],  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            );
            $spreadsheet3 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet3, 'Sheet3');
            $spreadsheet3->fromArray(
                $arrHeader[2],  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            );



            //$spreadsheet1->setTitle("Sheet1");
            $spreadsheet1->addSheet($spreadsheet2, 0);
            $spreadsheet1->addSheet($spreadsheet3, 0);
            $sheet1 = $spreadsheet1->getActiveSheet();
            $sheet1->setTitle("Sheet1");
            $sheet1->fromArray(
                $arrHeader[0],  // The data to set
                NULL,        // Array values with this value will not be set
                'A1'         // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
            );
    
            // Create Logo
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath('logo.png');
            //$drawing->setHeight(32);
            $drawing->setWorksheet($spreadsheet3);
            $drawing->setCoordinates('A2');
            //$drawing->setOffsetY($this->centerVerticalHorizontal($spreadsheet1, $drawing, 2, 'A')['offsetY']);
            //$drawing->setOffsetX($this->centerVerticalHorizontal($spreadsheet1, $drawing, 2, 'A')['offsetX']);


            //Let's format the 3 sheets to have wider rows
            $spreadsheet1->setActiveSheetIndex(2);
            $this->formatActiveSheetColumnsAndRows($spreadsheet1);
            $spreadsheet1->setActiveSheetIndex(0);
            $this->formatActiveSheetColumnsAndRows($spreadsheet1);
            $spreadsheet1->setActiveSheetIndex(1);
            $this->formatActiveSheetColumnsAndRows($spreadsheet1);


            $writer = new Xlsx($spreadsheet1);
            //$writer->save('hello world.xlsx');
    
            $fileName = 'hello-world.xlsx';
            //header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            //header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            //$writer->save('php://output');
            $writer->save($fileName);
        }

        public function update_sheet_rows($update_range= "A2: B10") {
           // return $update_sheet;
        }

        public function get_sheet_rows($get_range =  "A2: B10") {
            return $arrValues; 
        }

    }