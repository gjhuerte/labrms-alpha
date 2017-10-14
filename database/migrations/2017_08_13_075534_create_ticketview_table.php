<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
            CREATE VIEW ticket_v
            AS 
            SELECT 
                ticket.id,
                ticket.created_at AS 'date',
                ticket.ticketname AS 'title',
                ticket.details,
                ticket.tickettype,
                CONCAT( 'Item: ' , itemprofile.propertynumber ) AS 'tag' , 
                itemprofile.propertynumber AS 'link', 
                itemprofile.id AS 'link_id',    
                CONCAT( user.firstname , ' ' , user.lastname ) AS 'staffassigned',
                ticket.staffassigned AS staff_id,
                ticket.author,
                ticket.status
            FROM ticket
                JOIN item_ticket
                    ON item_ticket.ticket_id = ticket.id
                JOIN itemprofile AS itemprofile
                    ON itemprofile.id = item_ticket.item_id
                JOIN inventory
                    ON itemprofile.inventory_id = inventory.id
                JOIN itemtype
                    ON inventory.itemtype_id = itemtype.id
                JOIN user
                    ON user.id = ticket.staffassigned
                WHERE itemtype.name != 'System Unit'
            UNION
            SELECT 
                ticket.id,
                ticket.created_at AS 'date',
                ticket.ticketname AS 'title',
                ticket.details,
                ticket.tickettype,
                CONCAT( 'PC: ' , itemprofile.propertynumber ) AS 'tag' ,  
                itemprofile.propertynumber AS 'link', 
                itemprofile.id AS 'link_id',                       
                CONCAT( user.firstname , ' ' , user.lastname ) AS 'staffassigned',
                ticket.staffassigned AS staff_id,
                ticket.author,
                ticket.status
            FROM ticket
            LEFT JOIN user
                ON user.id = ticket.staffassigned
            JOIN pc_ticket
                ON pc_ticket.ticket_id = ticket.id
            JOIN pc
                ON pc.id = pc_ticket.pc_id  
            JOIN itemprofile
                ON itemprofile.id = pc.systemunit_id
            UNION
            SELECT 
                ticket.id,
                ticket.created_at AS 'date',
                ticket.ticketname AS 'title',
                ticket.details,
                ticket.tickettype,
                CONCAT( 'Room: ' , room.name ) AS 'tag' ,                   
                room.name AS 'link',   
                room.id AS 'link_id',    
                CONCAT( user.firstname , ' ' , user.lastname ) AS 'staffassigned',
                ticket.staffassigned AS staff_id,
                ticket.author,
                ticket.status
            FROM ticket
            LEFT JOIN user
                ON user.id = ticket.staffassigned
            JOIN room_ticket
            ON room_ticket.ticket_id = ticket.id
            JOIN room
            ON room.id = room_ticket.room_id
            UNION
            SELECT 
                ticket.id,
                ticket.created_at AS 'date',
                ticket.ticketname AS 'title',
                ticket.details,
                ticket.tickettype,
                'None' AS 'tag' ,   
                'None' AS 'link',      
                'None' AS 'link_id',                   
                CONCAT( user.firstname , ' ' , user.lastname ) AS 'staffassigned',
                ticket.staffassigned AS staff_id,
                ticket.author,
                ticket.status
            FROM ticket 
            LEFT JOIN user
            ON user.id = ticket.staffassigned
            WHERE ticket.id not in ( 
                select ticket_id 
                from room_ticket
                union
                select ticket_id 
                from item_ticket
                union
                select ticket_id 
                from pc_ticket
             ) 

        ");
	}
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("DROP VIEW IF EXISTS ticket_v");
	}

}
