<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$goals = [];
if (is_staff_member()) {
    $this->load->model('goals/goals_model');
    $goals = $this->goals_model->get_all_goals();
}
$staffs = [];
if (is_staff_member()) {
    $this->load->model('staff_model');
    $staffs = $this->staff_model->get('', ['active' => 1]);
}
?>

<style>

</style>

<div class="widget" id="widget-<?php echo create_widget_id('procrm_kpi'); ?>">
    <?php if (is_staff_member()) { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body padding-10">
                        <div class="widget-dragger"></div>
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php echo render_date_input('filter_from_date', null, '', ['placeholder' => 'Период']); ?>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php echo render_date_input('filter_from_date', null, '', ['placeholder' => 'Период']); ?>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <?php echo render_select(
                                    'filter_staff',
                                    $staffs,
                                    ['id', 'full_name'],
                                    null,
                                    '',
                                    [],
                                    [],
                                    '',
                                    'filter_staff_select',
                                    false
                                ); ?>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <button class="btn btn-primary">Поиск</button>
                            </div>
                        </div>
                        <hr class="hr-panel-heading-dashboard">
                        <div class="row">
                            <div class="col-md-3">
                                Кол-во звонков
                                <br/>
                                38
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                            </div>
                            <div class="col-md-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script>

</script>
