<?php defined('BASEPATH') or exit('No direct script access allowed');

class Report_notes extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!has_permission('lims', '', 'admin')) {
            access_denied('LIMS');
        }

        $this->load->model('lims/report_notes_model', 'report_notes_model');
    }

    public function index()
    {
        $data['title'] = _l('lims_report_notes') ?: 'Report Notes';
        $this->load->view('lims/admin/report_notes/manage', $data);
    }

    public function table()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $p = db_prefix();

        $aColumns = ['id','code','note_el','note_en','active','sort_order'];
        $sIndexColumn = 'id';
        $sTable = $p.'lims_report_notes';

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], []);
        $output  = $result['output'];
        $rResult = $result['rResult'];

        foreach ($rResult as $aRow) {
            $id = (int)$aRow['id'];

            $noteEl = trim(strip_tags((string)$aRow['note_el']));
            $noteEn = trim(strip_tags((string)$aRow['note_en']));

            $row = [];
            $row[] = $id;
            $row[] = $aRow['code'] ? html_escape($aRow['code']) : '-';
            $row[] = html_escape(mb_strimwidth($noteEl, 0, 90, '…', 'UTF-8'));
            $row[] = html_escape(mb_strimwidth($noteEn, 0, 90, '…', 'UTF-8'));
            $row[] = ((int)$aRow['active'] === 1)
                ? '<span class="label label-success">'.(_l('yes') ?: 'Yes').'</span>'
                : '<span class="label label-default">'.(_l('no') ?: 'No').'</span>';
            $row[] = (int)$aRow['sort_order'];

            $row[] = '
                <a href="#" class="btn btn-default btn-icon js-rn-edit"
                    data-id="'.$id.'"
                    data-code="'.html_escape($aRow['code']).'"
                    data-note_el="'.html_escape($aRow['note_el']).'"
                    data-note_en="'.html_escape($aRow['note_en']).'"
                    data-active="'.(int)$aRow['active'].'"
                    data-sort_order="'.(int)$aRow['sort_order'].'"
                ><i class="fa fa-pencil"></i></a>
                <a href="#" class="btn btn-danger btn-icon js-rn-delete" data-id="'.$id.'"><i class="fa fa-trash"></i></a>
            ';

            $output['aaData'][] = $row;
        }

        echo json_encode($output);
    }

    public function save()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id = (int)$this->input->post('id');

        $payload = [
            'code'       => trim((string)$this->input->post('code')),
            'note_el'    => trim((string)$this->input->post('note_el')),
            'note_en'    => trim((string)$this->input->post('note_en')),
            'active'     => (int)$this->input->post('active') === 1 ? 1 : 0,
            'sort_order' => (int)$this->input->post('sort_order'),
        ];

        if ($payload['note_el'] === '' || $payload['note_en'] === '') {
            echo json_encode(['success' => false, 'message' => 'Greek and English text are required.']);
            return;
        }

        try {
            if ($id > 0) {
                $this->report_notes_model->update($id, $payload);
            } else {
                $id = $this->report_notes_model->add($payload);
            }

            echo json_encode(['success' => true, 'id' => (int)$id]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $ok = $this->report_notes_model->delete((int)$id);
        echo json_encode(['success' => (bool)$ok]);
    }
}
