<?php

namespace App\Models\Admin;

use CodeIgniter\Model;

class RewardModel extends Model
{

    public function activeRewardModel($type, $id)
    {
        $query = "UPDATE {$type}_reward SET reward_is_active = 1 WHERE reward_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function nonActiveRewardModel($type, $id)
    {
        $query = "UPDATE {$type}_reward SET reward_is_active = 0 WHERE reward_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function removeRewardModel($type, $id)
    {
        $query = "DELETE FROM {$type}_reward WHERE reward_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function approveRewardQualified($type, $adminId, $id, $datetime)
    {
        try {
            $this->db->transBegin();

            $datetime = date("Y-m-d H:i:s");
            $query = "UPDATE {$type}_reward_qualified
            SET reward_qualified_status = 'approved',
            reward_qualified_status_administrator_id = ?,
            reward_qualified_status_datetime = ?
            WHERE reward_qualified_id = ?
        ";

            $this->db->query($query, [$adminId, $datetime, $id]);

            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal mengubah status reward", 1);
            }

            $detail_qualified = $this->db->table("sys_reward_qualified")->join('sys_reward_point', 'reward_point_member_id = reward_qualified_member_id')->getWhere(["reward_qualified_id" => $id])->getRow();
            $reward = $this->db->table("sys_reward")->getWhere(["reward_id" => $detail_qualified->reward_qualified_reward_id])->getRow();
            $arr_log_reward_point = [
                'reward_point_log_member_id' => $detail_qualified->reward_qualified_member_id,
                'reward_point_log_type' => 'out',
                'reward_point_log_value' => $reward->reward_condition_point - $detail_qualified->reward_point_paid,
                'reward_point_log_note' => 'Qualified reward cash ' . $reward->reward_title . " (" . (int)$reward->reward_condition_point . ")",
                'reward_point_log_datetime' => $datetime
            ];

            $this->db->table("sys_reward_point_log")->insert($arr_log_reward_point);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambahkan log reward poin", 1);
            }

            $this->db->table("sys_reward_point")->where("reward_point_member_id", $detail_qualified->reward_qualified_member_id)->update(["reward_point_paid" => $reward->reward_condition_point]);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal update reward point", 1);
            }

            $last_point_trip_acc = $this->db->table("sys_reward_trip_point")->select("reward_point_acc")->getWhere(["reward_point_member_id" => $detail_qualified->reward_qualified_member_id])->getRow('reward_point_acc');

            $this->db->table("sys_reward_trip_point")->where("reward_point_member_id", $detail_qualified->reward_qualified_member_id)->update(["reward_point_acc" => $last_point_trip_acc + $reward->reward_trip_point]);
            if ($this->db->affectedRows() < 0) {
                throw new \Exception("Gagal update reward poin trip", 1);
            }

            $arr_log_reward_point_trip = [
                'reward_point_log_member_id' => $detail_qualified->reward_qualified_member_id,
                'reward_point_log_type' => 'in',
                'reward_point_log_value' => $reward->reward_trip_point,
                'reward_point_log_note' => 'Reward Trip dari ' . $reward->reward_title,
                'reward_point_log_datetime' => $datetime
            ];

            $this->db->table("sys_reward_trip_point_log")->insert($arr_log_reward_point_trip);
            if ($this->db->affectedRows() <= 0) {
                throw new \Exception("Gagal menambahkan reward poin trip", 1);
            }

            $this->db->transCommit();

            return true;
        } catch (\Throwable $th) {
            $this->db->transRollback();
            return false;
        }
    }

    public function rejectRewardQualified($type, $adminId, $id, $datetime)
    {
        $query = "UPDATE {$type}_reward_qualified
            SET reward_qualified_status = 'rejected',
            reward_qualified_status_administrator_id = ?,
            reward_qualified_status_datetime = ?
            WHERE reward_qualified_id = ?
        ";
        return $this->db->query($query, [$adminId, $datetime, $id]);
    }

    public function activeRewardRo($type, $id)
    {
        $query = "UPDATE bin_reward_{$type} SET reward_is_active = 1 WHERE reward_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function nonActiveRewardRo($type, $id)
    {
        $query = "UPDATE bin_reward_{$type} SET reward_is_active = 0 WHERE reward_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function removeRewardRo($type, $id)
    {
        $query = "DELETE FROM bin_reward_{$type} WHERE reward_id = ?";
        return $this->db->query($query, [$id]);
    }

    public function approveRewardRoQualified($type, $adminId, $id, $datetime)
    {
        $query = "UPDATE bin_reward_{$type}_qualified
            SET reward_qualified_status = 'approved',
            reward_qualified_administrator_id = ?,
            reward_qualified_status_datetime = ?
            WHERE reward_qualified_id = ?
        ";
        return $this->db->query($query, [$adminId, $datetime, $id]);
    }

    public function rejectRewardRoQualified($type, $adminId, $id, $datetime)
    {
        $query = "UPDATE bin_reward_{$type}_qualified
            SET reward_qualified_status = 'rejected',
            reward_qualified_administrator_id = ?,
            reward_qualified_status_datetime = ?
            WHERE reward_qualified_id = ?
        ";
        return $this->db->query($query, [$adminId, $datetime, $id]);
    }
}
