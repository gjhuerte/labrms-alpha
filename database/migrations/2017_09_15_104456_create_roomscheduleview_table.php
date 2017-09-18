<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomscheduleviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE VIEW IF NOT EXISTS roomschedule_v
            AS 
            SELECT 
            roomschedule.id as `id`,
            room.name as `room`,
            roomschedule.room_id as `room_id`,
            roomschedule.faculty as `faculty_id`, 
            CONCAT(`user`.lastname,', ',`user`.firstname,`user`.middlename) as `faculty`,
            roomschedule.academicyear as `academicyear`,
            roomschedule.semester as `semester`,
            roomschedule.timein as `timein`,
            roomschedule.day as `day`,
            roomschedule.timeout as `timeout`,
            roomschedule.subject as `subject`,
            roomschedule.section as `section`,
            semester.datestart as `semesterstart`,
            semester.dateend as `semesterend`
            FROM roomschedule 
            inner join semester
            on roomschedule.semester = semester.semester 
            and roomschedule.academicyear = semester.academicyear
            left join `user`
            on roomschedule.faculty = `user`.id
            left join room
            on roomschedule.room_id = room.id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS roomschedule_v;");
    }
}
