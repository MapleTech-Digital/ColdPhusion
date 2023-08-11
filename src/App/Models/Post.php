<?php
/**
 * Badge Class
 * Author: Kyle Harrison <helloky@protonmail.com>
 */

namespace App\Models;

use Core\Database\DBOM\ActiveRecord;

class Post extends ActiveRecord
{
    protected static string $table = "posts";
    protected static string $id_field = "id";
    protected static array $ignored_fields = ['date_added', 'date_updated'];

    public ?int $id = null;
    public ?string $name = null;
    public ?string $slug = null;
    public ?string $display = null;
    public ?string $icon = null;
    public ?string $link = null;
    public ?string $description = null;
    public ?DateTime $date_added = null;
    public ?DateTime $date_updated = null;
    public ?int $pro_only = null;
    public ?int $nonsale = null;
    public ?int $featured = null;
    public ?string $creator = null;


    public function __construct() { parent::__construct(); }

    public function getDisplay()
    {
        return $this->display ? $this->display : $this->name;
    }

    public function getIcon()
    {
        global $system;
        return "{$system['system_uploads']}/{$this->icon}";
    }


    public function getShopInstance()
    {
        // if this id exists, try to get the existing badge shop
        if($this->id)
        {
            try
            {
                return BadgeShop::Get($this->id);
            } catch(\Exception $e) {}
        }

        // failing that create a new one, but dont save here, this is just a shell
        return BadgeShop::CreateFromArgs([
            'badge_id' => $this->id,
            'price' => 0.00,
            'active' => 0


        ]);
    }

    /**
     * Assign a user
     */
    public function promoteUser($userid)
    {
        // check user isn't already assigned
        $userBadges = Badge::UserBadges($userid);
        foreach($userBadges as $ub)
        {
            if($ub->getID() == $this->getID())
            {
                return null; // Do nothing, already assigned
            }
        }

        $sql = vsprintf("INSERT INTO users_badges (user, badge, do_display) VALUES (%s, %s, %s)",
            [
                secure($userid, 'int', false),
                secure($this->getID(), 'int', false),
                0
            ]);

        $query = $this->db->query($sql) or _error("SQL_ERROR", $this->db->error);
    }

    /**
     * Unassign a user
     */
    public function demoteUser($userid)
    {
        // we can indiscriminately delete, sql wont complain if records werent found
        $sql = vsprintf("DELETE FROM users_badges WHERE user = %s AND badge = %s",
            [
                secure($userid, 'int', false),
                secure($this->getID(), 'int', false),
            ]);

        $query = $this->db->query($sql) or _error("SQL_ERROR", $this->db->error);
    }



    public function awardUser($userid)
    {
        // we can indiscriminately delete, sql wont complain if records werent found
        $sql = vsprintf("UPDATE users SET user_wallet_balance = user_wallet_balance + 1 WHERE user = %s",
            [
                secure($userid, 'int', false),
            ]);

        $query = $this->db->query($sql) or _error("SQL_ERROR", $this->db->error);
    }

    /**
     * Changes the display of this badge for a specific user
     */
    public function setUserDisplay($userid, $doDisplay)
    {
        $sql = vsprintf(
            "UPDATE users_badges SET do_display = %s WHERE user = %s AND badge = %s",
            [ $doDisplay ? 1 : 0, secure($userid, 'int', false), secure($this->getID(), 'int', false) ]);

        $query = $this->db->query($sql) or _error("SQL_ERROR");
    }

    /*
    * Get all users associated with this badge
    */
    public function getAssigned($count = false)
    {
        // Bounce if this record doens't exist yet
        if(!$this->getID())
            return 0;

        $reflectors = "u.*";
        if($count)
            $reflectors = "count(*) as total";

        $sql = vsprintf(
            "select %s 
            from users_badges ub 
            left join users u on ub.user = u.user_id 
            where ub.`badge` = %s",
            [ $reflectors, $this->getID() ]);

        $query = $this->db->query($sql) or _error("SQL_ERROR");

        if($count)
        {
            $row = $query->fetch_assoc();
            return $row['total'];
        }

        $items = [];
        while($row = $query->fetch_assoc())
        {
            $items[] = $row;
        }

        return $items;
    }

    /**
     * Gets all Badge instances associated with any user id
     */
    public static function UserBadges($userid, $onlyShown = false)
    {
        global $db;
        $items = [];

        $conditions = "";
        $_conditions = ['ub.`user` = %s'];
        $_values = [secure($userid, 'int', false)];
        if($onlyShown)
        {
            $_conditions[] = 'ub.do_display = %s';
            $_values[] = secure(1, 'int', false);
        }
        if(count($_conditions))
        {
            $conditions = " AND " . implode(" AND ", $_conditions);
        }

        // This one uses an association table, so the standard List interface wont work here
        $sql = vsprintf("
            SELECT
                b.*, ub.do_display
            FROM
                users_badges ub
                LEFT JOIN badges b ON b.id = ub.badge 
            WHERE
                1 = 1
                {$conditions}", $_values);

        $query = $db->query($sql) or _error("SQL_ERROR");
        while($row = $query->fetch_assoc())
        {
            $badge = Badge::CreateFromArgs($row);
            $badge->extras['do_display'] = $row['do_display'];
            $items[] = $badge;
        }

        return $items;
    }

}
