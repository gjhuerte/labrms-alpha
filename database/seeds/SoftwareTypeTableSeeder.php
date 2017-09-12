<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\SoftwareType;
class SoftwareTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     DB::table('softwaretype')->delete();
   	//insert some dummy records
   	SoftwareType::insert(array(
       ['type' => 'Word processing Software'],
       //MS Word, WordPad and Notepad
       ['type' => 'Database Software'],
       //Oracle, MS Access etc
       ['type' => 'Spreadsheet Software'],
       //Apple Numbers, Microsoft Excel
       ['type' => 'Multimedia Software'],
       //Real Player, Media Player
       ['type' => 'Presentation Software'],
       //Microsoft Power Point, Keynotes
       ['type' => 'Enterprise Software'],
       //Customer relationship management system
       ['type' => 'Information Worker Software'],
       //Documentation tools, resource management tools
       ['type' => 'Educational Software'],
       //Dictionaries: Encarta, BritannicaMathematical: MATLABOthers: Google Earth, NASA World Wind
       ['type' => 'Simulation Software'],
       //Flight and scientific simulators
       ['type' => 'Content Access Software'],
       //Accessing content through media players, web browsers
       ['type' => 'Application Suites'],
       //OpenOffice, Microsoft Office
       ['type' => 'Engineering and Product Development Software'],
       //IDE or Integrated Development Environments
    ));
   	
    }	
	
	
	
	
	
	
	
	
	
	
	
}
