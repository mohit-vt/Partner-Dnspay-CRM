<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lead_kpi_model extends App_Model
{
    // Your confirmed pipeline IDs
    private const STATUS_LEAD = 7;
    private const STATUS_MEETING = 8;
    private const PIPELINE_STATUSES = [7,8,9,10,11];

    public function weekly_kpis(string $from, string $to): array
    {
        $total_leads = (int)$this->scalar(
            "SELECT COUNT(*) FROM tblleads WHERE status = ? AND DATE(dateadded) BETWEEN ? AND ?",
            [self::STATUS_LEAD, $from, $to]
        );

        $total_meetings = (int)$this->scalar(
            "SELECT COUNT(*) FROM tblleads WHERE status = ? AND DATE(dateadded) BETWEEN ? AND ?",
            [self::STATUS_MEETING, $from, $to]
        );

        $pipeline_value = (float)$this->scalar(
            "SELECT COALESCE(SUM(lead_value),0)
             FROM tblleads
             WHERE status IN (8,9,10,11)
               AND DATE(dateadded) BETWEEN ? AND ?",
            [$from, $to]
        );

        $unassigned = (int)$this->scalar(
            "SELECT COUNT(*)
             FROM tblleads
             WHERE (assigned IS NULL OR assigned = 0)
               AND DATE(dateadded) BETWEEN ? AND ?",
            [$from, $to]
        );

        $missing_value = (int)$this->scalar(
            "SELECT COUNT(*)
             FROM tblleads
             WHERE (lead_value IS NULL OR lead_value = 0)
               AND DATE(dateadded) BETWEEN ? AND ?",
            [$from, $to]
        );

        $next_step_field_id = $this->get_custom_field_id('leads', 'Next Step');
        $missing_next_step = null;
        if ($next_step_field_id) {
            $missing_next_step = (int)$this->scalar(
                "SELECT COUNT(*)
                 FROM tblleads l
                 LEFT JOIN tblcustomfieldsvalues v
                   ON v.relid = l.id
                  AND v.fieldid = ?
                  AND v.fieldto = 'leads'
                 WHERE DATE(l.dateadded) BETWEEN ? AND ?
                   AND (v.value IS NULL OR v.value = '')",
                [$next_step_field_id, $from, $to]
            );
        }

        $conversion_pct = $total_leads > 0
            ? round(($total_meetings / $total_leads) * 100, 2)
            : 0.0;

        return [
            'total_leads' => $total_leads,
            'total_meetings' => $total_meetings,
            'conversion_pct' => $conversion_pct,
            'pipeline_value' => $pipeline_value,
            'unassigned' => $unassigned,
            'missing_next_step' => $missing_next_step, // null if custom field not found
            'missing_value' => $missing_value,
        ];
    }

    public function leads_by_source(string $from, string $to): array
    {
        return $this->db->query(
            "SELECT ls.id AS source_id, ls.name AS source_name, COUNT(l.id) AS total
             FROM tblleads l
             LEFT JOIN tblleads_sources ls ON ls.id = l.source
             WHERE DATE(l.dateadded) BETWEEN ? AND ?
             GROUP BY ls.id, ls.name
             ORDER BY total DESC",
            [$from, $to]
        )->result_array();
    }

    public function leads_by_owner(string $from, string $to): array
    {
        return $this->db->query(
            "SELECT l.assigned AS owner_id,
                    COALESCE(CONCAT(s.firstname,' ',s.lastname), 'Unassigned') AS owner_name,
                    COUNT(l.id) AS total
             FROM tblleads l
             LEFT JOIN tblstaff s ON s.staffid = l.assigned
             WHERE DATE(l.dateadded) BETWEEN ? AND ?
             GROUP BY l.assigned, owner_name
             ORDER BY total DESC",
            [$from, $to]
        )->result_array();
    }

    public function stage_distribution(string $from, string $to): array
    {
        $ids = implode(',', self::PIPELINE_STATUSES);

        return $this->db->query(
            "SELECT l.status AS status_id, st.name AS status_name, COUNT(l.id) AS total
             FROM tblleads l
             LEFT JOIN tblleads_status st ON st.id = l.status
             WHERE l.status IN ($ids)
               AND DATE(l.dateadded) BETWEEN ? AND ?
             GROUP BY l.status, st.name
             ORDER BY l.status ASC",
            [$from, $to]
        )->result_array();
    }

    private function scalar(string $sql, array $params)
    {
        $row = $this->db->query($sql, $params)->row_array();
        if (!$row) return 0;
        return array_values($row)[0];
    }

    private function get_custom_field_id(string $fieldto, string $name): ?int
    {
        $row = $this->db->query(
            "SELECT id FROM tblcustomfields WHERE fieldto = ? AND name = ? LIMIT 1",
            [$fieldto, $name]
        )->row_array();

        return $row ? (int)$row['id'] : null;
    }
}
