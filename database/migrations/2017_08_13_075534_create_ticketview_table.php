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
                        CREATE VIEW ticketview AS
                        
                        SELECT 
                            t.id,
                            t.created_at AS 'date',
                            t.ticketname AS 'title',
                            t.details,
                            t.tickettype,
                            CONCAT('Item: ',i.propertynumber) AS 'tag' ,                      
                            CONCAT(u.firstname,' ',u.lastname) AS 'staffassigned',
                            t.staffassigned AS staff_id,
                            t.author,
                            t.status
                        FROM ticket AS t 
                            JOIN item_ticket AS it
                            ON it.ticket_id = t.id
                            JOIN itemprofile AS i
                            ON i.id = it.item_id
                            JOIN inventory AS inv
                            ON i.inventory_id = inv.id
                            JOIN itemtype AS itype
                            ON inv.itemtype_id = itype.id
                            JOIN user AS u
                            ON u.id =t.staffassigned
                            WHERE itype.name != 'System Unit'
                        
                        UNION
                        
                        SELECT 
                            t.id,
                            t.created_at AS 'date',
                            t.ticketname AS 'title',
                            t.details,
                            t.tickettype,
                            CONCAT('PC: ',ip.propertynumber) AS 'tag' ,                      
                            CONCAT(u.firstname,' ',u.lastname) AS 'staffassigned',
                            t.staffassigned AS staff_id,
                            t.author,
                            t.status
                        FROM ticket AS t 
                            JOIN pc_ticket AS pt
                            ON pt.ticket_id = t.id
                            JOIN pc AS p
                            ON p.id = pt.pc_id  
                            JOIN itemprofile AS ip
                            ON ip.id = p.systemunit_id
                            JOIN user AS u
                            ON u.id =t.staffassigned 
                        
                        UNION

                        SELECT 
                            t.id,
                            t.created_at AS 'date',
                            t.ticketname AS 'title',
                            t.details,
                            t.tickettype,
                            CONCAT('Room: ',r.name) AS 'tag' ,                      
                            CONCAT(u.firstname,' ',u.lastname) AS 'staffassigned',
                            t.staffassigned AS staff_id,
                            t.author,
                            t.status
                        FROM ticket AS t 
                            JOIN room_ticket AS rt
                            ON rt.ticket_id = t.id
                            JOIN room AS r
                            ON r.id = rt.room_id
                            JOIN user AS u
                            ON u.id =t.staffassigned
                        
                        UNION

                        SELECT 
                            t.id,
                            t.created_at AS 'date',
                            t.ticketname AS 'title',
                            t.details,
                            t.tickettype,
                            'None' AS 'tag' ,                      
                            CONCAT(u.firstname,' ',u.lastname) AS 'staffassigned',
                            t.staffassigned AS staff_id,
                            t.author,
                            t.status
                        FROM ticket AS t 
                        JOIN user AS u
                        ON u.id =t.staffassigned
                        WHERE t.id not in ( 
                            select ticket_id 
                            from room_ticket
                            union
                            select ticket_id 
                            from item_ticket
                            union
                            select ticket_id 
                            from pc_ticket
                         )
                        
                            ;");
	}
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("DROP VIEW ticketview ");
	}

}
